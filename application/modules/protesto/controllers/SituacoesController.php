<?php

class SituacoesController extends Zend_Controller_Action
{

	private $model_protesto = null;
    private $model_titulo_importado = null;
    private $model_titulo = null;
    private $model_historico = null;
    private $model_serasa = null;
    private $model_custa = null;
    
    public function init()
    {
    	if ( !Zend_Auth::getInstance()->hasIdentity() ) {
	        return $this->_helper->redirector->goToRoute( array('module'=>'admin', 'controller' => 'auth'), null, true);
	    }
    	$this->user = new Zend_Session_Namespace('user_data');	    
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
        $this->model_historico = new Historico();
        $this->model_serasa = new Serasa();
        $this->model_custa = new Custa();
        $this->view->setEncoding('ISO-8859-1');
    }

    public function indexAction()
    {
        // action body
    }

    public function suspenderAction()
    {
        $select = $this->model_protesto->selectTitulosSuspensao();
        
    	$data = $this->model_protesto->fetchAll($select);
    	
        $this->view->titulos = $data;
    }

    public function cancelarAction()
    {
        $select = $this->model_protesto->selectTitulos(2);
        
    	$data = $this->model_protesto->fetchAll($select);
    	
        $this->view->titulos = $data;
    }

    public function sustarAction()
    {
        $select = $this->model_protesto->selectTitulos(2);
        
    	$data = $this->model_protesto->fetchAll($select);
    	
        $this->view->titulos = $data;
    }

    public function suspenderprotestoAction()
    {
        $form = new Protesto_Form_Protestartitulo();

         $id      = (int) $this->_getParam('idProtesto');
         
         if($id){
	         $data    = $this->model_protesto->selectDevedor($id);         		
	         
	         if ( null === $data ){
	            ZendX_JQuery_FlashMessenger::addMessage('Título não encontrado.', 'notice'); 
	            return false;
	         }
	         $data->valortitulo = $this->_helper->Util->valor($data->valortitulo);
	         $form->setAsEditForm($data);
         }
         
         if ( $this->_request->isPost()){

	        $data_historico['idTitulo'] = $this->_request->getPost('idTitulo'); 
         	$data_historico['idSituacao'] = 9;
         	  
	        if($this->_request->getPost('tipo') == 7)     		
		        $this->model_titulo->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);
		    else
		    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);

		    $data_historico['idProtesto'] = $this->_request->getPost('idProtesto');
			//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
			$data_historico['descricao'] = "Protesto do título foi suspenso.";
			
			$this->setSerasa($this->_request->getPost('idProtesto'), 'c');
			
			$model_historico = new Historico();    	    	
			$model_historico->insert($data_historico);
						
			ZendX_JQuery_FlashMessenger::addMessage('Título marcado como suspenso.');
			//$this->_redirect('/situacoes/suspender');
			echo "<script>";
			echo "history.go(-2);";
			echo "</script>";
			exit;

         }
		
		$this->view->form = $form;
    }

    public function cancelarprotestoAction()
    {
        $form = new Protesto_Form_Protestartitulo();

         $id      = (int) $this->_getParam('idProtesto');
         
         if($id){
	         $data    = $this->model_protesto->selectDevedor($id);         		
	         
	         if ( null === $data ){
	            ZendX_JQuery_FlashMessenger::addMessage('Título não encontrado.', 'notice');
	            return false;
	         }
	         $data->valortitulo = $this->_helper->Util->valor($data->valortitulo);
	         $form->setAsEditForm($data);
         }
         
         if ( $this->_request->isPost()){

	        $data_historico['idTitulo'] = $this->_request->getPost('idTitulo'); 
         	$data_historico['idSituacao'] = 3;
         	  
	        if($this->_request->getPost('tipo') == 7)     		
		        $this->model_titulo->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);
		    else
		    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);

		    $data_historico['idProtesto'] = $this->_request->getPost('idProtesto');
			//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
			$data_historico['descricao'] = "Protesto do título cancelado.";
			
			$this->setSerasa($this->_request->getPost('idProtesto'), 'c');
	        
			$model_historico = new Historico();    	    	
			$model_historico->insert($data_historico);

			ZendX_JQuery_FlashMessenger::addMessage('Título marcado como cancelado.');
			
			//$this->_redirect('/situacoes/cancelar');
			echo "<script>";
			echo "history.go(-2);";
			echo "</script>";
			exit;

         }
		
		$this->view->form = $form;
    }

    public function sustarprotestoAction()
    {
        $form = new Protesto_Form_Protestartitulo();

         $id      = (int) $this->_getParam('idProtesto');
         
         if($id){
	         $data    = $this->model_protesto->selectDevedor($id);         		
	         
	         if ( null === $data ){
	            ZendX_JQuery_FlashMessenger::addMessage('Título não encontrado.', 'notice');
	            return false;
	         }
	         $data->valortitulo = $this->_helper->Util->valor($data->valortitulo);
	         $form->setAsEditForm($data);
         }
         
         if ( $this->_request->isPost()){

	        $data_historico['idTitulo'] = $this->_request->getPost('idTitulo'); 
         	$data_historico['idSituacao'] = 4;
         	  
	        if($this->_request->getPost('tipo') == 7)     		
		        $this->model_titulo->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);
		    else
		    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);

		    $data_historico['idProtesto'] = $this->_request->getPost('idProtesto');
			//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
			$data_historico['descricao'] = "Título Sustado.";
			
			$this->setSerasa($this->_request->getPost('idProtesto'), 'c');
	        
			$model_historico = new Historico();    	    	
			$model_historico->insert($data_historico);

			ZendX_JQuery_FlashMessenger::addMessage('Título marcado como sustado.');
			
			//$this->_redirect('/situacoes/sustar');
			echo "<script>";
			echo "history.go(-2);";
			echo "</script>";
			exit;

         }
		
		$this->view->form = $form;
    }

    public function protestossuspensosAction()
    {
        $select = $this->model_protesto->selectTitulos(9);
        
    	$data = $this->model_protesto->fetchAll($select);
    	
        $this->view->titulos = $data;
    }

    public function protestoscanceladosAction()
    {
        $select = $this->model_protesto->selectTitulos(3);
        
    	$data = $this->model_protesto->fetchAll($select);
    	
        $this->view->titulos = $data;
    }

    public function protestossustadosAction()
    {
        $select = $this->model_protesto->selectTitulos(4);
        
    	$data = $this->model_protesto->fetchAll($select);
    	
        $this->view->titulos = $data;
    }

    public function titulossustadosAction()
    {
        $select = $this->model_protesto->selectTitulos(10);
        
    	$data = $this->model_protesto->fetchAll($select);
    	
        $this->view->titulos = $data;
    }

    public function revogarsustacaoAction()
    {
        $form = new Protesto_Form_Protestartitulo();

         $id      = (int) $this->_getParam('idProtesto');
         
         if($id){
	         $data    = $this->model_protesto->selectDevedor($id);         		
	         
	         if ( null === $data ){
	            ZendX_JQuery_FlashMessenger::addMessage('Título não encontrado.', 'notice');
	            return false;
	         }
	         $data->valortitulo = $this->_helper->Util->valor($data->valortitulo);
	         $form->setAsEditForm($data);
         }
         
         if ( $this->_request->isPost()){

	        $data_historico['idTitulo'] = $this->_request->getPost('idTitulo'); 
         	$data_historico['idSituacao'] = 2;
         	  
	        if($this->_request->getPost('tipo') == 7)     		
		        $this->model_titulo->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);
		    else
		    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);

		    $data_historico['idProtesto'] = $this->_request->getPost('idProtesto');	
			//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
			$data_historico['descricao'] = "Revogação da Sustação.";
			
			$this->setSerasa($this->_request->getPost('idProtesto'), 'i');
	        
			$model_historico = new Historico();    	    	
			$model_historico->insert($data_historico);

			ZendX_JQuery_FlashMessenger::addMessage('Sustação revogada.');
			
			//$this->_redirect('/situacoes/titulosustados');
			echo "<script>";
			echo "history.go(-2);";
			echo "</script>";
			exit;

         }
		
		$this->view->form = $form;
    }

    public function revogarsuspensaoAction()
    {
        $form = new Protesto_Form_Protestartitulo();

         $id      = (int) $this->_getParam('idProtesto');
         
         if($id){
	         $data    = $this->model_protesto->selectDevedor($id);         		
	         
	         if ( null === $data ){
	            ZendX_JQuery_FlashMessenger::addMessage('Título não encontrado.', 'notice');
	            return false;
	         }
	         $data->valortitulo = $this->_helper->Util->valor($data->valortitulo);
	         $form->setAsEditForm($data);
         }
         
         if ( $this->_request->isPost()){

	        $data_historico['idTitulo'] = $this->_request->getPost('idTitulo'); 
         	$data_historico['idSituacao'] = 20;
         	  
	        if($this->_request->getPost('tipo') == 7)     		
		        $this->model_titulo->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);
		    else
		    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);

		    $data_historico['idProtesto'] = $this->_request->getPost('idProtesto');
			//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
			$data_historico['descricao'] = "Revogação da Suspensão.";
			
			$this->setSerasa($this->_request->getPost('idProtesto'), 'i');
	        
			$model_historico = new Historico();    	    	
			$model_historico->insert($data_historico);
			ZendX_JQuery_FlashMessenger::addMessage('Suspensão revogada.');			
			//$this->_redirect('/situacoes/protestossuspensos');
			echo "<script>";
			echo "history.go(-2);";
			echo "</script>";
			exit;

         }
		
		$this->view->form = $form;
    }

    public function revogarcancelamentoAction()
    {
        $form = new Protesto_Form_Protestartitulo();

         $id      = (int) $this->_getParam('idProtesto');
         
         if($id){
	         $data    = $this->model_protesto->selectDevedor($id);         		
	         
	         if ( null === $data ){
	            ZendX_JQuery_FlashMessenger::addMessage('Título não encontrado.', 'notice');
	            return false;
	         }
	         $data->valortitulo = $this->_helper->Util->valor($data->valortitulo);
	         $form->setAsEditForm($data);
         }
         
         if ( $this->_request->isPost()){

	        $data_historico['idTitulo'] = $this->_request->getPost('idTitulo'); 
         	$data_historico['idSituacao'] = 2;
         	  
	        if($this->_request->getPost('tipo') == 7)     		
		        $this->model_titulo->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);
		    else
		    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);
	         
		    $data_historico['idProtesto'] = $this->_request->getPost('idProtesto');
			//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
			$data_historico['descricao'] = "Revogação do Cancelamento.";
			
			$this->setSerasa($this->_request->getPost('idProtesto'), 'i');
	        
			$model_historico = new Historico();    	    	
			$model_historico->insert($data_historico);
			ZendX_JQuery_FlashMessenger::addMessage('Cancelamento revogada.');			
			//$this->_redirect('/situacoes/protestoscancelados');
			echo "<script>";
			echo "history.go(-2);";
			echo "</script>";
			exit;

         }
		
		$this->view->form = $form;
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

    public function setSerasa($idProtesto, $codigo){
    	
    	$titulo = $this->model_serasa->getTitulo($idProtesto);
    	//print_r($titulo[0]);exit;
    	if(count($titulo) > 0 && $titulo[0]->codigooperacao != $codigo){
	    	$data_serasa['idProtesto'] = $idProtesto;
			$data_serasa['data_serasa'] = date ( 'Y-m-d' );				
			$data_serasa['codigooperacao'] = $codigo;
			$this->model_serasa->insert($data_serasa);
    	}
    }

}


