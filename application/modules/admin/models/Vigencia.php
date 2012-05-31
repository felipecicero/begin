<?php

class Vigencia extends Zend_Db_Table_Abstract{

	protected $_name = 'cap_vigencias';

	public function findForSelect()
    {
    	$select = $this->select();
    	$select->order('vigencia DESC');
    	return $this->fetchAll($select);
    }
    
	public function getLastVigencia()
    {
    	$selectx = $this->select()    	
    					->from(array('vig' => 'cap_vigencias'), array('MAX(vigencia)'));
    	$sql = (string) $selectx;
    	
    	$select = $this->select();
    	$select->where('vigencia = (' . $selectx . ')');
    	    	
    	$idVigencia = $this->fetchAll($select); 
    	
    	return $idVigencia->Current();
    }
}

