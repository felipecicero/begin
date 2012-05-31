<?php

class Permissao extends Zend_Db_Table_Abstract{

	protected $_name = 'cart_permissoes';

	public function getPermissoes($idPapel='', $idRecurso='')
    {
    	$select = $this->select();
    	
    	
    	if($idPapel){
    		$select->where('idPapel = ?', $idPapel);
    	}
    	
    	if($idRecurso){    		
    		$select->where('idRecurso = ?', $idRecurso);
    	}
    	
    	/*$sql = (string) $select;
    	print_r('<pre>');
		print_r($sql);
		print_r('</pre>');
		exit;*/
    	
    	return $this->fetchAll($select);
    }

}