<?php

class NotificadorController extends Zend_Controller_Action
{

    private $model_protesto = null;
    private $model_titulo = null;
    private $model_titulo_importado = null;

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
        //$this->model_amigos = new Amigos();        
        $this->model_protesto = new Protesto();
        $this->model_titulo_importado = new TituloImportado();
        $this->model_titulo = new Titulo();
        $this->view->setEncoding('ISO-8859-1');
    }

    public function indexAction()
    {
        $model_cartorio = new Cartorio();
        $cartorio = $model_cartorio->getCartorio();
        $this->view->cartorio = $cartorio;
    }

    public function notificacaoAction()
    {
        $select =  $this->model_protesto->selectTitulos(20);
        
    	$data = $this->model_protesto->fetchAll($select);
    	
        $this->view->titulos = $data;
    }

    public function boletosAction()
    {
        $select =  $this->model_protesto->selectTitulos(20);
        
    	$data = $this->model_protesto->fetchAll($select);
    	
        $this->view->titulos = $data;
    }

    public function pdfnoticacaoAction()
    {
        $id      = (int) $this->_getParam('idProtesto');
     	
        $data    = $this->model_protesto->selectDevedor($id);         		
        //print_r($data);exit;
        if ( null === $data ){
            $this->view->message = "Devedor não encontrado!";
            ZendX_JQuery_FlashMessenger::addMessage("Dados não encotrados.", 'notice');
            return false;
        }
        
        $data_historico['idTitulo'] = $data->idTitulo;

        if($data->idSituacao == 20)
        	$data_historico['idSituacao'] = 21; // Foi para notificação
        if($data->tipo == 7)     		
	        $this->model_titulo->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);
	    else
	    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);
		
	    $data_historico['idProtesto'] = $data->idProtesto;
		//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
		$data_historico['descricao'] = "Notificação Gerada.";
        
		$model_historico = new Historico();    	    	
		$model_historico->insert($data_historico);
        
    	$notificacao = new Begin_Notificacao();
    	$model_cartorio = new Cartorio();
    	
    	$data_cartorio = $model_cartorio->getCartorio();

    	$notificacao->gerarNotificacao($data, $data_cartorio);
    }

    public function pdfboletoAction()
    {
        $id      = (int) $this->_getParam('idProtesto');        
     	
        $data    = $this->model_protesto->selectDevedor($id);         		
        //print_r($data->valortitulo);exit;
        if ( null === $data )
        {
            ZendX_JQuery_FlashMessenger::addMessage("Dados não encotrados.", 'notice');
            return false;
        }
                
        $data_historico['idTitulo'] = $data->idTitulo;

        if($data->idSituacao == 20) 
        	$data_historico['idSituacao'] = 22;//Aceite ja foi considerado recebido
        //print_r($data);exit;
        if($data->tipo == 7)
	        $this->model_titulo->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);
	        	       
	    else
	    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);
	    	
	    $data_historico['idProtesto'] = $data->idProtesto; 
		//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
		$data_historico['descricao'] = "Boleto Gerado.";
		
		$model_historico = new Historico();    	    	
		$model_historico->insert($data_historico);
        
    	$boleto = new Begin_Boleto();

    	$model_cartorio = new Cartorio();
    	
    	$model_custas = new Custa();
    	
    	$data_cartorio = $model_cartorio->getCartorio();
    	
    	$data_custas = $model_custas->getCustas($data->idProtesto, $data->valortitulo);
    	//print_r($data_custas);exit;
    	$boleto->gerarBoleto($data, $data_cartorio, $data_custas);
    }

    public function pdfboletogrupoAction()
    {
    	set_time_limit(0);
    	
        $__data    = $this->model_protesto->selectNotificador();
    	       
        if ( count($__data) < 1 ){
            ZendX_JQuery_FlashMessenger::addMessage("Talvez esse boleto já tenha sido gerado.", 'info');
            $this->_redirect('/notificador/boletos');
        }
                
    	$model_historico = new Historico();
        
        foreach ($__data as $data){
        	$data_historico = array();
        	        	
        	$data_historico['idTitulo'] = $data->idTitulo;

	        if($data->idSituacao == 20)        
	        	$data_historico['idSituacao'] = 22; //Foi para o Edital =x

	        if($data->tipo == 7)
		        $this->model_titulo->update($data_historico, "idTitulo = " . $data->idTitulo);
		    else
		    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data->idTitulo);
	
		    $data_historico['idProtesto'] = $data->idProtesto;
			$data_historico['descricao'] = "Boleto Gerado.";
	        
			$model_historico->insert($data_historico);	
        }
		
    
    	$boleto = new Begin_Boleto();

    	$model_cartorio = new Cartorio();
    	    	
    	$data_cartorio = $model_cartorio->getCartorio();
    	
    	$boleto->gerarBoletos($__data, $data_cartorio);
    }

    public function pdfnotificacaogrupoAction()
    {
    	set_time_limit(0);
    	      
        $__data    = $this->model_protesto->selectNotificador();         		
    	//print_r($__data);exit; 
    	if ( count($__data) < 1 ){
            ZendX_JQuery_FlashMessenger::addMessage("Talvez essa notificação já tenha sido gerada.", 'info');
            $this->_redirect('/notificador/notificacao');
            //return false;
        }
        
        $model_historico = new Historico();
        
        foreach ($__data as $data){
        	$data_historico = array();
        	        	
        	$data_historico['idTitulo'] = $data->idTitulo;

	        if($data->idSituacao == 20)        
	        	$data_historico['idSituacao'] = 21; //Foi para notificação

	        if($data->tipo == 7)
		        $this->model_titulo->update($data_historico, "idTitulo = " . $data->idTitulo);
		    else
		    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data->idTitulo);
	
		    $data_historico['idProtesto'] = $data->idProtesto;
			$data_historico['descricao'] = "Notificação Gerada.";
	        
			$model_historico->insert($data_historico);	
        }
        
        
    	$notificacao = new Begin_Notificacao();
    	$model_cartorio = new Cartorio();
    	
    	$data_cartorio = $model_cartorio->getCartorio();

    	$notificacao->gerarNotificacoes($__data, $data_cartorio);
    }

    public function titulosAction()
    {
        $select = $this->model_protesto->selectTitulos(0); // pra pegar todos os titulos
        
    	$data = $this->model_protesto->fetchAll($select);
    	
        $this->view->titulos = $data;
    }

    public function tituloAction()
    {
        $id      = (int) $this->_getParam('idProtesto');
         
         if($id){
	         $data    = $this->model_protesto->selectDevedor($id);         		
	         $historico = $this->model_historico->getHistorico($id); 
	         
	         if ( null === $data ){
	            ZendX_JQuery_FlashMessenger::addMessage('Título não encontrado.', 'notice');
	            return false;
	         }

	         
	         $this->view->data = $data;
	         $this->view->historico = $historico;	        
         }
         
	     return false;
    }

    public function menuAction()
    {
        // action body
    }

    public function notificacoesAction()
    {
    	$model_arquivo = new Arquivo();
    	
        $select =  $model_arquivo->selectArquivos(10);
        //$this->_helper->Valor->_pvar($select);
    	$data = $select;
    	
        $this->view->arquivos = $data;
    }

    public function boletoremessaAction()
	{
		
		$model_cartorio = new Cartorio();
		$data_cartorio = $model_cartorio->getCartorio();
		
		$model_historico = new Historico();
		$data_protesto = $model_historico->selectTitulos();
		
		//print_r(count($data_protesto));exit;
		
		if ( count($data_protesto) < 1 ){
            ZendX_JQuery_FlashMessenger::addMessage("Não existe remessa de boletos a ser gerado.", 'info');
            $this->_redirect('/notificador/boletos');
        }
		
		$numero_sequencial = 1;
		
		$header_buffer = '01REMESSA01COBRANCA';
		$complemento = '';
		$header = '';
		$header .= $this->_helper->Util->completa(26, $header_buffer, " ");
		$header .= $this->_helper->Util->completa(20, $data_cartorio->codigo_empresa, "0");
		$header .= $this->_helper->Util->completa(30, $data_cartorio->razao, " ");
		$header .= $this->_helper->Util->completa(3, $data_cartorio->numerobanco, "0");
		$header .= $this->_helper->Util->completa(15, strtoupper($data_cartorio->banco), " ");
		$header .= $this->_helper->Util->completa(6, date('dmy'), "0");
		$header .= $this->_helper->Util->completa(8, $complemento, " ");
		$header .= $this->_helper->Util->completa(2, 'MX', " ");
		$header .= $this->_helper->Util->completa(7, '200', "0");
		$header .= $this->_helper->Util->completa(277, $complemento, " ");
		$header .= $this->_helper->Util->completa(6, $numero_sequencial, "0"). "\n";
		
		$trailer = '';
		$numero_sequencial = 2;
		foreach ($data_protesto as $data){
			$digito = '';
			$digito .= $data_cartorio->carteira;
			$digito .= $this->_helper->Util->completa(10, $data->protocolo, "0");
			
			$trailer .= '100000 000000000000 0';
			$trailer .= $this->_helper->Util->completa(3, $data_cartorio->carteira, "0");
			$trailer .= $this->_helper->Util->completa(5, $data_cartorio->agencia, "0");
			$trailer .= $this->_helper->Util->completa(8, str_replace('-', '', $data_cartorio->conta), "0");
			$trailer .= '                            00000';
			
			$trailer .= $this->_helper->Util->completa(11, $data->protocolo, "0");
			$trailer .= $this->modulo_11($digito, 7);
			$trailer .= $this->_helper->Util->completa(10, "0", "0");
			$trailer .= $this->_helper->Util->completa(1, "2", "0");
			$trailer .= $this->_helper->Util->completa(12, " ", " ");
			$trailer .= $this->_helper->Util->completa(1, "2", "0");
			$trailer .= $this->_helper->Util->completa(2, " ", " ");
			$trailer .= $this->_helper->Util->completa(2, "1", "0");
			$trailer .= $this->_helper->Util->completa(10, $data->protocolo, "0");
			$trailer .= $this->_helper->Util->completa(6, $this->_helper->Util->converteDataArquivoy($data->datavencimentotitulo), "0");
			$trailer .= $this->_helper->Util->completa(13, preg_replace('/[^0-9]/', '', $data->valortitulo), "0");
			$trailer .= $this->_helper->Util->completa(8, "0", "0");
			$trailer .= $this->_helper->Util->completa(2, "99", "0");
			$trailer .= $this->_helper->Util->completa(1, "A", " ");
			$trailer .= $this->_helper->Util->completa(6, $this->_helper->Util->converteDataArquivoy($data->data_entrada), "0");
			$trailer .= $this->_helper->Util->completa(2, "0", "0");
			$trailer .= $this->_helper->Util->completa(2, "0", "0");
			$trailer .= $this->_helper->Util->completa(13, "0", "0");
			$trailer .= $this->_helper->Util->completa(6, "0", "0");
			$trailer .= $this->_helper->Util->completa(13, "0", "0");
			$trailer .= $this->_helper->Util->completa(13, "0", "0");
			$trailer .= $this->_helper->Util->completa(13, "0", "0");
			if($data->tipoidentificacao == 1) $trailer .= $this->_helper->Util->completa(2, "2", "0");
			if($data->tipoidentificacao == 2) $trailer .= $this->_helper->Util->completa(2, "1", "0");
			$trailer .= $this->_helper->Util->completa(14, $data->numeroidentificacao, "0");
			$trailer .= $this->_helper->Util->completa(40, strtoupper(trim($data->nome)), " ");
			$trailer .= $this->_helper->Util->completa(40, strtoupper(trim($data->enderecodevedor)), " ");
			$trailer .= $this->_helper->Util->completa(12, " ", " ");
			$trailer .= $this->_helper->Util->completa(8, $data->cep, "0");
			$trailer .= $this->_helper->Util->completa(60, " ", " ");
			$trailer .= $this->_helper->Util->completa(6, $numero_sequencial, "0"). "\n";
			
			$numero_sequencial++;
		}
		
		$rodape = '9'; 
		$rodape .= $this->_helper->Util->completa(393, " ", " ");
		$rodape .= $this->_helper->Util->completa(6, $numero_sequencial, "0");
		
		$nomearquivo = 'CB' . date('d') . date('m') . date('y') . '01.REM';
		
		$user = new Zend_Session_Namespace('user_data');
		$data_user['idUsuario'] = $user->user->idUsuario;
		$data_user['arquivo'] = $nomearquivo;
		$data_user['data_envio'] = date ( 'Y-m-d h:i:s' );
		$data_user['tipo'] = 12; //tipo 12 = Remessa de boletos de cobrança
					
		$arquivo = new Arquivo();
		$arquivo->insert($data_user);
		$lastId = $arquivo->getAdapter()->lastInsertId();
		
		$path = APPLICATION_PATH . '/arquivos/remessacobranca';
		if(!file_exists($path))mkdir($path);
		$path .= "/" . $lastId;
		
		
		file_put_contents($path, $header . $trailer . $rodape, FILE_APPEND);
		
		header('Content-type: octet/stream');
	    header('Content-disposition: attachment; filename="'.$nomearquivo.'";');
	    header('Content-Length: '.filesize($path));
	    readfile($path);
		
		$this->_redirect('/notificador/boletos');
	}

	public function modulo_11($num, $base=9, $r=0)  
	{
		/**
		 *   Autor:
		 *           Pablo Costa <pablo@users.sourceforge.net>
		 *
		 *   Função:
		 *    Calculo do Modulo 11 para geracao do digito verificador 
		 *    de boletos bancarios conforme documentos obtidos 
		 *    da Febraban - www.febraban.org.br 
		 *
		 *   Entrada:
		 *     $num: string numérica para a qual se deseja calcularo digito verificador;
		 *     $base: valor maximo de multiplicacao [2-$base]
		 *     $r: quando especificado um devolve somente o resto
		 *
		 *   Saída:
		 *     Retorna o Digito verificador.
		 *
		 *   Observações:
		 *     - Script desenvolvido sem nenhum reaproveitamento de código pré existente.
		 *     - Assume-se que a verificação do formato das variáveis de entrada é feita antes da execução deste script.
		 */                                        

		$soma = 0;
		$fator = 2;
		for ($i = strlen($num); $i > 0; $i--) {
			$numeros[$i] = substr($num,$i-1,1);
			$parcial[$i] = $numeros[$i] * $fator;
			$soma += $parcial[$i];
			if ($fator == $base) {

				$fator = 1;
			}
			$fator++;
		}

		if ($r == 0) {
			$soma *= 10;
			$digito = $soma % 11;
			if ($digito == 10) {
				$digito = 0;
			}
			return $digito;
		} elseif ($r == 1){
			$resto = $soma % 11;
			return $resto;
		}
	}
	
	public function boletoretornoAction()
    {
    	set_time_limit(0);
        $form = new Protesto_Form_ImportarRemessa();
        
    	$upload = new Zend_File_Transfer();
        $file = $upload->getFileInfo();
       
    	if ( $this->_request->isPost()){
            
    		if($file['arquivo']['name'] == ''){
	        	ZendX_JQuery_FlashMessenger::addMessage("Você não selecionou nenhum arquivo.", 'error');
	        } 
	        else{
    		
	    		$data = array('tipo'  => $this->_request->getPost('tipo'));
	            $nomearquivo = $file['arquivo']['name'];
				

	            if(($data['tipo'] == 1 && ($nomearquivo[9].$nomearquivo[10].$nomearquivo[11] != 'RET' || count(str_split($nomearquivo)) != 12 ))){            	
	            	ZendX_JQuery_FlashMessenger::addMessage("Selecione o arquivo de retorno correto.", 'error');	
	            }else{
	            	$user = new Zend_Session_Namespace('user_data');
	            	$data['idUsuario'] = $user->user->idUsuario;
	            	$data['arquivo'] = $nomearquivo;

	            	
	            	$arquivo = new Arquivo();
	            	$arquivo->insert($data);
	            	$lastId = $arquivo->getAdapter()->lastInsertId();

	            	$path = APPLICATION_PATH . '/arquivos';		
					if(!file_exists($path))mkdir($path);
	            	
	            	$path .= '/retornocobranca';		
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
							$idCabecalho = $this->importarRetorno($path, $lastId);
	            			if($idCabecalho){
								$form->arquivo->setValue(""); 
			            		ZendX_JQuery_FlashMessenger::addMessage("Arquivo enviado com sucesso.");			            		
							}else{
								$where = $arquivo->getAdapter()->quoteInto('idArquivo = ?', $lastId);
	        					$arquivo->delete($where);						
								ZendX_JQuery_FlashMessenger::addMessage("Erro ao importar arquivo", 'error');
							}
	            		}	            		
	            	}      		           		
	           	}
            }
        }           
    	$this->view->form = $form;
    }
	
	public function importarRetorno($path, $idArquivo)
    {
        $file_content = file($path);

        $erros = $this->verificarLinhas($file_content);
            
        if(count($erros) > 0){
			$_ver = '';
            for($i=0; $i<count($erros); $i++){
				$_ver .= $erros[$i] . "; ";
			}								
			ZendX_JQuery_FlashMessenger::addMessage("A(s) linha(s) " . $_ver . " não constam no arquivo.", 'notice');
        }
        $id = '';
		for( $i = 1; $i < count($file_content)-1; $i++){
			$linha = $file_content[$i];
			for($j = 0; $j < strlen($linha); $j++){
				if($j >= 70 && $j <= 80){ 
					$id .= $linha[$j];
				
				}
			}
			$this->model_historico = new Historico();		
			$data = $this->model_historico->selectRetorno(trim($id));
			
			if(isset($data[0])){
				$novo_estado['idSituacao'] = 1;
				$where = "idTitulo = " . $data[0]->idTitulo;
				
				if($data[0]->tipo == 7)     		
					$this->model_titulo->update($novo_estado, $where);
				else
					$this->model_titulo_importado->update($novo_estado, $where);
			
				$data_historico['idTitulo'] = $data[0]->idTitulo; 
				$data_historico['idProtesto'] = $data[0]->idProtesto;
				$data_historico['idSituacao'] = 1;
				$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
				$data_historico['descricao'] = "Título pago.";
				
				$model_historico = new Historico();
				$model_historico->insert($data_historico);
							
				$id = '';
			}
			else{
				return false;
			}
        }
		return true;
    }
	
	public function verificarLinhas($fileContent)
    {
    	
		$controle = 2;
		$linhasFaltando = array();
			
		for($i=1 ; $i < count($fileContent)-1 ; $i++)
		{            			
			$line = $fileContent[$i];
			$numeroSequencial = '';
			
			for($j=0; $j<strlen($line); $j++){
				if($j >= 394 && $j <= 399){ 
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
    


}



























