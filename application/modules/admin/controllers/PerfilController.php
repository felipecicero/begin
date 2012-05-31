<?php

class Admin_PerfilController extends Zend_Controller_Action
{

	private $_model = null;
	private $model_permissao = null;
	
    public function init()
    {
        if ( !Zend_Auth::getInstance()->hasIdentity() ) {
	        return $this->_helper->redirector->goToRoute( array('module'=>'admin','controller' => 'auth'), null, true);
	    }
	    
    	$params     = $this->getRequest()->getParams();			
	    $model_acl 	= new Acl();	    
	    $result = $model_acl->isAllowed($params["controller"] . "/" . $params["action"]);	    
	    if(!$result){
	    	ZendX_JQuery_FlashMessenger::addMessage("Área do sistema restrita.", 'error');
	    	$this->_redirect('/protesto/index');	    	
	    }
	    
        parent::init();
       	$this->_model = new Papel(); 
       	$this->model_permissao = new Permissao();       
        $this->view->setEncoding('ISO-8859-1');
    }

    public function indexAction()
    {
        // action body
    }

    public function cadastrarperfilAction()
    {
        $form = new Admin_Form_Perfil();
        
    	if ( $this->_request->isPost() )
        {	
        	$_data = $this->_request->getPost();
        	$recursos = array();        	
        	foreach($_data as $da => $d){
        		if( is_int ( $da )){
        			foreach($d as $val){
        				$recursos[] = $val;
        			}
        		}
        	}
        	
            $data = array(
                'papel'  => $this->_request->getPost('papel'),
                'descricao' => $this->_request->getPost('descricao')
            );
            
            //$recursos = $this->_request->getPost('recursos');
          
            if ( $form->isValid($data) )
            {
                if($this->_model->insert($data)){
					$idPapel = $this->_model->getAdapter()->lastInsertId();
					
                	$this->cadastrarPermissoes($idPapel, $recursos);
                	
                	ZendX_JQuery_FlashMessenger::addMessage('Dados cadastrados com sucesso.');
                }
                else ZendX_JQuery_FlashMessenger::addMessage('Problema ao cadastrar dados.', 'error');
            }
        }
                
    	$this->view->form = $form;
    }

    public function editarperfilAction()
    {
        $form = new Admin_Form_Perfil();
    	
    	if ( $this->_request->isPost() ){
            
    		$_data = $this->_request->getPost();
        	$recursos = array();        	
        	foreach($_data as $da => $d){
        		if( is_int ( $da )){
        			foreach($d as $val){
        				$recursos[] = $val;
        			}
        		}
        	}
        	
    		$data = array(
                'papel'  => $this->_request->getPost('papel'),
                'descricao' => $this->_request->getPost('descricao')
            );

            
            if ( $form->isValid($data) )
            {            	
                $this->_model->update($data, "idPapel = " . $this->_request->getPost('idPapel'));
                	
                $this->editarPermissoes($this->_request->getPost('idPapel'), $recursos);
                
                ZendX_JQuery_FlashMessenger::addMessage('Dados alterados com sucesso.');
                $this->_redirect('/admin/perfil/perfis');
            }            
            ZendX_JQuery_FlashMessenger::addMessage('Problemas ao alterar dados.', 'error');
            $this->_redirect('/admin/perfil/perfis');            
        }
        
        $id      = (int) $this->_getParam('idPapel');        
        $result  = $this->_model->find($id);
        $data    = $result->current();
        $permissoes = $this->model_permissao->getPermissoes((int) $this->_getParam('idPapel'));
                
        if ( null === $data )
        {
            ZendX_JQuery_FlashMessenger::addMessage('Perfil não encontrado.', 'notice');
            return false;
        }

        $form->setAsEditForm($data, $permissoes);

        $this->view->form = $form;
    }

    public function deletarperfilAction()
    {
        //verificamos se realmente foi informado algum ID
        if ( $this->_hasParam('idPapel') == false )
        {
            $this->_redirect('/admin/perfil/perfis');
        }
 
        $id = (int) $this->_getParam('idPerfil');
        $where = $this->_model->getAdapter()->quoteInto('idPapel = ?', $id);
        if($this->_model->delete($where)){
        	ZendX_JQuery_FlashMessenger::addMessage('Dados deletados com sucesso.');
        }        
        else 
        	ZendX_JQuery_FlashMessenger::addMessage('Problema ao deletar dados.', 'error');
        	
        $this->_redirect('/admin/perfil/perfis');
    }

    public function perfisAction()
    {
        $select = $this->_model->select();
        
    	$data = $this->_model->fetchAll($select);
    	
        $this->view->perfis = $data;
    }

    public function cadastrarPermissoes($idPapel, $recursos = array()){
    	$model_permissao = new Permissao();
    	
    	$data['idPapel'] = $idPapel;
    	$data['permissao'] = 'allow';
    	
    	foreach ($recursos as $recurso){    		
    		$data['idRecurso'] = $recurso;    		
    		$model_permissao->insert($data);
    	}
    }
    
	public function editarPermissoes($idPapel, $recursos = array()){
    	$model_permissao = new Permissao();
    	
    	$where = $this->model_permissao->getAdapter()->quoteInto('idPapel = ?', $idPapel);
    	$this->model_permissao->delete($where);   
    	
    	$data['idPapel'] = $idPapel;
    	$data['permissao'] = 'allow';    	
    	foreach ($recursos as $recurso){    		
    		$data['idRecurso'] = $recurso;    		
    		$model_permissao->insert($data);
    	}
    }

}









