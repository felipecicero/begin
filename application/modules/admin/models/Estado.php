<?php

class Estado extends Zend_Db_Table_Abstract
{

	protected $_name = 'cap_estados';

	public function findForSelect()
    {
    	$select = $this->select();
    	$select->order('nome');    	
    	return $this->fetchAll($select);
    }
}

