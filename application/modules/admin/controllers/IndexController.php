<?php

class Admin_IndexController extends Zend_Controller_Action
{

    public function init()
    {
    	if ( !Zend_Auth::getInstance()->hasIdentity() ) {
	        return $this->_helper->redirector->goToRoute( array('module'=>'admin', 'controller' => 'auth'), null, true);
	    }
	    
	    parent::init();
    	$this->view->setEncoding('ISO-8859-1');
    }

    public function indexAction()
    {        
        //$this->_redirect('/cartorio/index');
    }


}

