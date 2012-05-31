<?php 

class Admin_Bootstrap extends Zend_Application_Module_Bootstrap 
{ 
	protected function _initAutoload()
    {
    	$loader = Zend_Loader_Autoloader::getInstance();
        $loader->setFallbackAutoloader(true);
    	
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Admin_',
            'basePath'  => APPLICATION_PATH .'/modules/admin',
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
} 

