<?php

class Situacao extends Zend_Db_Table_Abstract{
	protected $_name = 'cap_situacao';

	public function findForSelect()
    {
    	$select = $this->select();
    	$select->order('codigo');
    	return $this->fetchAll($select);
    }

}

