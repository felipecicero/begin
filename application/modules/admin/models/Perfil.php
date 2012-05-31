<?php

class Perfil extends Zend_Db_Table_Abstract{

	protected $_name = 'cap_perfis';

	public function findForSelect()
    {
    	$select = $this->select();
    	$select->order('nome');
    	return $this->fetchAll($select);
    }

}