<?php

class PagamentoController extends Zend_Controller_Action
{

	private $model_protesto = null;
    private $model_titulo_importado= null;
    private $model_titulo = null; 
    private $model_serasa = null;
    
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
        $this->model_serasa = new Serasa();
        $this->view->setEncoding('ISO-8859-1');
    }

    public function indexAction()
    {
        // action body
    }

    public function pagartituloAction()
    {
        $select =  $this->model_protesto->selectTitulosPagamento();
        
    	$data = $this->model_protesto->fetchAll($select);
    	
        $this->view->titulos = $data;
    }

    public function quitartituloAction()
    {
        $form = new Protesto_Form_Quitartitulo();

         if ( $this->_request->isPost()){
			
	        $data_historico['idTitulo'] = $this->_request->getPost('idTitulo'); 
         	$data_historico['idSituacao'] = 1; // o sacado aceitou a divida e efetuou o pagamento.
         	  
	        if($this->_request->getPost('tipo') == 7)     		
		        $this->model_titulo->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);
		    else
		    	$this->model_titulo_importado->update($data_historico, "idTitulo = " . $data_historico['idTitulo']);

		    $data_historico['idProtesto'] = $this->_request->getPost('idProtesto'); 
			//$data_historico['data_historico'] = date ( 'Y-m-d h:i:s' );
			$data_historico['descricao'] = "Título pago.";

			$model_historico = new Historico();    	    	
			$model_historico->insert($data_historico);
			
			$data_serasa['idProtesto'] = $this->_request->getPost('idProtesto');
			$data_serasa['data_serasa'] = date ( 'Y-m-d' );				
			$data_serasa['codigooperacao'] = 'c';
			$this->model_serasa->insert($data_serasa);
			
			//GERAR RECIBO
			$data    = $this->model_protesto->selectDevedor($this->_request->getPost('idProtesto'));
			
			$model_cartorio = new Cartorio();    	
    		$data_cartorio = $model_cartorio->getCartorio();
			
			$recibo = new Begin_Recibo();
			$recibo->gerarRecibo($data, $data_cartorio);
			ZendX_JQuery_FlashMessenger::addMessage('Título marcado como pago.');			
			//$this->_redirect('/pagamento/pagartitulo');
			echo "<script>";
			echo "history.go(-2);";
			echo "</script>";
			exit;			
         }
         
    	 $id      = (int) $this->_getParam('idProtesto');
         
         if($id){         	
			 unset($data);
	         $data    = $this->model_protesto->selectDevedor($id);         		
	         
	         if ( null === $data ){
	            ZendX_JQuery_FlashMessenger::addMessage('Título não encontrado.', 'notice');
	            return false;
	         }
	         $data->valortitulo = $this->_helper->Util->valor($data->valortitulo);
	         $form->setAsEditForm($data);
         }
		
		$this->view->form = $form;
    }


}





