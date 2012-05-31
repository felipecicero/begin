<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initAutoload(){
        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->setFallbackAutoloader(true);
            
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            	            'namespace'             => '',
                        	'basePath'              => APPLICATION_PATH));
            
        return $moduleLoader;
                
    }
     
	protected function _initAcl(){
	    $aclSetup = new Zend_Acl_Setup();
	}
     
	public function _initTranslate() {
		$translator = new Zend_Translate ( array ('adapter' => 'array', 'content' => '../library/languages', 'locale' => 'pt_BR', 'scan' => Zend_Translate::LOCALE_DIRECTORY ) );
		Zend_Validate_Abstract::setDefaultTranslator ( $translator );
	}
     
	protected function _initViewHelpers() {
        $view = new Zend_View ();
        $this->bootstrap ( 'layout' );
        $layout = $this->getResource ( 'layout' );
        $view = $layout->getView ();
        $view->addHelperPath ( 'ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper' );
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer ();
        $viewRenderer->setView ( $view );
        Zend_Controller_Action_HelperBroker::addHelper ( $viewRenderer );
    }
    
	protected function _initHelpers(){
		Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH .'\helpers');
		
	}
    
}

