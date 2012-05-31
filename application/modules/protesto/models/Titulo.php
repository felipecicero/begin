<?php

class Titulo extends Zend_Db_Table_Abstract
{

	protected $_name = 'cap_titulos';
	
	public function selectTitulosTipo($idProtesto)
    {
    	//Selecione os titulos digitalizados
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('pro' => 'cap_protestos'), array());
    	
    	$select->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo'));

    	$data = $this->fetchAll($select);
    	
    	return $data;
    }
}
