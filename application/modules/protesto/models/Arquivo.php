<?php

class Arquivo extends Zend_Db_Table_Abstract{
   
    protected $_name = 'cap_arquivos';
    
	public function selectArquivos($tipo='')
    {
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('arq' => 'cap_arquivos'), array('idArquivo', 'idUsuario', 'arquivo', 'tipo', 'data_envio', 'portador'=>'SUBSTRING(arquivo, 2, 3)'));
    	    	
    	$select->joinInner(array('usu' => 'cap_usuarios'), 'usu.idUsuario = arq.idUsuario', array('nome'));
    	
    	$select->joinLeft(array('por' => 'cap_portadores'), 'SUBSTRING(arq.arquivo, 2, 3) = por.numerocodigoportador', array('remetente'=>'nomeportador'));
    	    	
    	$select->order('data_envio DESC');
    	
    	if($tipo){
    		$select->where('arq.tipo = ?', $tipo);
    	}
    	//$sql = (string) $select;
    	//print_r($sql);exit;    	
    	return $this->fetchAll($select);
    }
    
	public function selectArquivosImportados()
    {
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('arq' => 'cap_arquivos'), array('idArquivo', 'idUsuario', 'arquivo', 'tipo', 'data_envio', 'portador'=>'SUBSTRING(arquivo, 2, 3)'));
    	    	
    	$select->joinInner(array('usu' => 'cap_usuarios'), 'usu.idUsuario = arq.idUsuario', array('nome'));
    	
    	$select->joinLeft(array('por' => 'cap_portadores'), 'SUBSTRING(arq.arquivo, 2, 3) = por.numerocodigoportador', array('remetente'=>'nomeportador'));
    	    	
    	$select->order('data_envio DESC');
    	
    	$select->where('arq.tipo = 1 OR arq.tipo = 2 OR arq.tipo = 3');
    	
    	//$sql = (string) $select;
    	//print_r($sql);exit;    	
    	return $this->fetchAll($select);
    }
    
	public function selectArquivosExportados()
    {
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('arq' => 'cap_arquivos'), array('idArquivo', 'idUsuario', 'arquivo', 'tipo', 'data_envio', 'portador'=>'SUBSTRING(arquivo, 2, 3)'));
    	    	
    	$select->joinInner(array('usu' => 'cap_usuarios'), 'usu.idUsuario = arq.idUsuario', array('nome'));
    	
    	$select->joinLeft(array('por' => 'cap_portadores'), 'SUBSTRING(arq.arquivo, 2, 3) = por.numerocodigoportador', array('remetente'=>'nomeportador'));
    	    	
    	$select->order('data_envio DESC');
    	
    	$select->where('arq.tipo <> 1 AND arq.tipo <> 2 AND arq.tipo <> 3');
    	
    	//$sql = (string) $select;
    	//print_r($sql);exit;    	
    	return $this->fetchAll($select);
    }
    
	public function selectArquivosById($id)
    {
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('arq' => 'cap_arquivos'), array('idArquivo', 'idUsuario', 'arquivo', 'tipo', 'data_envio'));

    	$select->where('idArquivo = ?', $id);
    	
    	$data = $this->fetchAll($select);
    	
    	return $data->Current();
    }

}


