<?php

class Especie extends Zend_Db_Table_Abstract{

	protected $_name = 'cap_especietitulos';

	public function findForSelect()
    {
    	$select = $this->select();
    	$select->order('descricao');
    	return $this->fetchAll($select);
    }

}

