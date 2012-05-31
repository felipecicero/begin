<?php

class Rodape extends Zend_Db_Table_Abstract
{

	protected $_name = 'cap_rodape';

	public function getRodape($idCabecalho)
    {
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);

    	$select->from(array('rod' => 'cap_rodape'), array('idRodape', 'idregistro', 'somatorioseguranca_quantidade', 'somatorioseguranca_valor', 'numerosequencialarquivo'));

    	$select->joinInner(array('cab' => 'cap_cabecalhos'),  'rod.idCabecalho = cab.idCabecalho', array('datamovimento',));
    	
    	$select->joinInner(array('por' => 'cap_portadores'), 'cab.idPortador = por.idPortador', array('idPortador', 'numerocodigoportador', 'nomeportador'));
    	
    	$select->where('rod.idCabecalho = ?', $idCabecalho);
    	
    	//$sql = (string) $select;    
    	//print_r($sql);exit;
    	
    	$data = $this->fetchAll($select);
    	
    	return $data->Current();
    }
}

