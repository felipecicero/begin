<?php

class Cidade extends Zend_Db_Table_Abstract
{

	protected $_name = 'cap_cidades';
	
	public function findForSelect($id='')
    {
    	header('Content-Type: text/html; charset=ISO-8859-1');
    	$select = $this->select();
    	$select->order('nome');  
		
    	if($id){
    		$select->where('idEstado = ?', $id);
    	}
    	
    	$data = $this->fetchAll($select);
    	
    	return $data;
    }
    
	public function getPracaPag()
    {
    	header('Content-Type: text/html; charset=ISO-8859-1');
    	$select = $this->select();
    	
    	$select->from(array('car' => 'cap_cartorio'), array());
    	
    	$select->joinInner(array('end' => 'cap_enderecos'), 'car.idEndereco = end.idEndereco', array());
    	
    	$select->joinInner(array('cid' => 'cap_cidades'), 'end.idCidade = cid.idCidade', array('idCidade', 'idEstado',  'nome'));
    	
    	
    	$data = $this->fetchAll($select);
    	
    	return $data;
    }

}

