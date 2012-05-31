<?php

class Abrangencia extends Zend_Db_Table_Abstract
{

	protected $_name = 'cap_abrangencia';

	public function findCeps()
    {
    	$select = $this->select();   	
    	return $this->fetchAll($select);
    }
    
	public function getAbrangencias()
    {
    	$select = $this->select(); 

    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('abr' => 'cap_abrangencia'), array('idFaixacep','inicio', 'limite'));
    	
    	$select->joinInner(array('cid' => 'cap_cidades'), 'cid.idCidade = abr.idCidade', array('idCidade', 'cidade' => 'nome' ));
    	
    	
    	return $this->fetchAll($select);
    }
}

