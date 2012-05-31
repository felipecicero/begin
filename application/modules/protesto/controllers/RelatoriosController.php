<?php

class RelatoriosController extends Zend_Controller_Action
{

    private $model_protesto = null;

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
        $this->view->setEncoding('ISO-8859-1');
    }

    public function indexAction()
    {
        // action body
    }

    public function certidoesAction()
    {
        if ( $this->_request->isPost()){
        	
        	$_data = $this->_request->getPost();
        	
        	$data = $this->model_protesto->selectCertidao(preg_replace('/[^0-9]/', '', $_data['documento']));
        	//return $this->_helper->redirector->goToRoute( array('module'=>'protesto', 'controller' => 'relatorios', 'action' => 'resultado', 'documento' => $documento), null, true);
        	
        	$certidao = new Begin_Certidao();
        	
        	if(count($data) > 0){
	    		$certidao->positiva($data);
	    	}
	    	else{
				$certidao->negativa($_data);				
	    	}
        	
        }
        
        $form = new Protesto_Form_Busca();
        
        $this->view->form = $form;
    }

    public function resultadoAction()
    {
    	$documento = $this->_getParam('documento');
    	
        $data = $this->model_protesto->selectCertidao($documento);
    	        
         /*print_r("<pre>");
	     print_r($data);
	     print_r("</pre>");
	     exit;*/

	     $this->view->titulos = $data;
	    
    }

    public function certidaointeiroteorAction()
    {
        if ( $this->_request->isPost()){
        	        	
        	$data = $this->model_protesto->selectInteiroTeor(preg_replace('/[^0-9]/', '', $this->_request->getPost('protocolo')));
        	//print_r(count($data));exit;
        	if(count($data) == 0){
        		ZendX_JQuery_FlashMessenger::addMessage("Não existe título com este protocolo.", 'error');
        	}
        	else{
	        	$certidao = new Begin_Certidao();	        	
		    	$certidao->inteiroTeor($data->current());
        	}
	    	
        }
        
        $form = new Protesto_Form_Inteiroteor();
        
        $this->view->form = $form;
    }


}







