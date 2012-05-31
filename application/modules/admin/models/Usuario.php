<?php

class Usuario extends Zend_Db_Table_Abstract{

	protected $_name = 'cap_usuarios';

	public function findForSelect()
    {
    	$select = $this->select();
    	
    	$select->order('nome');
    	
    	return $this->fetchAll($select);
    }

	public function getUsuario($idUsuario)
    {
    	$select = $this->select();
    	    	
    	$select->where("idUsuario = ?", $idUsuario);
    	
    	return $this->fetchAll($select)->Current();
    }
}

