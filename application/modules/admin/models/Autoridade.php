<?php

class Autoridade extends Zend_Db_Table_Abstract{

	protected $_name = 'cap_autoridades';

	public function findForSelect($id='')
    {
    	$select = $this->select();
    	$select->order('nome');
    	if($id){
    		$select->where('idAutoridade = ?', $id);
    	}
    	
    	return $this->fetchAll($select);
    }

}

