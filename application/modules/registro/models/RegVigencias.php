<?php

class RegVigencias extends Zend_Db_Table_Abstract{

	protected $_name = 'car_vigencia';
	
	public function getLastVigencia()
    {
    	$selectx = $this->select()    	
    					->from(array('vig' => 'car_vigencia'), array('MAX(vigencia)'));
    	$sql = (string) $selectx;
    	
    	$select = $this->select();
    	$select->where('vigencia = (' . $selectx . ')');
    	    	
    	$idVigencia = $this->fetchAll($select); 
    	
    	return $idVigencia->Current();
    }

}

