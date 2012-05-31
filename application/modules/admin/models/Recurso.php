<?php

class Recurso extends Zend_Db_Table_Abstract{

	protected $_name = 'cart_recursos';

	public function getRecursos($recurso='')
    {
    	$select = $this->select();
    	
    	if($recurso){
    		$select->where("recurso = '". $recurso . "'");
    	}
    	
    	return $this->fetchAll($select);
    }
    
	public function getRecursosPai()
    {
    	$select = $this->select();
    	
    	$select->where("idPai = ?", 0);
    	
    	return $this->fetchAll($select);
    }
    
	public function getRecursosFilho($idPai)
    {
    	$select = $this->select();
    	
    	$select->where("idPai = ?", $idPai);
    	
    	return $this->fetchAll($select);
    }

}