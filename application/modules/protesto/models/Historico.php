<?php

class Historico extends Zend_Db_Table_Abstract
{

	protected $_name = 'cap_historico';

	public function getHistorico($idProtesto){
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->where('idProtesto = ?', $idProtesto);
    	
    	//$sql = (string) $select;    
    	//print_r($sql);exit;	
    	
    	$data = $this->fetchAll($select);
    	    	
    	return ($data);
    }
    
	public function selectTitulos($idSituacao = 21)
    {
    	//Selecione os titulos digitalizados
    	$select1 = $this->select();
    	
    	$select1->setIntegrityCheck(false);
    	
    	$select1->from(array('his' => 'cap_historico'), array('data_historico'));
    	
    	$select1->joinInner(array('pro' => 'cap_protestos'), 'his.idProtesto = pro.idProtesto', array('idProtesto',  'data_entrada'));
    	
    	$select1->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo'));
    	
    	$select1->joinInner(array('td' => 'cap_titulos'), 'pro.idTitulo = td.idTitulo', array());
    	
    	$select1->joinInner(array('pes' => 'cap_pessoa'), 'td.idPessoa_devedor = pes.idPessoa', array('nome', 'numeroidentificacao', 'tipoidentificacao' => 'tipo_identificacao'));
    	
    	$select1->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo'));
    	
    	$select1->where('arq.tipo = 7');
    	    	    	
    	$select1->where('his.idSituacao = ?', $idSituacao);
    	
    	$select1->where("his.data_historico >= '" . date('Y-m-d') . "'");
    	
    	
    	//Selecione os titulos importados
    	$select2 = $this->select();
    	
    	$select2->setIntegrityCheck(false);
    	
    	$select2->from(array('his' => 'cap_historico'), array('data_historico'));
    	
    	$select2->joinInner(array('pro' => 'cap_protestos'), 'his.idProtesto = pro.idProtesto', array('idProtesto',  'data_entrada'));
    	
    	$select2->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo'));
    	
    	$select2->joinInner(array('td' => 'cap_titulos_importados'), 'pro.idTitulo = td.idTitulo', array('nome' => 'nomedevedor', 'numeroidentificacao'=>'numeroidentificacaodevedor', 'tipoidentificacao' => 'tipoidentificacaodevedor'));
    	
    	$select2->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo'));
    	
    	$select2->where('arq.tipo <> 7');
    	    	    	    	
    	$select2->where('his.idSituacao = ?', $idSituacao);
    	
    	$select2->where("his.data_historico >= '" . date('Y-m-d') . "'");
    	
    	
    	//UNION com os dois selects
    	$select = $this -> select()
    				    -> union(array($select1, $select2));
    	
    	
    	    	
    	//$sql = (string) $select;    
    	//print_r($sql);exit;
    	
    	return $select;
    }
    
    public function selectRetorno($protocolo)
	{
		
		$select1 = $this->select();
    	
    	$select1->setIntegrityCheck(false);
    	
    	$select1->from(array('his' => 'cap_historico'), array('idSituacao', 'idProtesto'));
		
		$select1->where('his.idSituacao = 22');
		
		$select1->joinInner(array('pro' => 'cap_protestos'), 'pro.idProtesto = his.idProtesto', array('idArquivo'));
		
		$select1->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo'));
		
		$select1->joinInner(array('tit' => 'cap_titulos'), 'his.idTitulo = tit.idTitulo', array('idTitulo'));
		
		$select1->where('tit.idProtocolo = ?', $protocolo);
		
		$select2 = $this->select();
    	
    	$select2->setIntegrityCheck(false);
    	
    	$select2->from(array('his' => 'cap_historico'), array('idSituacao', 'idProtesto'));
		
		$select2->where('his.idSituacao = 22');
		
		$select2->joinInner(array('pro' => 'cap_protestos'), 'pro.idProtesto = his.idProtesto', array('idArquivo'));
		
		$select2->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo'));
		
		$select2->joinInner(array('tii' => 'cap_titulos_importados'), 'his.idTitulo = tii.idTitulo', array('idTitulo'));
    	
		$select2->where('tii.idProtocolo = ?', $protocolo);
		
		
		$select = $this -> select()
    				    -> union(array($select1, $select2));
						
		//$sql = (string) $select;    
    	//print_r($sql);exit;
		
		return $this->fetchAll($select);
	}  
    
    
}

