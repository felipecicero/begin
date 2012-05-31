<?php

class Agencia extends Zend_Db_Table_Abstract{

	protected $_name = 'cap_agencias';

	public function findForSelect($id='')
    {
    	header('Content-Type: text/html; charset=ISO-8859-1');
    	$select = $this->select();
    	$select->order('descricao');
    	
    	if($id){
    		$select->where('idBanco = ?', $id);
    	}
    	
    	return $this->fetchAll($select);
    }
}

