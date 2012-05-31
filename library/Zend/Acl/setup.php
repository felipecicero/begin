<?php
class Zend_Acl_Setup
{
    /**
     * @var Zend_Acl
     */
    protected $_acl;

    public function __construct()
    {
        $this->_acl = new Zend_Acl();
        $this->_initialize();
    }

    protected function _initialize()
    {
        $this->_setupRoles();
        $this->_setupResources();
        $this->_setupPrivileges();
        $this->_saveAcl();
    }

    protected function _setupRoles()
    {
        $this->_acl->addRole( new Zend_Acl_Role('usuario') );
        //$this->_acl->addRole( new Zend_Acl_Role('writer'), 'usuario' );
        $this->_acl->addRole( new Zend_Acl_Role('administrador'), 'usuario' );
    }

    protected function _setupResources()
    {
        $this->_acl->addResource( new Zend_Acl_Resource('admin') );
        $this->_acl->addResource( new Zend_Acl_Resource('auth') );
        $this->_acl->addResource( new Zend_Acl_Resource('cartorio') );
        $this->_acl->addResource( new Zend_Acl_Resource('error') );
        $this->_acl->addResource( new Zend_Acl_Resource('perfil') );
        $this->_acl->addResource( new Zend_Acl_Resource('usuario') );
        $this->_acl->addResource( new Zend_Acl_Resource('index') );
        
        $this->_acl->addResource( new Zend_Acl_Resource('importador') );
        $this->_acl->addResource( new Zend_Acl_Resource('notificador') );
        $this->_acl->addResource( new Zend_Acl_Resource('pagamento') );
        $this->_acl->addResource( new Zend_Acl_Resource('protestos') );
        $this->_acl->addResource( new Zend_Acl_Resource('relatorios') );
        $this->_acl->addResource( new Zend_Acl_Resource('situacoes') );
        $this->_acl->addResource( new Zend_Acl_Resource('verificador') );
    }

    protected function _setupPrivileges()
    {    	
    	/** Creating permissions */
		/*$this ->_acl-> allow('guest', 'user')
      		  		-> deny('guest', 'article')
      		  		-> allow('guest', 'article', 'view')
      		  		-> allow('writer', 'article', array('add', 'edit'))
      		  		-> allow('admin', 'article', 'approve');*/
      		  		
      	/*$this ->_acl-> allow('administrador', 'user')
      		  		-> deny('guest', 'article')
      		  		-> allow('guest', 'article', 'view')
      		  		-> allow('writer', 'article', array('add', 'edit'))
      		  		-> allow('admin', 'article', 'approve');*/
      
        $this->_acl->allow( 'administrador', 'importador', array('index', 'login') )
                   ->allow( 'administrador', 'verificador', array('error', 'forbidden') );
        //$this->_acl->allow( 'writer', 'noticias', array('index', 'adicionar') )
                   //->allow( 'writer', 'auth', 'logout' );
        //$this->_acl->allow( 'admin', 'usuarios', array('index', 'adicionar') );
    }

    protected function _saveAcl()
    {
        $registry = Zend_Registry::getInstance();
        $registry->set('acl', $this->_acl);
    }
}