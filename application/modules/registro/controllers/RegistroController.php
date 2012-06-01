<?php

class Registro_RegistroController extends Zend_Controller_Action
{
		
	private $model_vigencias = null;
	
	public function init()
    {
       $this->model_vigencias = new RegVigencias();
    }

    public function indexAction()
    {
        
    }

    public function vigenciasAction()
    {
        // action body
    }
	
	public function cadastrarvigenciaAction()
    {
        $this->model_vigencias->getLastVigencia();
		
		$form = new Registro_Form_Vigencias();
		
		
        if ( $this->_request->isPost()){
				
	        	$data = array(
	        		'vigencia'  => $this->_request->getPost('vigencia'),	            	
	            );
	            	
	            if ( $form->isValid($data) ){
	            	$data['vigencia'] = implode("-", array_reverse(explode("/", $data['vigencia'])));
		             	            	
	            	
	            	if($this->model_vigencias->insert($data))
	            		ZendX_JQuery_FlashMessenger::addMessage('Dados cadastrados com sucesso.');
	           		 else 
	            		ZendX_JQuery_FlashMessenger::addMessage('Problemas ao cadastrar os dados.', 'error');	                
	                $this->_redirect('registro/registro/vigencias');
	            }
        }
        
        $form->vigencia->setValue(date('d/m/Y'));
        $this->view->form = $form;

    }

    public function deletarvigenciaAction()
    {
        // action body
    }

    public function editarvigenciaAction()
    {
        // action body
    }

    public function emolumentosAction()
    {
        // action body
    }
	
	public function cadastraremolumentoAction()
    {
        // action body
    }

    public function editaremolumentoAction()
    {
        // action body
    }

    public function deletaremolumentoAction()
    {
        // action body
    }

    public function custasAction()
    {
        // action body
    }

    public function cadastrarcustasAction()
    {
        // action body
    }

    public function editarcustasAction()
    {
        // action body
    }

    public function deletarcustasAction()
    {
        // action body
    }

}