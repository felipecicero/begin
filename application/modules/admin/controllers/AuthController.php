<?php

class Admin_AuthController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	
    }

    public function indexAction()
    {
    $form = new Admin_Form_Login();
	    $this->view->form = $form;
	    //Verifica se existem dados de POST
	    if ( $this->getRequest()->isPost() ) {
	        $data = $this->getRequest()->getPost();	        
	        //Formulário corretamente preenchido?
	        if ( $form->isValid($data) ) {
	            $login = $form->getValue('login');
	            $senha = $form->getValue('senha');
	 
	            $dbAdapter = Zend_Db_Table::getDefaultAdapter();
	            //Inicia o adaptador Zend_Auth para banco de dados
	            $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
	            $authAdapter->setTableName('cap_usuarios')
	                        ->setIdentityColumn('login')
	                        ->setCredentialColumn('senha')
	                        ->setCredentialTreatment('MD5(?)');
	            //Define os dados para processar o login
	            $authAdapter->setIdentity($login)
	                        ->setCredential($senha);
	            //Efetua o login
	            $auth = Zend_Auth::getInstance();
	            $result = $auth->authenticate($authAdapter);
	            //Verifica se o login foi efetuado com sucesso
	            if ( $result->isValid() ) {
	                //Armazena os dados do usuário em sessão, apenas desconsiderando
	                //a senha do usuário
	                $info = $authAdapter->getResultRowObject(null, 'senha');
	                $storage = $auth->getStorage();
	                $storage->write($info);
	                
	                $authNamespace = new Zend_Session_Namespace('user_data');
					$authNamespace->user = $info;
						                
	                //Redireciona para o Controller protegido
	                return $this->_helper->redirector->goToRoute( array('module'=>'protesto', 'controller' => 'index'), null, true);
	            } else {
	                //Dados inválidos	                
	                ZendX_JQuery_FlashMessenger::addMessage("Usuário ou senha inválidos!!", 'error');
	                $this->_redirect('/admin/auth/index');
	            }
	        } else {
	            //Formulário preenchido de forma incorreta
	            $form->populate($data);
	        }
	    
    	}
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
    	$auth->clearIdentity();
    	
    	Zend_Session::start();
    	Zend_Session::destroy(true);
    	
    	
    	return $this->_helper->redirector('index');
    }


}



