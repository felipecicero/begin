<?php

class ProtestosController extends Zend_Controller_Action
{

    private $model_protesto = null;
    private $model_titulo_importado = null;
    private $model_titulo = null;
    private $model_serasa = null;
    private $model_livro = null;
    private $model_cartorio = null;

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
	    
        parent::init();      
        $this->model_protesto = new Protesto();
        $this->model_titulo_importado = new TituloImportado();
        $this->model_titulo = new Titulo();
        $this->model_serasa = new Serasa();
        $this->model_livro = new Livro();
        $this->model_cartorio = new Cartorio();
        $this->view->setEncoding('ISO-8859-1');
    }

    public function indexAction()
    {
        
    }

    public function protestosAction()
    {
        $select = $this->model_protesto->selectTitulos(22);
        
    	$data = $this->model_protesto->fetchAll($select);
    	
        $this->view->titulos = $data;
    }

    public function protestartituloAction()
    {
        $form = new Protesto_Form_Protestartitulo();

         
         if ( $this->_request->isPost()){
					    		
         	if($this->gerarProtesto($this->_request->getPost('idProtesto'))){
         	       
		        $data_historico['idTitulo'] = $this->_request->getPost('idTitulo'); 
		        $data_historico['idResponsavel'] = $this->_request->getPost('idResponsavel');
	         	$data_historico['idSituacao'] = 2; // Título protestado.
	         	  
		        if($this->_request->getPost('tipo') == 7)     		
			        $this->model_titulo->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);
			    else
			    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);
		        
			    $data_historico['idProtesto'] = $this->_request->getPost('idProtesto');  
				//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
				$data_historico['descricao'] = "Título protestado.";
		        unset($data_historico['idResponsavel']);
				$model_historico = new Historico();    	    	
				$model_historico->insert($data_historico);
							
				$data_serasa['idProtesto'] = $this->_request->getPost('idProtesto');
				$data_serasa['data_serasa'] = date ( 'Y-m-d' );				
				$data_serasa['codigooperacao'] = 'i';
				$this->model_serasa->insert($data_serasa);
				
				
				$data    = $this->model_protesto->selectDevedor($this->_request->getPost('idProtesto'));
				$model_cartorio = new Cartorio();    	
	    		$data_cartorio = $model_cartorio->getCartorio();
				
	    		$model_custas = new Custa();    	
	    		$data_custas = $model_custas->getCustas($this->_request->getPost('idProtesto'), $data->valortitulo);

	    		
	    		$model_autoridade = new Autoridade();    	
	    		$data_autoridade = $model_autoridade->findForSelect($this->_request->getPost('idResponsavel'));
	    		//print_r($data_custas);exit;
	    		$instrumento = new Begin_Instrumento();			
				$instrumento->gerarInstrumento($data, $data_cartorio, $data_custas, $data_autoridade->Current());
				
				ZendX_JQuery_FlashMessenger::addMessage('Título protestado.');			
				$this->_redirect('/protesto/protestos');
			
         	}
         	else{         		
         		ZendX_JQuery_FlashMessenger::addMessage('O Instrumento de Protesto já foi gerado. Verifique no livro de registros.', 'error');
         		return false;
         	}

         }
         
    	$id      = (int) $this->_getParam('idProtesto');         
        $data    = $this->model_protesto->selectDevedor($id);         		
         
        if ( null === $data ){
           ZendX_JQuery_FlashMessenger::addMessage('Título não encontrado.', 'notice');
           return false;
        }
        $data->valortitulo = $this->_helper->Util->valor($data->valortitulo);
        $form->setAsEditForm($data);
        
		$this->view->form = $form;
    }

    public function protestartitulogrupoAction()
    {
    	set_time_limit(0);
    	
        $__data    = $this->model_protesto->selectNotificador(null, 22);
        
        if(count($__data) > 0){
        
	        foreach ($__data as $data){
	        	$this->gerarProtesto($data->idProtesto);
	        }
	        
	        $__data = array();
	        $__data    = $this->model_protesto->selectNotificador(null, 22);
        
        	//$data_historico['idResponsavel'] = $this->_request->getPost('idResponsavel');                
        	$model_historico = new Historico();
        	
	        foreach ($__data as $data){
	        	$data_historico = array();
	        	 
		        $data_historico['idTitulo'] = $data->idTitulo;
		        $data_historico['idResponsavel'] = 1; 	        
		        $data_historico['idSituacao'] = 2; // Título protestado.
		           
		        if($this->_request->getPost('tipo') == 7)     		
			        $this->model_titulo->update($data_historico, "idTitulo = " . $data->idTitulo);
			    else
			    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data->idTitulo);
		        
			    $data_historico['idProtesto'] = $data->idProtesto;  
				//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
				$data_historico['descricao'] = "Título protestado.";
		        unset($data_historico['idResponsavel']);
				    	    	
				$model_historico->insert($data_historico);
							
				$data_serasa['idProtesto'] = $data->idProtesto;
				$data_serasa['data_serasa'] = date ( 'Y-m-d' );				
				$data_serasa['codigooperacao'] = 'i';
				$this->model_serasa->insert($data_serasa);
	        }
        
	        $model_cartorio = new Cartorio();    	
	    	$data_cartorio = $model_cartorio->getCartorio();
				    	
	    	$model_autoridade = new Autoridade();    	
	    	$data_autoridade = $model_autoridade->findForSelect(1);
	
	    	$instrumento = new Begin_Instrumento();			
			$instrumento->gerarInstrumentos($__data, $data_cartorio, $data_autoridade->Current());
			
        }

        ZendX_JQuery_FlashMessenger::addMessage('Não há títulos há se protestar.', 'info');			
		$this->_redirect('/protestos/protestos');
         	       
    }

    public function listarserasaAction()
    {
        $select = $this->model_serasa->selectTitulos();
        
    	//$data = $this->model_serasa->fetchAll($select);
    	
        $this->view->serasas = $select;
    }

    public function gerararquivoAction()
    {
        $date_e      = $this->_getParam('data');
        
    	$this->view->dataserasa = $date_e;
    	
        $this->view->titulo = $this->model_serasa->selectTitulosSerasa($date_e);
        
        //GERAR O ARQUIVO
    }

    public function excluirtituloarquivoAction()
    {
        // verificamos se realmente foi informado algum ID
        if ( $this->_hasParam('idProtesto') == false )
        {
            $this->_redirect('protestos/gerararquivo');
        }
 
        $id = (int) $this->_getParam('idProtesto');
        $where = $this->model_serasa->getAdapter()->quoteInto('idProtesto = ?', $id);
        $this->model_serasa->delete($where);
        ZendX_JQuery_FlashMessenger::addMessage('Título deletado do arquivo para o serasa.');        
        $this->_redirect('protestos/gerararquivo/data/' . $this->_getParam('data'));
    }

    public function gerarProtesto($idProtesto)
    {
    	
    	$select = $this->model_protesto->select()
	      			     						->setIntegrityCheck(false)
	      			     						->where('idProtesto = ?', $idProtesto); 
		$protesto = $this->model_protesto->fetchAll($select);
    	
		if($protesto->Current()->idLivro == 0 || $protesto->Current()->idLivro == null){
    	
	    	$pagina = $this->model_livro->getLivros();
	    	    	
	    	if(count($pagina) == 0){
	    		$data['folha'] = 1;
	    		$data['livro'] = 1;    		
	    		$this->model_livro->insert($data);
	    	}
	    	
	    	$pagina = $this->model_livro->getLivros();    	
	    	$id = $pagina[count($pagina)-1]->idLivro;
	    	
	    	$data['folha'] = $pagina[count($pagina)-1]->folha + 1;    	
	    	$data['livro'] = $pagina[count($pagina)-1]->livro;
	    	
	    	if($data['folha'] == 201){
	    	   $data['folha'] = 1;
	    	   $data['livro'] = $pagina[count($pagina)-1]->livro + 1;
	    	}
	    	
	    	$this->model_livro->insert($data);
	    	
	    	//atualiza o protesto com o id do livro
	    	$_data['idLivro'] = $id;
	    	$this->model_protesto->update($_data, "idProtesto = " . $idProtesto);
	    	
	    	return true;
    	
    	}
    	
    	return false;
    	
    }

    public function downloadserasaAction()
    {
    	$id      = $this->_getParam('idDownload');
    	
    	if($id == 1){
    		$this->arquivoInclusao();
    	}
    	if($id == 2){
    		$this->arquivoCancelamento();
    	}
    	
    }

    public function arquivoInclusao()
    {
    	
		$date     = $this->_getParam('data');
		
		$titulos = $this->model_protesto->selectTitulosSerasa($date,'i');//pegar os titulos todos eles
		$cartorio = $this->model_cartorio->getCartorio();
		
		$complemento = '';
		$header = '0';		
		$header .= $this->_helper->Util->completa(43, $complemento, " ");		
		$header .= $this->_helper->Util->completa(8, date('dmY'), " ");		
		$header .= $this->_helper->Util->completa(9, $complemento, " ");		
		$header .= $this->_helper->Util->completa(6, '9', "9");		
		$header .= $this->_helper->Util->completa(22, $complemento, " ");			
		$header .= '1';//tipo de documento		
		$header .= $this->_helper->Util->completa(9, '026750752', "0");		
		$header .= $this->_helper->Util->completa(25, 'SERASA-CONCENTRE-PROTESTO', " ");		
		$header .= 'E';
		$header .= $this->_helper->Util->completa(4, '63', "0");		
		$header .= $this->_helper->Util->completa(8, '32159900', "0");		
		$header .= $this->_helper->Util->completa(4, '0', "0");		
		$header .= $this->_helper->Util->completa(70, 'ADRIANA E RONI', " ");		
		$header .= $this->_helper->Util->completa(4, '5.0', " ");		
		$header .= $this->_helper->Util->completa(5, $complemento, " ");//codigo edi-7
		$header .= 'D';
		$header .= $this->_helper->Util->completa(40, 'protesto@cartoriomoromizato.com.br', " ");
		$header .= $this->_helper->Util->completa(272, $complemento, " ");
		$header .= $this->_helper->Util->completa(60, $complemento, " ");		
		$header .= '0000001' . "\n";
		
		
		$trs = '';
		$i = 2;	
			
		foreach ($titulos as $titulo){
			
			$tr = '';
			
			$tr .= '1';	
			$tr .= 'P';
			$tr .= 'I';
			$tr .= 'TO';
			$tr .= $this->_helper->Util->completa(4, 'PMJ', " ");			
			$tr .= $this->_helper->Util->completa(55, $complemento, " ");
			
			$tr .= $this->_helper->Util->completa(45, $titulo->nomesacador, " ");
			$tr .= $this->_helper->Util->completa(9, $complemento, " ");
			$tr .= $this->_helper->Util->completa(4, $complemento, " ");
			$tr .= $this->_helper->Util->completa(2, $complemento, " ");
			$tr .= $this->_helper->Util->completa(44, $complemento, " ");
			$tr .= $this->_helper->Util->completa(8, $complemento, " ");
			$tr .= $this->_helper->Util->completa(20, $complemento, " ");
			$tr .= $this->_helper->Util->completa(2, $complemento, " ");
			$tr .= $this->_helper->Util->completa(25, $complemento, " ");
			$tr .= $this->_helper->Util->completa(1, $complemento, " ");
			$tr .= $this->_helper->Util->completa(1, $complemento, " ");
			$tr .= $this->_helper->Util->completa(7, $complemento, " ");
			$tr .= $this->_helper->Util->completa(6, $titulo->livro, " ");//
			$tr .= $this->_helper->Util->completa(1, $complemento, " ");
			$tr .= $this->_helper->Util->completa(4, $titulo->folha, " ");//
			$tr .= $this->_helper->Util->completa(3, $titulo->codigo, " ");//
			
			$tr .= $this->_helper->Util->completa(14, $titulo->valortitulo, " ");
			$tr .= $this->_helper->Util->completa(8, $this->_helper->Util->converteDataArquivo($titulo->dataemissao), "0");
			
			if((int)$titulo->tipoidentificacao == 1){
				$pessoa = 'J';
				$tipodocumento = 1;
				$numeroidentificacao = substr($titulo->numeroidentificacao, 0, 8);
				$filial = substr($titulo->numeroidentificacao, 8, 4);
				$digitocontrole = substr($titulo->numeroidentificacao, 12, 2);
			}
			else if((int)$titulo->tipoidentificacao == 2){
				$pessoa = 'F';
				$tipodocumento = 2;
				$numeroidentificacao = substr($titulo->numeroidentificacao, 0, 9);
				$filial = '';				
				$digitocontrole = substr($titulo->numeroidentificacao, 10, 2);
			}
			else{
				$pessoa = '';
				$tipodocumento = '';
				$numeroidentificacao = '';
				$filial = '';				
				$digitocontrole = '';
			}
			
			$tr .= $this->_helper->Util->completa(1, $pessoa, " ");
			$tr .= $this->_helper->Util->completa(1, $tipodocumento, " ");
			$tr .= $this->_helper->Util->completa(2, $complemento, " ");//////
			$tr .= $this->_helper->Util->completa(25, $complemento, " ");
			$tr .= $this->_helper->Util->completa(45, $titulo->nome, " ");
			$tr .= $this->_helper->Util->completa(3, $complemento, " ");
			$tr .= $this->_helper->Util->completa(9, $numeroidentificacao, "0");
			$tr .= $this->_helper->Util->completa(4, $filial, "0");
			$tr .= $this->_helper->Util->completa(2, $digitocontrole, "0");
			$tr .= $this->_helper->Util->completa(10, $complemento, " ");
			$tr .= $this->_helper->Util->completa(45, $titulo->endereco, " ");
			$tr .= $this->_helper->Util->completa(8, $titulo->cep, "0");
			$tr .= $this->_helper->Util->completa(20, $titulo->cidade, " ");
			$tr .= $this->_helper->Util->completa(2, $titulo->estado, " ");
			$tr .= $this->_helper->Util->completa(2, $cartorio->codigo, "0");
			
			$tr .= $this->_helper->Util->completa(10, $titulo->protocolo, "0");
			$tr .= $this->_helper->Util->completa(8, $cartorio->cep, "0");
			$tr .= $this->_helper->Util->completa(12, $complemento, " ");
			$tr .= $this->_helper->Util->completa(8, $complemento, " ");/////  $titulo->data_cancelamento Data do cancelamento do protesto, caso seja cancelamento
			$tr .= $this->_helper->Util->completa(1, '1', " ");
			$tr .= $this->_helper->Util->completa(8, $complemento, " ");
			$tr .= $this->_helper->Util->completa(8, $complemento, " ");
			$tr .= $this->_helper->Util->completa(31, $complemento, " ");
			$tr .= $this->_helper->Util->completa(60, $complemento, " ");
			$tr .= $this->_helper->Util->completa(7, $i, "0") . "\n";
			
			$trs .= $tr;
			
			$i++;
		}
		
		$trailler = '9';
		$trailler .= $this->_helper->Util->completa(592, $complemento, " ");				
		$trailler .= $this->_helper->Util->completa(7, $i, "0") . "\n";		
				
		$nomearquivo = 'P'.$cartorio->codigo.date('dm').".PMJ";
		
		$user = new Zend_Session_Namespace('user_data');
        $data['idUsuario'] = $user->user->idUsuario;
        $data['arquivo'] = $nomearquivo;
        //$data['data_envio'] = date ( 'Y-m-d h:i:s' );
        $data['tipo'] = 9; //tipo 9 = Serasa - inclusao
            
        $arquivo = new Arquivo();
        $arquivo->insert($data);
        $lastId = $arquivo->getAdapter()->lastInsertId();
		
		$path = APPLICATION_PATH . '/arquivos/serasa';		
		if(!file_exists($path))mkdir($path);
		
		$path .= '/inclusao';		
		if(!file_exists($path))mkdir($path);		
		$path .= "/" . $lastId;
		
		
		file_put_contents($path, $header . $trs . $trailler, FILE_APPEND);
		
		header('Content-type: octet/stream');
	    header('Content-disposition: attachment; filename="'.$nomearquivo.'";');
	    header('Content-Length: '.filesize($path));
	    readfile($path);
	    exit;
    }

    public function arquivoCancelamento()
    {
		
		$date     = $this->_getParam('data');
		
    	$titulos = $this->model_protesto->selectTitulosSerasa($date, 'c');//pegar os titulos todos eles
		$cartorio = $this->model_cartorio->getCartorio();
		
		$complemento = '';
		$header = '0';		
		$header .= $this->_helper->Util->completa(43, $complemento, " ");		
		$header .= $this->_helper->Util->completa(8, date('dmY'), " ");		
		$header .= $this->_helper->Util->completa(9, $complemento, " ");		
		$header .= $this->_helper->Util->completa(6, '9', "9");		
		$header .= $this->_helper->Util->completa(22, $complemento, " ");			
		$header .= '1';//tipo de documento		
		$header .= $this->_helper->Util->completa(9, '026750752', "0");		
		$header .= $this->_helper->Util->completa(25, 'SERASA-CONCENTRE-PROTESTO', " ");		
		$header .= 'E';
		$header .= $this->_helper->Util->completa(4, '63', "0");		
		$header .= $this->_helper->Util->completa(8, '32159900', "0");		
		$header .= $this->_helper->Util->completa(4, '0', "0");		
		$header .= $this->_helper->Util->completa(70, 'ADRIANA E RONI', " ");		
		$header .= $this->_helper->Util->completa(4, '5.0', " ");		
		$header .= $this->_helper->Util->completa(5, $complemento, " ");//codigo edi-7
		$header .= 'D';
		$header .= $this->_helper->Util->completa(40, 'protesto@cartoriomoromizato.com.br', " ");
		$header .= $this->_helper->Util->completa(272, $complemento, " ");
		$header .= $this->_helper->Util->completa(60, $complemento, " ");		
		$header .= '0000001' . "\n";
		
		
		$trs = '';
		$i = 2;	
			
		foreach ($titulos as $titulo){
			
			$tr = '';
			
			$tr .= '1';	
			$tr .= 'C';
			$tr .= 'E';
			$tr .= 'TO';
			$tr .= $this->_helper->Util->completa(4, 'PMJ', " ");			
			$tr .= $this->_helper->Util->completa(55, $complemento, " ");
			
			$tr .= $this->_helper->Util->completa(45, $titulo->nomesacador, " ");
			$tr .= $this->_helper->Util->completa(9, $complemento, " ");
			$tr .= $this->_helper->Util->completa(4, $complemento, " ");
			$tr .= $this->_helper->Util->completa(2, $complemento, " ");
			$tr .= $this->_helper->Util->completa(44, $complemento, " ");
			$tr .= $this->_helper->Util->completa(8, $complemento, " ");
			$tr .= $this->_helper->Util->completa(20, $complemento, " ");
			$tr .= $this->_helper->Util->completa(2, $complemento, " ");
			$tr .= $this->_helper->Util->completa(25, $complemento, " ");
			$tr .= $this->_helper->Util->completa(1, $complemento, " ");
			$tr .= $this->_helper->Util->completa(1, $complemento, " ");
			$tr .= $this->_helper->Util->completa(7, $complemento, " ");
			$tr .= $this->_helper->Util->completa(6, $titulo->livro, " ");//
			$tr .= $this->_helper->Util->completa(1, $complemento, " ");
			$tr .= $this->_helper->Util->completa(4, $titulo->folha, " ");//
			$tr .= $this->_helper->Util->completa(3, $titulo->codigo, " ");//
			
			$tr .= $this->_helper->Util->completa(14, $titulo->valortitulo, " ");
			$tr .= $this->_helper->Util->completa(8, $this->_helper->Util->converteDataArquivo($titulo->dataemissao), "0");
			
			if((int)$titulo->tipoidentificacao == 1){
				$pessoa = 'J';
				$tipodocumento = 1;
				$numeroidentificacao = substr($titulo->numeroidentificacao, 0, 8);
				$filial = substr($titulo->numeroidentificacao, 8, 4);
				$digitocontrole = substr($titulo->numeroidentificacao, 12, 2);
			}
			else if((int)$titulo->tipoidentificacao == 2){
				$pessoa = 'F';
				$tipodocumento = 2;
				$numeroidentificacao = substr($titulo->numeroidentificacao, 0, 9);
				$filial = '';				
				$digitocontrole = substr($titulo->numeroidentificacao, 10, 2);
			}
			else{
				$pessoa = '';
				$tipodocumento = '';
				$numeroidentificacao = '';
				$filial = '';				
				$digitocontrole = '';
			}
			
			$tr .= $this->_helper->Util->completa(1, $pessoa, " ");
			$tr .= $this->_helper->Util->completa(1, $tipodocumento, " ");
			$tr .= $this->_helper->Util->completa(2, $complemento, " ");//////
			$tr .= $this->_helper->Util->completa(25, $complemento, " ");
			$tr .= $this->_helper->Util->completa(45, $titulo->nome, " ");
			$tr .= $this->_helper->Util->completa(3, $complemento, " ");
			$tr .= $this->_helper->Util->completa(9, $numeroidentificacao, "0");
			$tr .= $this->_helper->Util->completa(4, $filial, "0");
			$tr .= $this->_helper->Util->completa(2, $digitocontrole, "0");
			$tr .= $this->_helper->Util->completa(10, $complemento, " ");
			$tr .= $this->_helper->Util->completa(45, $titulo->endereco, " ");
			$tr .= $this->_helper->Util->completa(8, $titulo->cep, "0");
			$tr .= $this->_helper->Util->completa(20, $titulo->cidade, " ");
			$tr .= $this->_helper->Util->completa(2, $titulo->estado, " ");
			$tr .= $this->_helper->Util->completa(2, $cartorio->codigo, "0");
			
			$tr .= $this->_helper->Util->completa(10, $titulo->protocolo, "0");
			$tr .= $this->_helper->Util->completa(8, $cartorio->cep, "0");
			$tr .= $this->_helper->Util->completa(12, $complemento, " ");
			$tr .= $this->_helper->Util->completa(8, $complemento, " ");/////  $titulo->data_cancelamento Data do cancelamento do protesto, caso seja cancelamento
			$tr .= $this->_helper->Util->completa(1, '1', " ");
			$tr .= $this->_helper->Util->completa(8, $complemento, " ");
			$tr .= $this->_helper->Util->completa(8, $complemento, " ");
			$tr .= $this->_helper->Util->completa(31, $complemento, " ");
			$tr .= $this->_helper->Util->completa(60, $complemento, " ");
			$tr .= $this->_helper->Util->completa(7, $i, "0") . "\n";
			
			$trs .= $tr;
			
			$i++;
		}
		
		$trailler = '9';
		$trailler .= $this->_helper->Util->completa(592, $complemento, " ");				
		$trailler .= $this->_helper->Util->completa(7, $i, "0") . "\n";		
				
		$nomearquivo = 'C'.$cartorio->codigo.date('dm').".PMJ";
		
		$user = new Zend_Session_Namespace('user_data');
        $data['idUsuario'] = $user->user->idUsuario;
        $data['arquivo'] = $nomearquivo;
        //$data['data_envio'] = date ( 'Y-m-d h:i:s' );
        $data['tipo'] = 8; //tipo 8 = Serasa - cancelamento
            
        $arquivo = new Arquivo();
        $arquivo->insert($data);
        $lastId = $arquivo->getAdapter()->lastInsertId();
		
		$path = APPLICATION_PATH . '/arquivos/serasa';		
		if(!file_exists($path))mkdir($path);
		
		$path .= '/cancelamento';		
		if(!file_exists($path))mkdir($path);		
		$path .= "/" . $lastId;
		
		
		file_put_contents($path, $header . $trs . $trailler, FILE_APPEND);
		
		header('Content-type: octet/stream');
	    header('Content-disposition: attachment; filename="'.$nomearquivo.'";');
	    header('Content-Length: '.filesize($path));
	    readfile($path);
	    exit;
    }

    public function protestosgeradosAction()
    {
        $model_arquivo = new Arquivo();
    	
        $select =  $model_arquivo->selectArquivos(11);
        //$this->_helper->Valor->_pvar($select);
    	$data = $select;
    	
        $this->view->arquivos = $data;
    }


}

















