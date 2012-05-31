<?php

class Cabecalho extends Zend_Db_Table_Abstract
{

	protected $_name = 'cap_cabecalhos';

	public function getCabecalho($id){
		
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('cab' => 'cap_cabecalhos'), array('idCabecalho', 'idregistro', 'datamovimento', 'idtransacao_remetente', 'idtransacao_destinatario', 'idtransacao_tipo', 'numerosequencialremessa', 'quantidaderegistrosremessa', 'quantidadetitulosremessa', 'quantidadeindicacoesremessa', 'quantidadeoriginaisremessa', 'idagenciacentralizadora', 'versaolayout', 'codigomunicipiopracapagamento', 'numerosequencialarquivo' ));

    	$select->joinInner(array('por' => 'cap_portadores'), 'cab.idPortador = por.idPortador', array('idPortador', 'numerocodigoportador', 'nomeportador'));
    	
    	$select->where('idCabecalho = ?', $id);
    	
    	//$sql = (string) $select;    
    	//print_r($sql);exit;
    	
    	$data = $this->fetchAll($select);
    	
    	return $data->Current();
    	
    }
}

