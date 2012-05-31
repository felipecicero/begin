<?php

class Feriado extends Zend_Db_Table_Abstract{

	protected $_name = 'cap_feriados';

	public function getFeriados(){ // pega os feriados dos ultimos 10 dias
    	
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('fer' => 'cap_feriados'), array('idFeriado', 'date', 'descricao', 'dia' => 'DAYNAME(date)'));
    	
    	$select->where("TO_DAYS(NOW()) - TO_DAYS(date) <= 10");
    	
    	$select->where("TO_DAYS(NOW()) - TO_DAYS(date) > 0");
    	//$sql = (string) $select;    
    	//print_r($sql);exit;
    	$data = $this->fetchAll($select);
    	
    	return $data;
    }
    
	public function getFeriadosbyDate($date){ // pega os feriados dos ultimos 10 dias
    	
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->where("date = '" . $date . "'");
    	//$sql = (string) $select;    
    	//print_r($sql);exit;
    	$data = $this->fetchAll($select);
    	
    	return $data;
    }
}

