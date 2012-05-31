<?php 

class Protesto_Bootstrap extends Zend_Application_Module_Bootstrap 
{ 
	protected function _initAutoload()
    {
    	$loader = Zend_Loader_Autoloader::getInstance();
        $loader->setFallbackAutoloader(true);
    	
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Protesto_',
            'basePath'  => APPLICATION_PATH .'/modules/protesto',
            'resourceTypes' => array (
                'form' => array(
                    'path' => 'forms',
                    'namespace' => 'Form'
                ),
                'model' => array(
                    'path' => 'models',
                    'namespace' => 'Model'
                ),
            )
         ));
         
        return $autoloader;
    }
    
	protected function _initAcl(){
	    $aclSetup = new Zend_Acl_Setup();
	}
    
	/*protected function _initHelpers(){
		Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH .'/controllers/helpers');
	}*/
    
} 