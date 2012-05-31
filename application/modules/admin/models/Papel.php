<?php

class Papel extends Zend_Db_Table_Abstract{

	protected $_name = 'cart_papeis';

	public function getPapeis($idPapel='')
    {
    	$select = $this->select();
    	if($idPapel){
    		$select->where('idPapel = ?', $idPapel);
    	}
    	return $this->fetchAll($select);
    }

}

