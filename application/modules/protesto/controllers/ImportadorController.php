<?php

class /*Protesto_*/ImportadorController extends Zend_Controller_Action
{

	protected $model_titulo_importado = null;
    protected $model_historico = null;
    protected $model_arquivo = null;
    protected $model_Cabecalho = null;
    protected $model_Rodape = null;    
    protected $model_portador = null;
    
    public function init()
    {
    	if ( !Zend_Auth::getInstance()->hasIdentity() ) {
	        return $this->_helper->redirector->goToRoute( array('module'=>'admin', 'controller' => 'auth'), null, true);
	    }
	    
		$params     = $this->getRequest()->getParams();			
	    $model_acl 	= new Acl();	    
	    $result = $model_acl->isAllowed($params["controller"] . "/" . $params["action"]);	    
	    if(!$result){
	    	ZendX_JQuery_FlashMessenger::addMessage("Área do sistema restrita.", 'error');
	    	$this->_redirect('/protesto/index');	    	
	    }

	    $authNamespace = new Zend_Session_Namespace('before');
		$authNamespace->before = $params["controller"] . "/" . $params["action"];
	    
	    parent::init();    	 
	    $this->model_titulo_importado = new TituloImportado();
	    $this->model_historico = new Historico; 
	    $this->model_arquivo = new Arquivo;
	    $this->model_Cabecalho = new Cabecalho();
	    $this->model_Rodape = new Rodape();
	    $this->model_portador = new Portador();	   
	    	    
    	$this->view->setEncoding('ISO-8859-1');
    }

    public function indexAction()
    {
    	$model_acl = new Acl();
        //$this->model_historico->selectTitulos();
        //$notificacao = new Zend_Acl_Setup();
    	//$model_cartorio = new Cartorio();
    }

    public function importarAction()
    {
    	set_time_limit(0);
        $form = new Protesto_Form_Importar();
        
    	$upload = new Zend_File_Transfer();
        $file = $upload->getFileInfo();
       
    	if ( $this->_request->isPost()){
            
    		if($file['arquivo']['name'] == ''){
	        	ZendX_JQuery_FlashMessenger::addMessage("Você não selecionou nenhum arquivo.", 'error');
	        } 
	        else{
    		
	    		$data = array('tipo'  => $this->_request->getPost('tipo'));
	            $nomearquivo = $file['arquivo']['name'];

	            if(($data['tipo'] == 1 && ($nomearquivo[0] != 'B' || count(str_split($nomearquivo)) != 12 ))){            	
	            	ZendX_JQuery_FlashMessenger::addMessage("Selecione o arquivo de remessa correto.", 'error');	
	            } else if(($data['tipo'] == 2 && ($nomearquivo[0] != 'D' || count(str_split($nomearquivo)) != 13 ))){
	            	ZendX_JQuery_FlashMessenger::addMessage("Selecione o arquivo de devolução correto.", 'error');
	            } else if(($data['tipo'] == 3 && ($nomearquivo[0] != 'C' || count(str_split($nomearquivo)) != 13 ))){
	            	ZendX_JQuery_FlashMessenger::addMessage("Selecione o arquivo de cancelamento correto.", 'error');	
	            }else{
	            	$user = new Zend_Session_Namespace('user_data');
	            	$data['idUsuario'] = $user->user->idUsuario;
	            	$data['arquivo'] = $nomearquivo;
	            	//$data['data_envio'] = date ( 'Y-m-d h:i:s' );
	            	
	            	$arquivo = new Arquivo();
	            	$arquivo->insert($data);
	            	$lastId = $arquivo->getAdapter()->lastInsertId();

	            	$path = APPLICATION_PATH . '/arquivos';		
					if(!file_exists($path))mkdir($path);
	            	
	            	$path .= '/importados';		
					if(!file_exists($path))mkdir($path);					
					$path .= "/" . $lastId;
	            	
	            	
	            	
	            	$upload->addFilter('Rename',
	                   				    array('target' => $path,
	                         				  'overwrite' => true));
	                   
	            	if(!$upload->receive()){
	            		$where = $arquivo->getAdapter()->quoteInto('idArquivo = ?', $lastId);
	        			$arquivo->delete($where);
	        			ZendX_JQuery_FlashMessenger::addMessage("Problemas ao importar arquivo.", 'error');	
	            	}
	            	else{
	            		
	            		if($data['tipo'] == 1){
							$idCabecalho = $this->importarRemessa($path, $lastId);
	            			if($idCabecalho){
								$form->arquivo->setValue(""); 
			            		ZendX_JQuery_FlashMessenger::addMessage("Arquivo enviado com sucesso.");

			            		//FAZER O DOWNLOAD DO ARQUIVO (envia pra o método que gera confirmação)
			            		$this->gerarConfirmacao($idCabecalho);
			            		
							}else{
								$where = $arquivo->getAdapter()->quoteInto('idArquivo = ?', $lastId);
	        					$arquivo->delete($where);						
								ZendX_JQuery_FlashMessenger::addMessage("Erro ao importar arquivo", 'error');
							}
	            		}
	            		
	            		if($data['tipo'] == 2){
	            			$erros = $this->importarDesistencia($path, $lastId);
	            			if(count($erros) == 0){
								$form->arquivo->setValue(""); 
			            		ZendX_JQuery_FlashMessenger::addMessage("Arquivo enviado com sucesso.");
							}else{						
								$linhas = '';
								for($i=0; $i<count($erros); $i++){
									$linhas .= $erros[$i] . "; ";
								}
								$where = $arquivo->getAdapter()->quoteInto('idArquivo = ?', $lastId);
	        					$arquivo->delete($where);								
								ZendX_JQuery_FlashMessenger::addMessage("Erro ao importar a(s) linha(s) " . $linhas, 'notice');
							}
	            		}
	            		
	            		if($data['tipo'] == 3){
	            			$erros = $this->importarCancelamento($path, $lastId);
							if(count($erros) == 0){
								$form->arquivo->setValue(""); 
			            		ZendX_JQuery_FlashMessenger::addMessage("Arquivo enviado com sucesso.");
							}else{
								$linhas = '';
								for($i=0; $i<count($erros); $i++){
									$linhas .= $erros[$i] . "; ";
								}
								$where = $arquivo->getAdapter()->quoteInto('idArquivo = ?', $lastId);
	        					$arquivo->delete($where);
								ZendX_JQuery_FlashMessenger::addMessage("Erro ao importar a(s) linha(s) " . $linhas, 'notice');
							}
	            		}
	            		
	            	}      		
	            		            		
	           	   }
            	}
            }
                        
    	$this->view->form = $form;
    }

    public function digitalizarprotestoAction()
    {
        $form = new Protesto_Form_DigitalizarProtesto();
        $upload = new Zend_File_Transfer();    		
	    $file = $upload->getFileInfo();
    	 
    	if ( $this->_request->isPost()){

    		$__data = $this->_request->getPost();            	
	        unset($__data['submit']); unset($__data['MAX_FILE_SIZE']);
	        
	        if ( $form->isValid($__data) ){
					$lastId = 0;
					//print_r($file['arquivo']['name']);exit;	
			        if(isset($file['arquivo']['name']) && $file['arquivo']['name'] != ''){
			        			        	
			    		$nomearquivo = $file['arquivo']['name'];            	           	
			    		$data['arquivo'] = $nomearquivo;
			    		
			            $user = new Zend_Session_Namespace('user_data');
			            $data['idUsuario'] = $user->user->idUsuario;
			            $data['tipo'] = 7; //Protesto Digitalizado 7 (Serve também para identificar qual tabela de títulos se deve procurar o dado)
			            //$data['data_envio'] = date ( 'Y-m-d h:i:s' );
			            
			            $arquivo = new Arquivo();
			            $arquivo->insert($data);
			            $lastId = $arquivo->getAdapter()->lastInsertId();
			            
			            $path = APPLICATION_PATH . '/arquivos';		
						if(!file_exists($path))mkdir($path);
								            
			            $path .=  '/digitalizados';		
						if(!file_exists($path))mkdir($path);					
						$path .= "/" . $lastId;
			            
			            $upload->addFilter('Rename', array('target' => $path,'overwrite' => true));
			                   
			            if(!$upload->receive()){
			            	$where = $arquivo->getAdapter()->quoteInto('idArquivo = ?', $lastId);
			        		$arquivo->delete($where);
			        		ZendX_JQuery_FlashMessenger::addMessage("O arquivo selecionado não foi enviado.", 'notice');
			            }
			            
			        } 
	        
					//FAZ A VERIFICAÇÃO E VALIDAÇÃO DOS CAMPOS E TALS
	            	$msg = $this->getInconcistencias_digitalizacao($__data);
	            	if($msg == ''){//se não houver problemas com as verificações do controlador
	            		unset($data);
						$data_devedor = array(
							'tipo_identificacao' => $this->_request->getPost('tipo_identificacao_devedor'),
					    	'numeroidentificacao' => preg_replace('/[^0-9]/', '', $this->_request->getPost('documento_devedor')),
		            		'nome' => strtoupper ($this->_request->getPost('nome_devedor')),
						    'cep' =>  preg_replace('/[^0-9]/', '', $this->_request->getPost('cep_devedor')),
						    'endereco' => strtoupper ($this->_request->getPost('endereco_devedor')),
						    'complemento' => strtoupper ($this->_request->getPost('complemento_devedor')), 
						    'bairro' => strtoupper ($this->_request->getPost('bairro_devedor')),
						    'numero' => strtoupper ($this->_request->getPost('numero_devedor')),
						    'cidade' => strtoupper ($this->_request->getPost('cidade_devedor')),
							'observacoes' => strtoupper ($this->_request->getPost('obs_devedor'))
			            );
			            
			            $data_cedente = array(
			            	'tipo_identificacao' => $this->_request->getPost('tipo_identificacao_cedente'),
					    	'numeroidentificacao' => preg_replace('/[^0-9]/', '', $this->_request->getPost('documento_cedente')),
		            		'nome' => strtoupper ($this->_request->getPost('nome_cedente')),
						    'cep' => preg_replace('/[^0-9]/', '', $this->_request->getPost('cep_cedente')),
						    'endereco' => strtoupper ($this->_request->getPost('endereco_cedente')),
						    'complemento' => strtoupper ($this->_request->getPost('complemento_cedente')), 
						    'bairro' => strtoupper ($this->_request->getPost('bairro_cedente')),
						    'numero' => strtoupper ($this->_request->getPost('numero_cedente')),
						    'cidade' => strtoupper ($this->_request->getPost('cidade_cedente')),
							'observacoes' => strtoupper ($this->_request->getPost('obs_cedente'))
			            );
			            
			            $data_apresentante = array(
			            	'tipo_identificacao' => $this->_request->getPost('tipo_identificacao_apresentante'),
					    	'numeroidentificacao' =>  preg_replace('/[^0-9]/', '', $this->_request->getPost('documento_apresentante')),
		            		'nome' => strtoupper ($this->_request->getPost('nome_apresentante')),
						    'cep' => preg_replace('/[^0-9]/', '', $this->_request->getPost('cep_apresentante')),
						    'endereco' => strtoupper ($this->_request->getPost('endereco_apresentante')),
						    'complemento' => strtoupper ($this->_request->getPost('complemento_apresentante')), 
						    'bairro' => strtoupper ($this->_request->getPost('bairro_apresentante')),
						    'numero' => strtoupper ($this->_request->getPost('numero_apresentante')),
						    'cidade' => strtoupper ($this->_request->getPost('cidade_apresentante')),
							'observacoes' => strtoupper ($this->_request->getPost('obs_apresentante'))
			            );
			            
			            $data_sacador = array(
			            	'tipo_identificacao' => $this->_request->getPost('tipo_identificacao_sacador'),
					    	'numeroidentificacao' => preg_replace('/[^0-9]/', '', $this->_request->getPost('documento_sacador')),
		            		'nome' => strtoupper ($this->_request->getPost('nome_sacador')),
						    'cep' => preg_replace('/[^0-9]/', '',$this->_request->getPost('cep_sacador')),
						    'endereco' => strtoupper ($this->_request->getPost('endereco_sacador')),
						    'complemento' => strtoupper ($this->_request->getPost('complemento_sacador')), 
						    'bairro' => strtoupper ($this->_request->getPost('bairro_sacador')),
						    'numero' => strtoupper ($this->_request->getPost('numero_sacador')),
						    'cidade' => strtoupper ($this->_request->getPost('cidade_sacador')),
							'observacoes' => strtoupper ($this->_request->getPost('obs_sacador'))
			            );
	            		
			           		           
			            $data_titulo['idSituacao'] = 20; //20 - Aberto . (Sempre será aberto quando cadastrar.)
			            $data_titulo['idProtocolo'] = $this->getUltimoProtocolo();
			            $data_titulo['idEspecietitulo'] = $this->_request->getPost('idEspecietitulo');
			            $data_titulo['dataemissaotitulo'] = implode("-", array_reverse(explode("/", $this->_request->getPost('dataemissaotitulo'))));
			            $data_titulo['datavencimentotitulo'] = implode("-", array_reverse(explode("/", $this->_request->getPost('datavencimentotitulo'))));
			            $data_titulo['dataocorrencia'] = date('Y-m-d');
			            
			            $data_titulo['numerotitulo'] = $this->_request->getPost('numerotitulo');
			            $data_titulo['idAgencia'] = $this->_request->getPost('idAgencia');
			            $data_titulo['titulo_bancario'] = strtoupper ($this->_request->getPost('titulo_bancario'));
			            $data_titulo['codigocedente_agencia'] = $this->_request->getPost('codigocedente_agencia');
			            $data_titulo['pracaprotesto'] = $this->_request->getPost('pracaprotesto');
			            $data_titulo['valortitulo'] = str_replace(',', '.', str_replace('.', '',$this->_request->getPost('valortitulo')));
			            $data_titulo['saldotitulo'] = str_replace(',', '.', str_replace('.', '',$this->_request->getPost('saldotitulo')));
			            //$data_titulo['valorcustascartorio'] = str_replace(',', '.', str_replace('.', '',$this->_request->getPost('valorcustascartorio')));
			            //nosso numero, tipomoeda, informacao sobre aceite (aceite), tipo de ocorrencia, informações sobre aceite (colocar em protestos) 		            
			            //$data_titulo['pagas'] = $this->_request->getPost('codigocedente_agencia');//referente as custas
			            //$data_titulo['intim_out'] = $this->_request->getPost('codigocedente_agencia');
			            //$data_titulo['conducao'] = $this->_request->getPost('codigocedente_agencia');
			            //$data_titulo['certidao'] = $this->_request->getPost('codigocedente_agencia');
			            //$data_titulo['taxajudiciaria'] = $this->_request->getPost('codigocedente_agencia');
			            $data_titulo['tipoendosso'] = $this->_request->getPost('tipoendosso');
			            //$data_titulo['contraapresentacao'] = $this->_request->getPost('codigocedente_agencia');
			            //$data_titulo['avista'] = $this->_request->getPost('codigocedente_agencia');
			            //$data_titulo['contraprotesto'] = $this->_request->getPost('codigocedente_agencia');
			            //$data_titulo['finsfamiliares'] = $this->_request->getPost('codigocedente_agencia');
			            //$data_titulo['aceite'] = $this->_request->getPost('codigocedente_agencia');
			            
			            //procura devedor, cedente, favorecido, apresentante
			            $data_titulo['idPessoa_devedor'] = 0;
	            		$data_titulo['idPessoa_devedor'] = $this->cadastrarpessoa($data_devedor);					
	            		$data_titulo['idPessoa_cedente'] = $this->cadastrarpessoa($data_cedente);
	            		
	            		if($this->_request->getPost('documento_apresentante') == '')
	            			$data_titulo['idPessoa_apresentante'] = $data_titulo['idPessoa_cedente'];
	            		else
	            			$data_titulo['idPessoa_apresentante'] = $this->cadastrarpessoa($data_apresentante);
	            			
						if($this->_request->getPost('documento_sacador') == '')
	            			$data_titulo['idPessoa_sacador'] = $data_titulo['idPessoa_cedente'];
	            		else
	            			$data_titulo['idPessoa_sacador'] = $this->cadastrarpessoa($data_sacador);
	            					
	            		//Dados para o cap_protestos
	            		$data['idTitulo'] = $this->cadastrartitulo($data_titulo);	            		
	            		//$data['data_entrada'] = date ( 'Y-m-d h:i:s' );
	            		$data['idVigencia'] = $this->getVigenciaCustas();
	            		$data['idArquivo'] = $lastId;
	            		
						$model_protesto = new Protesto();
						$model_protesto->insert($data);			
	            
						$data_historico['idProtesto'] = $model_protesto->getAdapter()->lastInsertId();
						$data_historico['idTitulo'] = $data['idTitulo'];
						$data_historico['idSituacao'] = $data_titulo['idSituacao'];
						//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
						$data_historico['descricao'] = "Título cadastrado.";
									
						$this->cadastrarhistoricotitulo($data_historico);
						
	            		$form->arquivo->setValue(""); 
	            		ZendX_JQuery_FlashMessenger::addMessage("Título cadastrado com sucesso.");
	            		$form = new Protesto_Form_DigitalizarProtesto();
	            	}
	            	else{
	            		ZendX_JQuery_FlashMessenger::addMessage($msg, 'warning');
	            	}
	          }	            
          }
                           
    	$this->view->form = $form;
    }

    public function cadastrarespecieAction()
    {
        $form = new Protesto_Form_Especietitulo();
        
        if ( $this->_request->isPost()){
	        	$data = array(		        	
		        	'codigo'  => $this->_request->getPost('codigo'),
	                'descricao' => $this->_request->getPost('descricao')	            	
	            );
	
	            if ( $form->isValid($data) )
	            {
	            	$model = new Especie();
	            	$model->insert($data);	                
	               ZendX_JQuery_FlashMessenger::addMessage("Dados cadastrados com sucesso.");
	            }
        }
        
        $this->view->form = $form;
    }

    public function cadastrarsituacaoAction()
    {
        $form = new Protesto_Form_Situacao();
        
        if ( $this->_request->isPost()){
	        	$data = array(
	        		'codigo'  => $this->_request->getPost('codigo'),
	                'descricao' => $this->_request->getPost('descricao')	            	
	            );
	
	            if ( $form->isValid($data) )
	            {
	            	$model = new Situacao();
	            	$model->insert($data);	                
	                ZendX_JQuery_FlashMessenger::addMessage("Dados cadastrados com sucesso.");
	            }
        }
        
        $this->view->form = $form;
    }

    public function arquivosimportadosAction()
    {
        $select =  $this->model_arquivo->selectArquivosImportados();
        //$this->_helper->Valor->_pvar($select);
    	$data = $select;
    	
        $this->view->arquivos = $data;
    }

    public function downloadarquivoAction()
    {
        $id = (int) $this->_getParam('idArquivo');
        
        $arquivo = $this->model_arquivo->selectArquivosById($id);
        
        $path = APPLICATION_PATH ;
        
        if($arquivo->tipo > 0 && $arquivo->tipo < 4)
        	$path .= '/arquivos/importados/' . $id;
        if($arquivo->tipo == 4)
        	$path .= '/arquivos/confirmacao/' . $id;
        if($arquivo->tipo == 5)
        	$path .= '/arquivos/retorno/' . $id;
        if($arquivo->tipo == 7)
        	$path .= '/arquivos/digitalizados/' . $id;
        if($arquivo->tipo == 8)
        	$path .= '/arquivos/serasa/cancelamento/' . $id;
        if($arquivo->tipo == 9)
        	$path .= '/arquivos/serasa/inclusao/' . $id;
        if($arquivo->tipo == 10)
        	$path .= '/arquivos/notificacoes/' . $id . '.pdf';
        if($arquivo->tipo == 11)
        	$path .= '/arquivos/instrumentos/' . $id . '.pdf';
        if($arquivo->tipo == 12)
        	$path .= '/arquivos/remessacobranca/' . $id;
        if($arquivo->tipo == 13)
        	$path .= '/arquivos/retornocobranca/' . $id;
        
        	//print_r($path);exit;
        	
        if(file_exists($path)){	 
		    header('Content-type: octet/stream');
		    header('Content-disposition: attachment; filename="'.$arquivo->arquivo.'";');
		    header('Content-Length: '.filesize($path));
		    readfile($path);
		    exit;
        }
        
        ZendX_JQuery_FlashMessenger::addMessage("Esse arquivo não existe!", 'error');
        $this->_redirect('/importador/arquivosimportados');
    }

    public function getcidadesAction()
    {
       $this->_helper->layout ()->disableLayout ();
	   $this->_helper->viewRenderer->setNoRender ();

	       	
	   $id = (int) $this->_getParam('idEstado');
	   $id = $_GET['id'];
       $model_cidade = new Cidade();
       $cidades = $model_cidade->findForSelect($id)->toArray();
   	   //print_r($cidades);
       $combo = "<option value='0'>Carregando ... </option>";

       if (count($cidades) > 0) {
           
       	   $combo = array ();
           $combo .= '<option value = "">Selecione a Cidade</option>';
           foreach ( $cidades as $lista ) {           	
           			if($lista ['idCidade'] == 9899){
                  		$combo .= '<option value="' . $lista ['idCidade'] . '" selected="selected">' . $lista ['nome'] . '</option>';
           			}
                  	else{
                  		$combo .= '<option value="' . $lista ['idCidade'] . '">' . $lista ['nome'] . '</option>';           
                  	}
           }
      }
      
      echo $combo;
    }

    public function getemolumentoAction()
    {
       $this->_helper->layout ()->disableLayout ();
	   $this->_helper->viewRenderer->setNoRender ();

	   $valor = $_GET['valor'];
	   //$valor = (int) $this->_getParam('valor');
	   $valor = str_replace('.', '', $valor);
	   $valor = str_replace(',', '.', $valor);
       $model_emolumento = new Emolumento();
       $emolumento = $model_emolumento->getEmolumento($valor);
   	   
       $html = "<input type='text' name='emolumento' id='emolumento' value='0,00' />";
       
       if(isset($emolumento->emolumento)){       		
       		$html = "<input type='text' name='emolumento' id='emolumento'  value='" . number_format($emolumento->emolumento, 2, ",", ".") . "' />";       		
       }
       
  	   echo $html;
  	   
  	   exit ();
    }

    public function getagenciasAction()
    {
       $this->_helper->layout ()->disableLayout ();
	   $this->_helper->viewRenderer->setNoRender ();

	       	
	   $id = (int) $this->_getParam('idBanco');
	   $id = $_GET['id'];
       $model_agencia = new Agencia();
       $agencias = $model_agencia->findForSelect($id)->toArray();
       $combo = "<option value='0'>Carregando ... </option>";

       if (count($agencias) > 0) {           
       	   $combo = array ();
           $combo .= '<option value = "">Selecione a Agência</option>';
           foreach ( $agencias as $lista ) {           	
                  		$combo .= '<option value="' . $lista ['idAgencia'] . '">' . $lista['codigo'] . " - " . $lista ['descricao'] . '</option>';
           }
      }
      
      echo $combo;
    }

    public function getpessoaAction()
    {
       $this->_helper->layout ()->disableLayout ();
	   $this->_helper->viewRenderer->setNoRender ();

	   $doc = $_GET['doc'];
	   //$doc = $this->_getParam('doc');
	   $doc = preg_replace('/[^0-9]/', '', $doc);
	   
       $model_pessoa = new Pessoa();
       $pessoa = $model_pessoa->findByDocumento($doc);
       //$this->_helper->Util->_pvar($pessoa->Current());exit;      
       if(isset($pessoa[0]->idPessoa)){ 
       		$pessoa[0]->cep =$this->_helper->Util->ajustaCEP($pessoa[0]->cep);      		
       		$nome = "<input type='hidden' name='idnome".$doc."' id='idnome".$doc."'  value='" . htmlentities($pessoa[0]->nome) . "' >";
       		$cep = "<input type='hidden' name='idcep".$doc."' id='idcep".$doc."'  value='" . $pessoa[0]->cep . "' >";
       		$endereco = "<input type='hidden' name='idendereco".$doc."' id='idendereco".$doc."'  value='" . htmlentities($pessoa[0]->endereco) . "' >";
       		$complemento = "<input type='hidden' name='idcomplemento".$doc."' id='idcomplemento".$doc."'  value='" . htmlentities($pessoa[0]->complemento) . "' >";
       		$bairro = "<input type='hidden' name='idbairro".$doc."' id='idbairro".$doc."'  value='" . htmlentities($pessoa[0]->bairro) . "' >";
       		$numero = "<input type='hidden' name='idnumero".$doc."' id='idnumero".$doc."'  value='" . $pessoa[0]->numero . "' >";
       		$uf = "<input type='hidden' name='iduf".$doc."' id='iduf".$doc."'  value='" . $pessoa[0]->estado . "' >";
       		$cidade = "<input type='hidden' name='idcidade".$doc."' id='idcidade".$doc."'  value='" . $pessoa[0]->cidade . "' >";
       		$obs = "<input type='hidden' name='idobs".$doc."' id='idobs".$doc."'  value='" . $pessoa[0]->observacoes . "' >";
       		
       		echo $nome . $cep . $endereco . $complemento . $bairro .$numero . $uf . $cidade . $obs;
       }
        	   
  	   exit ();
    }

    public function retornosAction()
    {
        $select =  $this->model_arquivo->selectArquivosExportados();
    	$data = $select;
        $this->view->arquivos = $data;
    }

    public function criarretornoAction()
    {
       //$this->_helper->layout->disableLayout();
    	
    	$form = new Protesto_Form_Portador();
    	
    	if ( $this->_request->isPost() ){
        	$id = $this->_request->getPost('idPortador');
        	//print_r($id);exit;
        	
        	if($this->gerarRetorno($id))
        		ZendX_JQuery_FlashMessenger::addMessage('Retorno gerado com sucesso. ');
        	else 
        		ZendX_JQuery_FlashMessenger::addMessage('Retorno não pode ser gerado. ', 'notice');
        }
    	
    	$this->view->form = $form;
    }
    
    public function importarRemessa($path, $idArquivo)
    {
    		//FAZ A VERIFICAÇÃO E VALIDAÇÃO DOS CAMPOS E TALS
            //recebe conteudo do arquivo
            $file_content = file($path);

            $erros = $this->verificarLinhas($file_content);
            
            if(count($erros) > 0){
            	$_ver = '';
            	for($i=0; $i<count($erros); $i++){
					$_ver .= $erros[$i] . "; ";
				}								
				ZendX_JQuery_FlashMessenger::addMessage("A(s) linha(s) " . $_ver . " não constam no arquivo.", 'notice');
            }
            
            
            $data['idCabecalho'] = $this->processaConteudoArquivo_cabecalho($file_content[0]); //1º linha do arquivo            		
            $idRodape = $this->processaConteudoArquivo_rodape($file_content[count($file_content)-1], $data['idCabecalho']); //Última linha do arquivo
            //print_r($idRodape);exit;
            $model_protesto = new Protesto();
                        		
            for($i=1 ; $i < count($file_content)-1 ; $i++){            			
            	$_titulo = $this->processaConteudoArquivo_titulo($file_content[$i], $data['idCabecalho']);

            	if($_titulo['idProtocolo'] > 0){ 
            		           	
	            	$data_protesto['idTitulo'] = $this->cadastrartitulo_importacao($_titulo);
	            	$data_protesto['idArquivo'] = $idArquivo;
	            	//$data_protesto['data_entrada'] = date ( 'Y-m-d h:i:s' );
					$data_protesto['idVigencia'] = $this->getVigenciaCustas();

					if(!$model_protesto->insert($data_protesto)){
						return false;
					}
					//$this->_helper->Util->_pvar($_titulo['idProtocolo']);
					$data_historico['idProtesto'] = $model_protesto->getAdapter()->lastInsertId();
					$data_historico['idTitulo'] = $data_protesto['idTitulo']; 
					$data_historico['idSituacao'] = $_titulo['idSituacao'];
					//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
					$data_historico['descricao'] = "Título cadastrado.";
								
					$this->cadastrarhistoricotitulo($data_historico);	            	
	            }
	            
            	else{// apenas cadastra o titulo sem cadastar um protesto pra ele
            		 $this->cadastrartitulo_importacao($_titulo);
            	}
            	
            }
            //exit;
            return $data['idCabecalho'];
    }

    public function importarDesistencia($path, $idArquivo)
    {
    		
            $file_content = file($path);
            
            $model_protesto = new Protesto();

            $erros = array();
            
            for($i=2 ; $i < count($file_content)-2 ; $i++){            			
            	$_titulo = $this->processaDesistencia($file_content[$i]);            	
            	//IF $_titulo FALSE, SALVA A LINHA QUE NÃO IMPORTOU
            	if(!$_titulo){
            		$erros[] = $i;
            	}            	
            }            
            return $erros;
    }

    public function importarCancelamento($path, $idArquivo)
    {
    		
            $file_content = file($path);
            
            $model_protesto = new Protesto();

            $erros = array();
            
            for($i=2 ; $i < count($file_content)-1 ; $i++){            			
            	$_titulo = $this->processaCancelamento($file_content[$i]);            	
            	//IF $_titulo FALSE, SALVA A LINHA QUE NÃO IMPORTOU
            	if(!$_titulo){
            		$erros[] = $i;
            	}            	
            }            
            return $erros;
    }
    
	public function cadastrarpessoa($data)
    {
    	$model_pessoa = new Pessoa();
    		//$this->_pvar($data);exit;	        
        $select =  $model_pessoa->select() 
                   		  ->setIntegrityCheck(false) 
              			  ->where("numeroidentificacao = '" . $data['numeroidentificacao'] . "'");
        $pessoa = $model_pessoa->fetchAll($select);
        
        
        $data_endereco['cep'] = $data['cep']; unset($data['cep']);
		$data_endereco['rua'] = $data['endereco']; unset($data['endereco']);
		$data_endereco['complemento'] = $data['complemento']; unset($data['complemento']);
		$data_endereco['bairro'] = $data['bairro']; unset($data['bairro']);
		$data_endereco['numero'] = $data['numero']; unset($data['numero']);
		$data_endereco['idCidade'] = $data['cidade']; unset($data['cidade']);
        
		if(count($pessoa) > 0){
			
			$model_pessoa->update($data, "idPessoa = " . $pessoa->Current()->idPessoa);

			$this->cadastrarendereco($data_endereco, $pessoa->Current()->idEndereco);
			
			return $pessoa->Current()->idPessoa;
		}
		else{			
			$data['idEndereco'] = $this->cadastrarendereco($data_endereco);
			 
			$model_pessoa->insert($data);
            return $model_pessoa->getAdapter()->lastInsertId();
		}
		
    	
   		 
    }

    public function cadastrarendereco($data, $id = '')
    {
    	 $model_endereco = new Endereco();
    	 
    	if($id){
    		$data['cep'] = preg_replace('/[^0-9]/', '', $data['cep']);
    		$model_endereco->update($data, "idEndereco = " . $id);
    	}
    	else{
			$data['cep'] = preg_replace('/[^0-9]/', '', $data['cep']);

        	$model_endereco->insert($data);
        	return $model_endereco->getAdapter()->lastInsertId();
    	}
    	
    	return true;
		
    }

    public function cadastrartitulo($data)
    {
    	$model_titulo = new Titulo();
    		        
        $select =  $model_titulo->select() 
                   		  		->setIntegrityCheck(false) 
              			  		->where("idProtocolo = ?", $data['idProtocolo']);
        $titulo = $model_titulo->fetchAll($select);
        
		if(isset($titulo['_data'])){
			return $titulo['_data']->idTitulo;
		}
		else{
			//cadastrar historio antes de cadastrar o titulo
			$model_titulo->insert($data);
			$idTitulo = $model_titulo->getAdapter()->lastInsertId();
			/*$data_historico['idTitulo'] = $idTitulo;
			$data_historico['idSituacao'] = $data['idSituacao'];
			$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
			$data_historico['descricao'] = "Título cadastrado.";
						
			$this->cadastrarhistoricotitulo($data_historico);*/
			$this->updateprotocolo($data['idProtocolo']);
			 
			
            return $idTitulo;
		}	
    }

    public function cadastrartitulo_importacao($data)
    {

        $select =  $this->model_titulo_importado->select() 
                   		  						->setIntegrityCheck(false) 
              			  						->where("idProtocolo = ?", $data['idProtocolo'])
              			  						->where("idProtocolo > 0");//para os arquivos que são irregulares
        $titulo = $this->model_titulo_importado->fetchAll($select);
        
		if(isset($titulo['_data'])){
			return $titulo['_data']->idTitulo;
		}
		else{
			//cadastrar historio antes de cadastrar o titulo
			$this->model_titulo_importado->insert($data);
			$idTitulo = $this->model_titulo_importado->getAdapter()->lastInsertId();
			
			$this->updateprotocolo($data['idProtocolo']);
			
            return $idTitulo;
		}
		
    }

    public function updatetitulo_importacao($data, $idTitulo)
    {
		if($this->model_titulo_importado->update($data, "idTitulo = " . $idTitulo)){		
        	return true;
		}
        	
        return false;		
    }

    public function cadastrarhistoricotitulo($data)
    {
    	
		$model_historico = new Historico();
    	    	
		$model_historico->insert($data);
		
		return $model_historico->getAdapter()->lastInsertId();
		
    }

    public function updateprotocolo($idProtocolo)
    {
    	$model_protocolo = new Protocolo();

	 	$select =  $model_protocolo->select() 
                   		  		   ->setIntegrityCheck(false) 
              			  		   ->where("idProtocolo = ?", $idProtocolo);
        $protocolo = $model_protocolo->fetchAll($select);
                
        $data_protocolo = array(
        						'situacao' => '2'
        				  );
        
        
		if(isset($protocolo['_data'])){			
	        $where = $model_protocolo->getAdapter()->quoteInto('idProtocolo = ?', $idProtocolo);
	        $model_protocolo->update($data_protocolo, $where);

	        $data_protocolo = array(
        							'protocolo' => ($protocolo['_data']->protocolo + 1),
	        						'situacao' => '1'        						
        				  	  );
        	$model_protocolo->insert($data_protocolo);
			$model_protocolo->getAdapter()->lastInsertId();
		}
    	
    }

    public function getEspecieByCodigo($codigo)
    {
    	$model_especie = new Especie();

	 	$select =  $model_especie->select() 
                   		  		   ->setIntegrityCheck(false) 
              			  		   ->where("codigo = '" . $codigo ."'");
        $especie = $model_especie->fetchAll($select);
        
        if(isset($especie['_data']))
        	return $especie['_data']->idEspecietitulo;
        
        return 0;
    }

    public function getUltimoProtocolo()
    {
    	$model_protocolo = new Protocolo();

    	/****VERIFICAR SE O BANCO TA VAZIO***/
	 	$select = $model_protocolo->select()
    				  			  ->setIntegrityCheck(false)
    				  			  ->where("situacao = ?", 1); 

    	$protocolo = $model_protocolo->fetchAll($select);
    	
    	if(count($protocolo) == 0){
    		$model_protocolo->insert(array('protocolo'=>1, 'situacao' => 1));
    		return $model_protocolo->getAdapter()->lastInsertId();
    	}
        //$this->_pvar($protocolo[count($protocolo)-1]);exit;        
        return $protocolo[count($protocolo)-1]->idProtocolo;
    }

    public function getVigenciaCustas()
    {
    	$model_vigencia = new Vigencia();
       	$vigencia = $model_vigencia->getLastVigencia();
        return $vigencia->idVigencia;
    }

    public function getPortador($data)
    {
    	    	
	 	$select = $this->model_portador->select()
    				  			  ->setIntegrityCheck(false)
    				  			  ->where("numerocodigoportador = ?", $data['numerocodigoportador']); 
              		   
    	$portador = $this->model_portador->fetchAll($select)->Current();

    	$id = '';
    	
    	if(count($portador) == 0){
    		$data_p['numerocodigoportador'] = $data['numerocodigoportador'];
    		$data_p['nomeportador'] = $data['nomeportador'];
    		$data_p['idagenciacentralizadora'] = $data['idagenciacentralizadora'];
    		
    		$this->model_portador->insert($data_p);
    		
    		$id = $this->model_portador->getAdapter()->lastInsertId();
    	}
    	else if(trim($portador->nomeportador) != trim($data['nomeportador'])){//atualiza o nome do portador caso este mude.
    		
    		$id = $portador->idPortador;
    		
    		$data_p['nomeportador'] = $data['nomeportador'];
    		$data_p['idagenciacentralizadora'] = $data['idagenciacentralizadora'];    		
    		$this->model_portador->update($data_p, "idPortador = " . $id);
    	}
    	else{
    		$id = $portador->idPortador;
    	}
    	
        return $id;
    }

    public function processaConteudoArquivo_cabecalho($line)
    {
    	
		$data = array( 'idregistro' => '', 'numerocodigoportador' => '', 'nomeportador' => '', 'datamovimento' => '', 'idtransacao_remetente' => '', 
					   'idtransacao_destinatario' => '', 'idtransacao_tipo' => '', 'numerosequencialremessa' => '', 
					   'quantidaderegistrosremessa' => '', 'quantidadetitulosremessa' => '', 'quantidadeindicacoesremessa' => '', 
					   'quantidadeoriginaisremessa' => '', 'idagenciacentralizadora' => '', 'versaolayout' => '', 'codigomunicipiopracapagamento' => '', 
					   'numerosequencialarquivo' => '', 'idPortador' => '' );
		
		for($i=0; $i<strlen($line); $i++){
			if($i == 0)
				$data['idregistro'] .= $line[$i];
				
			if($i >= 1 && $i <= 3)
				$data['numerocodigoportador'] .= $line[$i];
				
			if($i >= 4 && $i <= 43)				
				$data['nomeportador'] .= $line[$i];			
				
			if($i >= 44 && $i <= 51)
				$data['datamovimento'] .= $line[$i];
				
			if($i >= 52 && $i <= 54)
				$data['idtransacao_remetente'] .= $line[$i];
				
			if($i >= 55 && $i <= 57)
				$data['idtransacao_destinatario'] .= $line[$i];
				
			if($i >= 58 && $i <= 60)
				$data['idtransacao_tipo'] .= $line[$i];
				
			if($i >= 61 && $i <= 66)
				$data['numerosequencialremessa'] .= $line[$i];
				
			if($i >= 67 && $i <= 70)
				$data['quantidaderegistrosremessa'] .= $line[$i];
				
			if($i >= 71 && $i <= 74)
				$data['quantidadetitulosremessa'] .= $line[$i];
				
			if($i >= 75 && $i <= 78)
				$data['quantidadeindicacoesremessa'] .= $line[$i];
				
			if($i >= 79 && $i <= 82)
				$data['quantidadeoriginaisremessa'] .= $line[$i];
				
			if($i >= 83 && $i <= 88)
				$data['idagenciacentralizadora'] .= $line[$i];
				
			if($i >= 89 && $i <= 91)
				$data['versaolayout'] .= $line[$i];
				
			if($i >= 92 && $i <= 98)
				$data['codigomunicipiopracapagamento'] .= $line[$i];
			
			if($i >= 596)
				$data['numerosequencialarquivo'] .= $line[$i];			
		}
		
		$data['datamovimento'] = $this->_helper->Util->converteData($data['datamovimento'], '-');
		$data['idPortador'] = $this->getPortador($data);
		

		unset($data['numerocodigoportador']);unset($data['nomeportador']);
		
		$this->model_Cabecalho->insert($data);
		
		return $this->model_Cabecalho->getAdapter()->lastInsertId();		
    }

    public function processaConteudoArquivo_rodape($line, $idCabecalho)
    {
    	
		$data = array('idCabecalho' => $idCabecalho, 'idregistro' => '', 'somatorioseguranca_quantidade' => '', 'somatorioseguranca_valor' => '', 
					  'numerosequencialarquivo' => '');
    	
		for($i=0; $i<strlen($line); $i++){
			if($i == 0)
				$data['idregistro'] = $line[$i];
			
			if($i >= 52 && $i <= 56){
				$data['somatorioseguranca_quantidade'] .= $line[$i];								 
			}								
			if($i >= 57 && $i <= 74){
				$data['somatorioseguranca_valor'] .= $line[$i];
			}								
			if($i >= 596){								
				$data['numerosequencialarquivo'] .= $line[$i];
			}											
		}				
    	//$this->_pvar($data); exit;
    	
    	    	
		$this->model_Rodape->insert($data);
		
		return $this->model_Rodape->getAdapter()->lastInsertId();
    }

    public function processaConteudoArquivo_titulo($line, $idCabecalho)
    {
    	
		$data = array(	'idSituacao' => '', 'idProtocolo' => '', 'idCabecalho' => '', 'idregistro' => '', 'codigocedente_agencia' => '', 'nomecedente' => '', 'nomesacador' => '', 'documentosacador' => '', 
					  	'enderecosacador' => '', 'cepsacador' => '', 'cidadesacador' => '', 'ufsacador' => '', 'nossonumero' => '', 'especietitulo' => '', 'numerotitulo' => '', 'dataemissaotitulo' => '', 
						'datavencimentotitulo' => '', 'tipomoeda' => '', 'valortitulo' => '', 'saldotitulo' => '', 'pracaprotesto' => '', 'tipoendosso' => '', 'informacaosobreaceite' => '', 
						'numerocontroledevedor' => '', 'nomedevedor' => '', 'tipoidentificacaodevedor' => '', 'numeroidentificacaodevedor' => '', 'documentodevedor' => '', 'enderecodevedor' => '', 
						'cepdevedor' => '', 'cidadedevedor' => '', 'ufdevedor' => '', 'codigocartorio' => '',  'tipoocorrencia' => '', 
						'valorcustascartorio' => '', 'declaracaoportador' => '', 'dataocorrencia' => '', 'codigoirregularidade' => '', 'bairrodevedor' => '', 'valorcustascartoriodistribuidor' => '', 
						'registrodistribuicao' => '', 'valorgravacaoeletronica' => '', 'numerooperacaobanco' => '', 'numerocontratobanco' => '', 'numeroparcelacontrato' => '', 
						'tipoletracambio' => '', 'complementocodigoirregularidade' => '', 'protestomotivofalencia' => '', 'instrumentoprotesto' => '', 'valordemaisdespesas' => '', 
						'numerosequencialregistro' => '');
		
		for($i=0; $i<strlen($line); $i++){
			if($i == 0){
 				$data['idregistro'] .= $line[$i];
			}
			/*if($i >= 1 && $i <= 3){ // essa informação consta no cabeçalho
				$data['numerocodigoportador'] = $data['numerocodigoportador'] . $line[$i];  
			}*/
			if($i >= 4 && $i <= 18){
				$data['codigocedente_agencia'] .= $line[$i]; 
			}
			if($i >= 19 && $i <= 63){//	TEM QUE VER ISSAQUÊ/*******************************************/
				$data['nomecedente'] .= $line[$i]; 
			}
			if($i >= 64 && $i <= 108){
				$data['nomesacador'] .= $line[$i];
			}
			if($i >= 109 && $i <= 122){
				$data['documentosacador'] .= $line[$i];
			}
			if($i >= 123 && $i <= 167){ 
				$data['enderecosacador'] .= $line[$i];
			}
			if($i >= 168 && $i <= 175){ 
				$data['cepsacador'] .= $line[$i];
			}
			if($i >= 176 && $i <= 195){ 
				$data['cidadesacador'] .= $line[$i];
			}
			if($i >= 196 && $i <= 197){ 
				$data['ufsacador'] .= $line[$i];
			}
			if($i >= 198 && $i <= 212){
				$data['nossonumero'] .= $line[$i];
			}
			if($i >= 213 && $i <= 215){ //BUSCAR ESSE DADO NO BANCO DE DADOS ATRAVÉS DO CODIGO FORNECIDO AQUI, SETAR NO IDESPECIETITULO E APAGAR ESSE CAMPO DO ARRAY //*******************************************
				$data['especietitulo'] .= $line[$i];
			}	
			if($i >= 216 && $i <= 226){ 
				$data['numerotitulo'] .= $line[$i];
			}
			if($i >= 227 && $i <= 234){ 
				$data['dataemissaotitulo'] .= $line[$i];
			}
			if($i >= 235 && $i <= 242){ 
				$data['datavencimentotitulo'] .= $line[$i];
			}
			if($i >= 243 && $i <= 245){
				$data['tipomoeda'] .= $line[$i];
			}
			if($i >= 246  && $i <= 259){ 
				$data['valortitulo'] .= $line[$i];
			}
			if($i >= 260 && $i <= 273){ 
				$data['saldotitulo'] .= $line[$i];
			}
			if($i >= 274 && $i <= 293){ 
				$data['pracaprotesto'] .= $line[$i];
			}
			if($i == 294){ 
				$data['tipoendosso'] .= $line[$i];
			}
			if($i == 295){ 
				$data['informacaosobreaceite'] .= $line[$i];
			}
			if($i == 296){ 
				$data['numerocontroledevedor'] .= $line[$i];
			}
			if($i >= 297 && $i <= 341){ 
				$data['nomedevedor'] .= $line[$i];
			}
			if($i >= 342 && $i <= 344){ 
				$data['tipoidentificacaodevedor'] .= $line[$i];
			}
			if($i >= 345 && $i <= 358){
				if($data['tipoidentificacaodevedor'] == '001') 
					$data['numeroidentificacaodevedor'] .= $line[$i];
				else if($i >= 348)//se for um tipo CPF, ele retira os dois primeiros zeros q o completam, para poder nao dar bug na hora do cadastro/Busca dele como pessoa 
					$data['numeroidentificacaodevedor'] .= $line[$i];				
			}
			if($i >= 359 && $i <= 369){
				$data['documentodevedor'] .= $line[$i];
			}
			if($i >= 370 && $i <= 414){ 
				$data['enderecodevedor'] .= $line[$i];
			}
			if($i >= 415 && $i <= 422){ 
				$data['cepdevedor'] .= $line[$i];
			}
			if($i >= 423 && $i <= 442){ 
				$data['cidadedevedor'] .= $line[$i];
			}
			if($i >= 443 && $i <= 444){ 
				$data['ufdevedor'] .= $line[$i];
			}			
			if($i >= 445 && $i <= 446){ 
				$data['codigocartorio'] .= '';//VERIFICAR QUEM É ESSE DADO
			}
			//n° protocolo 446-457			
			//tipo de ocorrencia 458-458
			//data do protocolo 459
			if($i >= 466 && $i <= 475){ 
				$data['valorcustascartorio'] .= $line[$i];
			}
			if($i == 476){ 
				$data['declaracaoportador'] .= $line[$i];
			}			
			if($i >= 485 && $i <= 486){ 
				$data['codigoirregularidade'] .= $line[$i];
			}
			if($i >= 487 && $i <= 506){ 
				$data['bairrodevedor'] .= $line[$i];
			}
			if($i >= 507 && $i <= 516){ 
				$data['valorcustascartoriodistribuidor'] .= $line[$i];
			}
			if($i >= 517 && $i <= 522){ 
				$data['registrodistribuicao'] .= $line[$i];
			}
			if($i >= 523 && $i <= 532){ 
				$data['valorgravacaoeletronica'] .= $line[$i];
			}
			if($i >= 533 && $i <= 537){ 
				$data['numerooperacaobanco'] .= $line[$i];
			}
			if($i >= 538 && $i <= 552){ 
				$data['numerocontratobanco'].= $line[$i];
			}
			if($i >= 553 && $i <= 555){ 
				$data['numeroparcelacontrato'] .= $line[$i];
			}
			if($i == 556){ 
				$data['tipoletracambio'] .= $line[$i];
			}
			if($i >= 557 && $i <= 564){ 
				$data['complementocodigoirregularidade'] .= $line[$i];
			}
			if($i == 565){ 
				$data['protestomotivofalencia'] .= $line[$i];
			}
			if($i == 566){ 
				$data['instrumentoprotesto'] .= $line[$i];
			}
			if($i >= 567 && $i <= 576){ 
				$data['valordemaisdespesas'] .= $line[$i];
			}
			if($i >= 596 && $i <= 599){ 
				$data['numerosequencialregistro'] .= $line[$i];
			} 
		}
		
		
		$data['dataemissaotitulo'] = $this->_helper->Util->converteData($data['dataemissaotitulo'], '-');
		$data['datavencimentotitulo'] = $this->_helper->Util->converteData($data['datavencimentotitulo'], '-');
		
		$data['idSituacao'] = 20; //situacao em aberto // ou como está definido no layout, Tipo da Situação
		$data['dataocorrencia'] = date ( 'Y-m-d' ); //DATA DA IMPORTAÇÃO
		
		$data['codigoirregularidade'] = $this->getInconcistencias_titulo($data);
		
		if($data['codigoirregularidade'] == '00')
			$data['idProtocolo'] = $this->getUltimoProtocolo();// a data do protocolo ja esta embutida junto com este ID
		else{// se o arquivo tiver irregularidades não precisa gerar protocolo pra ele 
			$data['idProtocolo'] = 0;
			$data['tipoocorrencia'] = '5';
		}
			
		$data['idCabecalho'] = $idCabecalho;
		
		
		
		$data['valortitulo'] = $this->_helper->Util->decimais($data['valortitulo']);
		$data['saldotitulo'] = $this->_helper->Util->decimais($data['saldotitulo']);
		$data['valorcustascartorio'] = $this->_helper->Util->decimais($data['valorcustascartorio']);
		$data['valorcustascartoriodistribuidor'] = $this->_helper->Util->decimais($data['valorcustascartoriodistribuidor']);
		$data['valorgravacaoeletronica'] = $this->_helper->Util->decimais($data['valorgravacaoeletronica']);
		$data['valordemaisdespesas'] = $this->_helper->Util->decimais($data['valordemaisdespesas']);
		 	
		
		return $data;
		//$this->_pvar($data);
		
    }

    public function processaDesistencia($line)
    {
	    
    	$idregistro = $line[0];
    	
    	if($idregistro == 2){ // digito que verifica se é uma linha contendo dados do titulo
    		$protocolo = '';
    		$numTitulo = '';
	    	for($i=0; $i<strlen($line); $i++){//pega o numero do protocolo
	    		if($i >= 1 && $i <= 10){
	 				$protocolo .= $line[$i];
				}
				
				if($i >= 19 && $i <= 29){
	 				$numTitulo .= $line[$i];
				}
		    }    	
	    	
	    	$idTitulo = $this->model_titulo_importado->getTitulosByProtocolo((int)$protocolo, $numTitulo);//procura o titulo correspondente
	    	
	    	if(count($idTitulo) > 0){ //se encontrar, atualiza ele para a situação de sustado
	    		
	    		$idSituacaoAtual = $this->model_titulo_importado->getSituacaoTitulo($idTitulo->idTitulo);
	    		
	    		if($idSituacaoAtual != 8){	//Se o título já foi cancelado não pode susta-lo mais.    		
		    		$data['idTitulo'] = $idTitulo->idTitulo;
		    		$data['idSituacao'] = 4; // SUSTADO
	            	$this->model_titulo_importado->update($data, "idTitulo = " . $data['idTitulo']);
	            	
	            	//$data['data_historico'] = date ( 'Y-m-d h:i:s' );
					$data['descricao'] = "Título sustado via arquivo eletrônico.";
					   	    	
					return $this->model_historico->insert($data);
	    		}
	    	}
            		    	
    	}
    	
    	return true;
    	
    }

    public function processaCancelamento($line)
    {
	    
    	$idregistro = $line[0];
    	
    	if($idregistro == 2){ // digito que verifica se é uma linha contendo dados do titulo
    		$protocolo = '';
    		$numTitulo = '';
	    	for($i=0; $i<strlen($line); $i++){//pega o numero do protocolo
	    		if($i >= 1 && $i <= 10){
	 				$protocolo .= $line[$i];
				}
				
				if($i >= 19 && $i <= 29){
	 				$numTitulo .= $line[$i];
				}
		    }    	
	    	
	    	$idTitulo = $this->model_titulo_importado->getTitulosByProtocolo((int)$protocolo, $numTitulo);//procura o titulo correspondente
	    	
	    	if(count($idTitulo) > 0){ //se encontrar, atualiza ele para a situação de sustado
	    		$data['idTitulo'] = $idTitulo->idTitulo;
	    		$data['idSituacao'] = 3; // CANCELADO
            	$this->model_titulo_importado->update($data, "idTitulo = " . $data['idTitulo']);
            	
            	//$data['data_historico'] = date ( 'Y-m-d h:i:s' );
				$data['descricao'] = "Título cancelado via arquivo eletrônico.";
				   	    	
				return $this->model_historico->insert($data);
	    	}
            		    	
    	}
    	
    	return true;
    }

    public function verificarLinhas($fileContent)
    {
    	
		$controle = 2;
		$linhasFaltando = array();
		
    	for($i=1 ; $i < count($fileContent)-1 ; $i++){            			

			$line = $fileContent[$i];
    		$numeroSequencial = '';

    		for($j=0; $j<strlen($line); $j++){
				if($j >= 596 && $j <= 599){ 
					$numeroSequencial .= $line[$j];
				} 
    		}
    		
    		if((int)$numeroSequencial != $controle){
    			$linhasFaltando[] = $controle;
    			$controle = (int)$numeroSequencial;
    		}
    		
    		$controle++;
        }
        
        return $linhasFaltando;
    	
    }

    public function getInconcistencias_titulo($data)
    {
		
		//Codigo 01 - Se a data de apresentação do título for inferior ao vencimento.
		if(strtotime(date('Y-m-d')) < strtotime($data['datavencimentotitulo']) ){		
			//$this->_pvar('ERRO 01');
			 return '01';
		}		
		//02 Falta de comprovante da prestação de serviço. 
		//
		//
		//03 Nome do sacado incompleto/incorreto.
		if($data['nomecedente'] && $data['nomecedente'] == '' ){
			return '03';
		}  
		//04 Nome do cedente incompleto/incorreto.
    	if(strlen($data['nomecedente']) < 5 || $data['nomecedente'] == '' ){
			return '04';
		} 
		//05 Nome do sacador incompleto/incorreto.  
        if(strlen($data['nomesacador']) < 5 || $data['nomesacador'] == '' ){
			return '05';
		}
		//06 Endereço do sacado insuficiente.
    	if(strlen($data['nomedevedor']) < 5 || $data['nomedevedor'] == '' ){
			return '06';
		}
		
		//07 CNPJ/CPF do sacado inválido/incorreto
		$validator  = new Hazel_Validate_Or();
		$validator -> addValidator(new Hazel_Validate_Cpf())
          		   -> addValidator(new Hazel_Validate_Cnpj());
		
		if (!$validator->isValid($data['numeroidentificacaodevedor'])) {
		    return '07';
		}		
		//08 CNPJ/CPF incompatível c/ o nome do sacado/sacador/avalista
		//
		//
		//13 Título aceito – falta título (ag ced: enviar). 
		//14 CEP incorreto.
    	if (!preg_match('/^[1-9]{1}[0-9]{7}$/', $data['cepdevedor'])) {
			return '14';
		} 
		//15 Praça de pagamento incompatível com endereço.
		//
		//		
		//16 Falta número do título. 
    	if($data['numerotitulo'] == '00000000000' ){		
			 return '16';
		}		
		//18 Falta data de emissão do título.
   		if($data['dataemissaotitulo'] == '0000-00-00' ){		
			 return '18';
		} 
		//20 Data de emissão posterior ao vencimento.
    	if(strtotime($data['dataemissaotitulo']) > strtotime($data['datavencimentotitulo']) ){		
			 return '20';
		}
		//22 CEP do sacado incompatível com a praça de protesto.
    	$model_faixacep = new Abrangencia();
		$ceps = $model_faixacep->findCeps();
		$flag = false;
		for($i=0; $i < count($ceps); $i++){
			if(($ceps[$i]->inicio == $ceps[$i]->limite && $data['cepdevedor'] == $ceps[$i]->inicio) || ($data['cepdevedor'] >= $ceps[$i]->inicio && $data['cepdevedor'] <= $ceps[$i]->limite) ){
				$flag = true;
			}
		}
		if(!$flag){
			return '22';
		}		
		//23 Falta espécie do título.
		if($data['especietitulo'] == '   ' ){		
			 return '23';
		}
		//25 Tipo de endosso inválido.
		if($data['tipoendosso'] != 'M' && $data['tipoendosso'] != 'T' && $data['tipoendosso'] != ' ' ){		
			 return '25';
		}
		//28 Sacado e Sacador/Avalista são a mesma pessoa
    	if($data['documentodevedor'] == $data['documentosacador'] ){//28			
			return '28';
		}
    	//33 Falta data de vencimento no título
		if($data['datavencimentotitulo'] == '0000-00-00'){//30			
			return '33';
		}
		//30 Aguardar um dia útil após o vencimento para protestar
		if($data['datavencimentotitulo'] == date('Y-m-d') ){//30			
			return '30';
		}  
		//38 Endereço do sacado igual ao do sacador ou do portador (FALTA VER PORTADOR)
		//
		//
		if($data['enderecodevedor'] == $data['enderecosacador'] ){
			return '38';
		}		
		//39 Endereço do apresentante incompleto ou não informado
		//40 Rua / Número inexistente no endereço
		//52 Título apresentado em duplicidade
		//53 Título já protestado
		//54 Letra de Câmbio vencida – falta aceite do sacado
		//58 Ausência do Documento Físico
		//61 Título de outra jurisdição territorial
		//65 Dados do sacador em branco ou inválido
		
		return '00';
    }

    public function getInconcistencias_digitalizacao($data)
    {
		$msg = '';
		
		$model_pessoa = new Pessoa();
		$devedor = $model_pessoa->findByDocumento($data['documento_devedor']);
		if(count($devedor) == 1 && $devedor->nome != $data['nome_devedor']){//8			
			$msg = "Verifique o nome do devedor.";
			return $msg;
		}
		
		$data['dataemissaotitulo'] = implode("-", array_reverse(explode("/", $this->_request->getPost('dataemissaotitulo'))));
		$data['datavencimentotitulo'] = implode("-", array_reverse(explode("/", $this->_request->getPost('datavencimentotitulo'))));		            
    	if(strtotime($data['dataemissaotitulo']) > strtotime($data['datavencimentotitulo']) ){//20		
			$msg = "A data de emissão do título não deve ser posterior ao seu vencimento.";
			return $msg;
		}
		
    	$model_faixacep = new Abrangencia();
		$ceps = $model_faixacep->findCeps();
		$flag = false;
		$cep_devedor = (int) preg_replace('/[^0-9]/', '', $data['cep_devedor']);
		
		for($i=0; $i < count($ceps); $i++){//para o 22
			if(($ceps[$i]->inicio == $ceps[$i]->limite && $cep_devedor == $ceps[$i]->inicio) || ($cep_devedor >= $ceps[$i]->inicio && $cep_devedor <= $ceps[$i]->limite) ){
				$flag = true;
			}
		}
		if(!$flag){//22
			$msg = "O CEP não pertence a praça de protesto deste cartório.";
			return $msg;
		}
		
    	if($data['documento_devedor'] == $data['documento_sacador'] ){//28			
			$msg = "O devedor e o sacador não podem ser as mesmas pessoas.";
			return $msg;
		}
		
    	if($data['datavencimentotitulo'] == date('Y-m-d') ){//30			
			$msg = "Aguarde 1(um) dia útil para protestar este título.";
			return $msg;
		}

		return $msg;
    }
    
	public function gerarConfirmacao($idCabecalho)
    {
		
		$cabecalho = $this->model_Cabecalho->getCabecalho($idCabecalho);		
		$titulos = $this->model_titulo_importado->getTitulo($idCabecalho);
		$rodape = $this->model_Rodape->getRodape($idCabecalho);
		
		$complemento = '';
		$header = $cabecalho->idregistro;		
		$header .= $this->_helper->Util->completa(3, $cabecalho->numerocodigoportador, "0");		
		$header .= $this->_helper->Util->completa(40, $cabecalho->nomeportador, " ");		
		$header .= $this->_helper->Util->completa(8, $this->_helper->Util->converteDataArquivo($cabecalho->datamovimento), "0");		
		$header .= 'SDT';		
		$header .= 'BFO';		
		$header .= 'CRT';		
		$header .= $this->_helper->Util->completa(6, $cabecalho->numerosequencialremessa, "0");		
		$header .= $this->_helper->Util->completa(4, $cabecalho->quantidaderegistrosremessa, "0");		
		$header .= $this->_helper->Util->completa(4, $cabecalho->quantidadetitulosremessa, "0");
		$header .= $this->_helper->Util->completa(4, $cabecalho->quantidadeindicacoesremessa, "0");		
		$header .= $this->_helper->Util->completa(4, $cabecalho->quantidadeoriginaisremessa, "0");		
		$header .= $this->_helper->Util->completa(6, $cabecalho->idagenciacentralizadora, "0");		
		$header .= $this->_helper->Util->completa(3, $cabecalho->versaolayout, "0");		
		$header .= $this->_helper->Util->completa(7, $cabecalho->codigomunicipiopracapagamento, "0");		
		$header .= $this->_helper->Util->completa(497, $complemento, " ");		
		$header .= $this->_helper->Util->completa(4, $cabecalho->numerosequencialarquivo, "0") . "\n";
		
		
		$trs = '';		
		foreach ($titulos as $titulo){
			$complemento = '';
			$tr = '';
			
			$tr .= $this->_helper->Util->completa(1, $titulo->idregistro, "0") 					. $this->_helper->Util->completa(3, $titulo->numerocodigoportador, "0");	
			$tr .= $this->_helper->Util->completa(15, $titulo->codigocedente_agencia, "0") 		. $this->_helper->Util->completa(45, $titulo->nomecedente, "0");
			$tr .= $this->_helper->Util->completa(45, $titulo->nomesacador, " ") 				. $this->_helper->Util->completa(14, $titulo->documentosacador, "0");
			$tr .= $this->_helper->Util->completa(45, $titulo->enderecosacador, "0") 			. $this->_helper->Util->completa(8, $titulo->cepsacador, "0");
			$tr .= $this->_helper->Util->completa(20, $titulo->cidadesacador, " ") 				. $this->_helper->Util->completa(2, $titulo->ufsacador, " ");
			$tr .= $this->_helper->Util->completa(15, $titulo->nossonumero, "0") 				. $this->_helper->Util->completa(3, $titulo->especietitulo, " ");
			$tr .= $this->_helper->Util->completa(11, $titulo->numerotitulo, "0") 				. $this->_helper->Util->completa(8, $this->_helper->Util->converteDataArquivo($titulo->dataemissaotitulo), "0");
			$tr .= $this->_helper->Util->completa(8, $this->_helper->Util->converteDataArquivo($titulo->datavencimentotitulo), "0") . $this->_helper->Util->completa(3, $titulo->tipomoeda, "0");
			$tr .= $this->_helper->Util->completa(14, preg_replace('/[^0-9]/', '', $titulo->valortitulo), "0") 				. $this->_helper->Util->completa(14, preg_replace('/[^0-9]/', '', $titulo->saldotitulo), "0");
			$tr .= $this->_helper->Util->completa(20, $titulo->pracaprotesto, "0") 				. $this->_helper->Util->completa(1, $titulo->tipoendosso, " ");
			$tr .= $this->_helper->Util->completa(1, $titulo->informacaosobreaceite, " ") 		. $this->_helper->Util->completa(1, $titulo->numerocontroledevedor, "0");
			$tr .= $this->_helper->Util->completa(45, $titulo->nomedevedor, " ") 				. $this->_helper->Util->completa(3, $titulo->tipoidentificacaodevedor, "0");
			$tr .= $this->_helper->Util->completa(14, $titulo->numeroidentificacaodevedor, "0") . $this->_helper->Util->completa(11, $titulo->documentodevedor, "0");
			$tr .= $this->_helper->Util->completa(45, $titulo->enderecodevedor, " ") 			. $this->_helper->Util->completa(8, $titulo->cepdevedor, "0");
			$tr .= $this->_helper->Util->completa(20, $titulo->cidadedevedor, " ") 				. $this->_helper->Util->completa(2, $titulo->ufdevedor, " ");
			$tr .= $this->_helper->Util->completa(2, $titulo->codigocartorio, "0") 				. $this->_helper->Util->completa(10, $titulo->protocolo, "0");
			$tr .= $this->_helper->Util->completa(1, $titulo->tipoocorrencia, "0") 				. $this->_helper->Util->completa(8, $this->_helper->Util->converteDataArquivo($titulo->data_protocolo), "0");
			$tr .= $this->_helper->Util->completa(10, preg_replace('/[^0-9]/', '', $titulo->valorcustascartorio), "0") 		. $this->_helper->Util->completa(1, $titulo->declaracaoportador, "0");
			$tr .= $this->_helper->Util->completa(8, $this->_helper->Util->converteDataArquivo($titulo->dataocorrencia), "0") . $this->_helper->Util->completa(2, $titulo->codigoirregularidade, "0");
			$tr .= $this->_helper->Util->completa(20, $titulo->bairrodevedor, " ") 				. $this->_helper->Util->completa(10, preg_replace('/[^0-9]/', '', $titulo->valorcustascartoriodistribuidor), "0");
			$tr .= $this->_helper->Util->completa(6, $titulo->registrodistribuicao, "0") 		. $this->_helper->Util->completa(10, preg_replace('/[^0-9]/', '', $titulo->valorgravacaoeletronica), "0");
			$tr .= $this->_helper->Util->completa(5, $titulo->numerooperacaobanco, "0")		    . $this->_helper->Util->completa(15, $titulo->numerocontratobanco, "0");
			$tr .= $this->_helper->Util->completa(3, $titulo->numeroparcelacontrato, "0")		. $this->_helper->Util->completa(1, $titulo->tipoletracambio, "0");
			$tr .= $this->_helper->Util->completa(8, $titulo->complementocodigoirregularidade, "0") . $this->_helper->Util->completa(1, $titulo->protestomotivofalencia, " ");
			$tr .= $this->_helper->Util->completa(1, $titulo->instrumentoprotesto, " ") 		. $this->_helper->Util->completa(10, preg_replace('/[^0-9]/', '', $titulo->valordemaisdespesas), "0");
			$tr .= $this->_helper->Util->completa(19, $complemento, " ") 						. $this->_helper->Util->completa(4, $titulo->numerosequencialregistro, "0") . "\n";
			$trs .= $tr;
		}
		
		
		$complemento = '';
		$trailler = $rodape->idregistro;		
		$trailler .= $this->_helper->Util->completa(3, $cabecalho->numerocodigoportador, "0");		
		$trailler .= $this->_helper->Util->completa(40, $rodape->nomeportador, " ");		
		$trailler .= $this->_helper->Util->completa(8, $this->_helper->Util->converteDataArquivo($rodape->datamovimento), "0");
		$trailler .= $this->_helper->Util->completa(5, $rodape->somatorioseguranca_quantidade, "0");
		$trailler .= $this->_helper->Util->completa(18, preg_replace('/[^0-9]/', '', $rodape->somatorioseguranca_valor), "0");
		$trailler .= $this->_helper->Util->completa(521, $complemento, " ");
		$trailler .= $this->_helper->Util->completa(4, $rodape->numerosequencialarquivo, "0") . "\n";		
		
		//$this->_helper->Util->_pvar($trailler);exit;
		
		$nomearquivo = 'C'.substr($titulos[0]->arquivo, 1, 11);
		
		$user = new Zend_Session_Namespace('user_data');
        $data['idUsuario'] = $user->user->idUsuario;
        $data['arquivo'] = $nomearquivo;
        //$data['data_envio'] = date ( 'Y-m-d h:i:s' );
        $data['tipo'] = 4; //tipo 4 = Confirmação
            
        $arquivo = new Arquivo();
        $arquivo->insert($data);
        $lastId = $arquivo->getAdapter()->lastInsertId();
		
		$path = APPLICATION_PATH . '/arquivos/confirmacao';		
		if(!file_exists($path))mkdir($path);		
		$path .= "/" . $lastId;
		
		
		file_put_contents($path, $header . $trs . $trailler, FILE_APPEND);
		
		ZendX_JQuery_FlashMessenger::addMessage("Confirmação Gerada com sucesso.", 'info');
		//header('Content-type: octet/stream');
	    //header('Content-disposition: attachment; filename="'.$nomearquivo.'";');
	    //header('Content-Length: '.filesize($path));
	    //readfile($path);
	    //exit;
		
    }

    public function gerarRetorno($idPortador)
    {
    	
    	$titulos = $this->model_titulo_importado->getTituloRetorno($idPortador); 

    	if(count($titulos) > 0){
	    	$portador = $this->model_portador->findForSelect($idPortador);
	    	$complemento = '';
			$header = '0';		
			$header .= $this->_helper->Util->completa(3, $portador->numerocodigoportador, "0");		
			$header .= $this->_helper->Util->completa(40, $portador->nomeportador, " ");		
			$header .= $this->_helper->Util->completa(8, date('dmY'), "0");		
			$header .= 'SDT';
			$header .= 'BFO';
			$header .= 'RTP';
			$header .= $this->_helper->Util->completa(6, '', "0");//numerosequencialremessa	do retorno	
			$header .= $this->_helper->Util->completa(4, count($titulos), "0");		
			$header .= $this->_helper->Util->completa(4, '', "0");
			$header .= $this->_helper->Util->completa(4, '', "0");		
			$header .= $this->_helper->Util->completa(4, '', "0");		
			$header .= $this->_helper->Util->completa(6, $portador->idagenciacentralizadora, "0");		
			$header .= $this->_helper->Util->completa(3, '043', "0");		
			$header .= $this->_helper->Util->completa(7, '1721000', "0");		
			$header .= $this->_helper->Util->completa(497, $complemento, " ");		
			$header .= $this->_helper->Util->completa(4, '0001', "0") . "\n";
	    	
			//$this->_helper->util->_pvar($header);exit;
	    	
	    	$trs = '';
	    	$somatorioseguranca = 0;
	    	$i = 2;		
			foreach ($titulos as $titulo){
				$codigoirregularidade = 0;
				$tipoocorrencia = 0;
				$complemento = '';
				$tr = '';
				
				$tr .= $this->_helper->Util->completa(1, $titulo->idregistro, "0") 										. $this->_helper->Util->completa(3, $titulo->numerocodigoportador, "0");	
				$tr .= $this->_helper->Util->completa(15, $titulo->codigocedente_agencia, "0") 							. $this->_helper->Util->completa(45, '', " ");
				$tr .= $this->_helper->Util->completa(45, '', " ") 														. $this->_helper->Util->completa(14, '', " ");
				$tr .= $this->_helper->Util->completa(45, '', " ") 														. $this->_helper->Util->completa(8, '', " ");
				$tr .= $this->_helper->Util->completa(20, '', " ") 														. $this->_helper->Util->completa(2, '', " ");
				$tr .= $this->_helper->Util->completa(15, trim($titulo->nossonumero), "0") 								. $this->_helper->Util->completa(3, '', " ");
				$tr .= $this->_helper->Util->completa(11,'', " ") 														. $this->_helper->Util->completa(8, '', " ");
				$tr .= $this->_helper->Util->completa(8, '', " ") 														. $this->_helper->Util->completa(3, '001', "0");
				$tr .= $this->_helper->Util->completa(14, preg_replace('/[^0-9]/', '', $titulo->valortitulo), "0") 		. $this->_helper->Util->completa(14, preg_replace('/[^0-9]/', '',$titulo->saldotitulo), "0");
				$tr .= $this->_helper->Util->completa(20, '', " ") 														. $this->_helper->Util->completa(1, '', " ");
				$tr .= $this->_helper->Util->completa(1, '', " ") 														. $this->_helper->Util->completa(1, '', " ");
				$tr .= $this->_helper->Util->completa(45, '', " ") 														. $this->_helper->Util->completa(3, '', " ");
				$tr .= $this->_helper->Util->completa(14, '', " ") 														. $this->_helper->Util->completa(11, '', " ");
				$tr .= $this->_helper->Util->completa(45, '', " ")														. $this->_helper->Util->completa(8, '', " ");
				$tr .= $this->_helper->Util->completa(20, '', " ") 														. $this->_helper->Util->completa(2, '', " ");
				$tr .= $this->_helper->Util->completa(2, $titulo->codigocartorio, "0") 									. $this->_helper->Util->completa(10, $titulo->protocolo, "0");
				$tr .= $this->_helper->Util->completa(1, $titulo->tipoocorrencia, "0") 									. $this->_helper->Util->completa(8, $this->_helper->Util->converteDataArquivo($titulo->data_protocolo), "0");
				$tr .= $this->_helper->Util->completa(10, preg_replace('/[^0-9]/', '',$titulo->valorcustascartorio), "0") . $this->_helper->Util->completa(1, $titulo->declaracaoportador, "0");
				$tr .= $this->_helper->Util->completa(8, $this->_helper->Util->converteDataArquivo($titulo->dataocorrencia), "0") . $this->_helper->Util->completa(2, $codigoirregularidade, "0");
				$tr .= $this->_helper->Util->completa(20, '', " ") 														. $this->_helper->Util->completa(10, preg_replace('/[^0-9]/', '', $titulo->valorcustascartoriodistribuidor), "0");
				$tr .= $this->_helper->Util->completa(6, $titulo->registrodistribuicao, "0") 							. $this->_helper->Util->completa(10, preg_replace('/[^0-9]/', '', $titulo->valorgravacaoeletronica), "0");
				$tr .= $this->_helper->Util->completa(5, '', " ") 														. $this->_helper->Util->completa(15, '', " ");
				$tr .= $this->_helper->Util->completa(3, '', " ") 														. $this->_helper->Util->completa(1, '', " ");
				$tr .= $this->_helper->Util->completa(8, $titulo->complementocodigoirregularidade, "0") 				. $this->_helper->Util->completa(1, '', " ");
				$tr .= $this->_helper->Util->completa(1, '', " ") 														. $this->_helper->Util->completa(10, preg_replace('/[^0-9]/', '', $titulo->valordemaisdespesas), "0");
				$tr .= $this->_helper->Util->completa(19, $complemento, " ") 											. $this->_helper->Util->completa(4, $i, "0") . "\n";
				$trs .= $tr;
				
				$somatorioseguranca += $titulo->valortitulo;
				$i++;
			}
			
			$complemento = '';
			$trailler = '9';		
			$trailler .= $this->_helper->Util->completa(3, $portador->numerocodigoportador, "0");		
			$trailler .= $this->_helper->Util->completa(40, $portador->nomeportador, " ");		
			$trailler .= $this->_helper->Util->completa(8, date('dmY'), "0");
			$trailler .= $this->_helper->Util->completa(5, count($titulos), "0");
			$trailler .= $this->_helper->Util->completa(18, preg_replace('/[^0-9]/', '', $somatorioseguranca), "0");
			$trailler .= $this->_helper->Util->completa(521, $complemento, " ");
			$trailler .= $this->_helper->Util->completa(4, $i, "0") . "\n";
			
			
			$nomearquivo = 'R'. $portador->numerocodigoportador . date('d') . date('m') . "." . date('y') . "1";
					
			$user = new Zend_Session_Namespace('user_data');
	        $data['idUsuario'] = $user->user->idUsuario;
	        $data['arquivo'] = $nomearquivo;
	        //$data['data_envio'] = date ( 'Y-m-d h:i:s' );
	        $data['tipo'] = 5; //tipo 4 = Confirmação
	            
	        $arquivo = new Arquivo();
	        $arquivo->insert($data);
	        $lastId = $arquivo->getAdapter()->lastInsertId();
			
			$path = APPLICATION_PATH . '/arquivos/retorno';		
			if(!file_exists($path))mkdir($path);		
			$path .= "/" . $lastId;
			
			
			file_put_contents($path, $header . $trs . $trailler, FILE_APPEND);
		
			return true;
		}
		
		return false;
					
    }

}


