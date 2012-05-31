<?php

class VerificadorController extends Zend_Controller_Action
{

    private $model_protesto = null;
    private $model_edital = null;
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
        $this->model_protesto = new Protesto();
        $this->model_edital = new Edital();
        $this->model_titulo = new Titulo();
        $this->model_titulo_importado = new TituloImportado();
        $this->view->setEncoding('ISO-8859-1');
    }

    public function indexAction()
    {
        // action body
    }
    
	public function aceiteAction()
    {
        $select = $this->model_protesto->selectTitulos(21);
        
    	$data = $this->model_protesto->fetchAll($select);
    	
        $this->view->titulos = $data;
    }

    public function digitalizaraceiteAction()
    {
        $form = new Protesto_Form_DigitalizarAceite();
    	$user = new Zend_Session_Namespace('user_data');
		$idUsuario = $user->user->idUsuario;
		    		//print_r($this);exit;								    
		$id      = (int) $this->_getParam('idProtesto');       
        $data    = $this->model_protesto->selectDevedor($id);         		
 
        if ( null === $data )
        {
            ZendX_JQuery_FlashMessenger::addMessage('Título não encontrado.', 'notice');
            return false;
        }
        
        $form->setAsEditForm($data);
        
        if ( $this->_request->isPost()){
        	
        	$upload = new Zend_File_Transfer();
	        $file = $upload->getFileInfo();
	        
	        if(isset($file['arquivo']['name']) && $file['arquivo']['name'] != ''){
		        $nomearquivo = $file['arquivo']['name'];  
	    		
	            $data_aceite['idUsuario'] = $idUsuario;
	            $data_aceite['idProtesto'] = $id;
	            //$data_aceite['data_envio'] = date ( 'Y-m-d h:i:s' );
	    		$data_aceite['arquivo'] = $nomearquivo;
	            //print_r($data_aceite);exit;
	            $aceite = new Aceite();
	            $aceite->insert($data_aceite);
	            $lastId = $aceite->getAdapter()->lastInsertId();
	            
	            $path = APPLICATION_PATH . '/arquivos/aceites';		
				if(!file_exists($path))mkdir($path);					
				$path .= "/" . $lastId;
	            
	            $upload->addFilter('Rename', array('target' => $path, 'overwrite' => true));
		        
	        	if(!$upload->receive()){
	            	$where = $aceite->getAdapter()->quoteInto('idAceite = ?', $lastId);
	        		$aceite->delete($where);
	        		ZendX_JQuery_FlashMessenger::addMessage('Problemas no upload do canhoto.', 'error');
	        		return false;
	            }
	        } 
	        
        	$data_historico['idSituacao'] = 22; // notificação digitalizada, pronto pra protesto       		
        	if($data->tipo == 7)     		
		        $this->model_titulo->update($data_historico, "idTitulo = " . $data->idTitulo);
		    else
		    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data->idTitulo);        		
        	
		    $data_historico['idProtesto'] = $data->idProtesto;
	        $data_historico['idTitulo'] =$data->idTitulo; 
			$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
			$data_historico['descricao'] = "Título pronto para protesto (Aceite recebido).";
	        
			$model_historico = new Historico();    	    	
			$model_historico->insert($data_historico);
			ZendX_JQuery_FlashMessenger::addMessage('Aceite registrado com sucesso.');
			$this->_redirect('/verificador/aceite');
        	
        }
		
		$this->view->form = $form;
		//$this->_redirect('/verificador/aceite');
    }

    public function editalAction()
    {
         $id      = (int) $this->_getParam('idProtesto');       
         $data    = $this->model_protesto->getIdTitulo($id);         		
         if ( null === $data )
         {
            ZendX_JQuery_FlashMessenger::addMessage('Título não encontrado.', 'notice');
            return false;
         }
         
        $data_historico['idSituacao'] = 22;   
        
        if($data[0]->tipo == 7)     		
	        $this->model_titulo->update($data_historico, "idTitulo = " . $data[0]->idTitulo);
	    else
	    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data[0]->idTitulo);
         
        $data_historico['idTitulo'] =$data[0]->idTitulo;
        $data_historico['idProtesto'] =$data[0]->idProtesto; 
		//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
		$data_historico['descricao'] = "Título está em edital.";
        
		$model_historico = new Historico();    	    	
		$model_historico->insert($data_historico);
		
		$model_edital = new Edital();
		$busca = $model_edital->getEdital($id);
		if(count($busca) == 0 ){
			$data_edital['idProtesto'] = $id; 
			$data_edital['data_edital'] = date ( 'Y-m-d' );
			$model_edital->insert($data_edital);
		}
		else{
			ZendX_JQuery_FlashMessenger::addMessage('Este título já está em edital.', 'notice');
			$this->_redirect('protesto/index');
		}
		
		
		ZendX_JQuery_FlashMessenger::addMessage('Título está em edital.');
		$this->_redirect('/verificador/aceite');
    }

    public function listaeditalAction()
    {
        $select =  $this->model_edital->selectEditais();
       
    	//$data = $this->model_edital->fetchAll($select);
    	
        $this->view->editais = $select;
    }

    public function gerareditalAction()
    {
        $date_e      = $this->_getParam('data');
    	
    	$this->view->dataedital = $date_e;
    	
        $this->view->titulo = $this->model_edital->selectTitulosEdital($date_e);
    }

    public function excluirtituloeditalAction()
    {
        if ( $this->_hasParam('idProtesto') == false )
        {
            $this->_redirect('verificador/geraredital');
        }
 
        $id = (int) $this->_getParam('idProtesto');
        $where = $this->model_edital->getAdapter()->quoteInto('idProtesto = ?', $id);
        $this->model_edital->delete($where);

        $data    = $this->model_protesto->getIdTitulo($id);
        $data_historico['idSituacao'] = 21;   
        if($data[0]->tipo == 7)     		
	        $this->model_titulo->update($data_historico, "idTitulo = " . $data[0]->idTitulo);
	    else
	    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data[0]->idTitulo);
         
        $data_historico['idTitulo'] =$data[0]->idTitulo;
        $data_historico['idProtesto'] =$data[0]->idProtesto;  
		//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
		$data_historico['descricao'] = "Título excluído do edital. (Situação 2)";
        
		$model_historico = new Historico();    	    	
		$model_historico->insert($data_historico);
		
        ZendX_JQuery_FlashMessenger::addMessage('Título excluído do edital com sucesso.');
        $this->_redirect('verificador/geraredital/data/' . $this->_getParam('data'));
    }

    public function exportareditalAction()
    {
        $this->_helper->layout->disableLayout();
    	$date_e      = $this->_getParam('data');
    	
        $titulos = $this->model_edital->selectTitulosEdital($date_e);
        
        $this->view->titulo = $this->model_edital->selectTitulosEdital($date_e);
        $document = '';
        /*foreach($titulos as $titulo){
        	$document .= trim($titulo->apontamento) . " " . trim($titulo->vencimento) . " " . trim($titulo->numerotitulo) . " " . $titulo->valor . " " . trim($titulo->devedor) . " " . $titulo->credor . "\n";	
        }*/
    	
		
        header("Content-Type: text/html; application/vnd.msword");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("content-disposition: attachment;filename='Edital_".date('d_m_Y_h_i').".doc'");
		
		print $document;
		//die();
    }


}















