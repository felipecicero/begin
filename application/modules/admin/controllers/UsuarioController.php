<?php

class Admin_UsuarioController extends Zend_Controller_Action
{

	private $_model = null;
	
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
        
       	$this->_model = new Usuario();        
        
        $this->view->setEncoding('ISO-8859-1');//para nao dar problemas com acentuação dos formulários
    }

    public function indexAction()
    {
        /*$usuario = Zend_Auth::getInstance()->getIdentity();
    	$this->view->user = $usuario;*/
    }

    public function cadastrarAction()
    {
        $form = new Admin_Form_Usuario();
        
    	if ( $this->_request->isPost() )
        {
            $data = array(
                'nome'  => $this->_request->getPost('nome'),
                'email' => $this->_request->getPost('email'),
            	'login' => $this->_request->getPost('login'),
            	'senha' => md5($this->_request->getPost('senha')),
            	'confirmasenha' => md5($this->_request->getPost('confirmasenha')),
            	'nascimento' => $this->_request->getPost('nascimento'),
            	'telefone' => $this->_request->getPost('telefone'),
            	'idPapel' => $this->_request->getPost('idPapel')
            );

            if ( $form->isValid($data) )
            {
            	unset($data['confirmasenha']);
            	$data['nascimento'] = implode("-", array_reverse(explode("/", $data['nascimento'])));
            	$dados['data_cadastro'] = date ( 'Y-m-d h:i:s' );
            	
                if($this->_model->insert($data)){                
                	ZendX_JQuery_FlashMessenger::addMessage('Dados cadastrados com sucesso.');
                }
                else ZendX_JQuery_FlashMessenger::addMessage('Problema ao cadastrar dados.', 'error');
            }
        }
                
    	$this->view->form = $form;
    }

    public function editarAction()
    {
        $form = new Admin_Form_Editarusuario();
    	
   		 if ( $this->_request->isPost() ){
            
            $data = array(
            	'nome'  => $this->_request->getPost('nome'),
                //'email' => $this->_request->getPost('email'),
            	//'login' => $this->_request->getPost('login'),
            	'senha' => md5($this->_request->getPost('senha')),
            	'confirmasenha' => md5($this->_request->getPost('confirmasenha')),
            	'nascimento' => $this->_request->getPost('nascimento'),
            	'telefone' => $this->_request->getPost('telefone'),
            	'idPapel' => $this->_request->getPost('idPapel')
            );

            $user = new Zend_Session_Namespace('user_data');
	        $idPapel = $user->user->idPapel;
	        if ($idPapel != 1 )//caso a pessoa acesse esse módulo por erro do sistema mas não seja de fato o admin
            {
            	ZendX_JQuery_FlashMessenger::addMessage('Você não pode alterar os dados dos usuários.', 'error');
            	$this->_redirect('/protesto/index');
            }
            
            if ( $form->isValid($data) )
            {
            	unset($data['confirmasenha']);
            	if($data['senha'] == md5('')){
            	   unset($data['senha']);
            	}
            	$data['nascimento'] = implode("-", array_reverse(explode("/", $data['nascimento']))); 

                if($this->_model->update($data, "idUsuario = " . $this->_request->getPost('idUsuario'))){
                	ZendX_JQuery_FlashMessenger::addMessage('Dados alterados com sucesso.');
                }
                else ZendX_JQuery_FlashMessenger::addMessage('Problemas ao alterar dados.', 'error');
                //$this->view->message = "Dados alterados com sucesso.";
                $this->_redirect('/admin/usuario/usuarios');
            }
            
        }
        
        $id      = (int) $this->_getParam('idUsuario');        
        $result  = $this->_model->find($id);
        $data    = $result->current();         
		$data['nascimento'] = implode("/", array_reverse(explode("-", $data['nascimento'])));
		$pass = $data['senha'];//recupera a senha original para que se vier senha em branco do formulario, a senha original permanecera a mesma quando atualizar		
		
        if ( null === $data )
        {
            ZendX_JQuery_FlashMessenger::addMessage('Usuário não encontrado.', 'notice');
            return false;
        }

        $form->setAsEditForm($data);

        $this->view->form = $form;
    }

    public function deletarAction()
    {
        // verificamos se realmente foi informado algum ID
        if ( $this->_hasParam('idUsuario') == false )
        {
            $this->_redirect('usuario/usuarios');
        }
 
        $id = (int) $this->_getParam('idUsuario');
        $where = $this->_model->getAdapter()->quoteInto('idUsuario = ?', $id);
        if($this->_model->delete($where)){
        	ZendX_JQuery_FlashMessenger::addMessage('Dados deletados com sucesso.');
        }        
        else 
        	ZendX_JQuery_FlashMessenger::addMessage('Problema ao deletar dados.', 'error');
        	
        $this->_redirect('admin/usuario/usuarios');
    }

    public function usuariosAction()
    {
        $select = $this->_model->select();
        
    	$data = $this->_model->fetchAll($select);
    	
        $this->view->user = $data;
    }


}









