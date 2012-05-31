<?php

class /*Protesto_*/IndexController extends Zend_Controller_Action
{

    public function init()
    {
    	if ( !Zend_Auth::getInstance()->hasIdentity() ) {
	        return $this->_helper->redirector->goToRoute( array('module'=>'admin', 'controller' => 'auth'), null, true);
	    }
	    
    }

    public function indexAction()
    {
        // action body
    }


}

