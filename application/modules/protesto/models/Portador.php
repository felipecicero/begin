<?php

class Portador extends Zend_Db_Table_Abstract
{

	protected $_name = 'cap_portadores';

	public function findForSelect($id='')
    {
    	$select = $this->select();
    	$select->order('nomeportador');
    	
    	if($id){
    		$select->where('idPortador = ?', $id);
    		$data = $this->fetchAll($select)->Current();
    		return $data;
    	}
    	
    	return $this->fetchAll($select);
    }
}

