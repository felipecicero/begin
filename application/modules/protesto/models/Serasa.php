<?php

class Serasa extends Zend_Db_Table_Abstract{

	protected $_name = 'cap_serasa';
	
	public function selectTitulos(){
		
		$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->group ( array ("data_serasa") );
    	
    	$select->order(array('data_serasa DESC'));
    	//$sql = (string) $select;    	
    	//print_r($sql);exit;    	
    	$data = $this->fetchAll($select);

    	return $data;
	}
	
	public function selectTitulosSerasa($date)
    {
    	//Selecione os titulos digitalizados
    	$select1 = $this->select();
    	
    	$select1->setIntegrityCheck(false);
    	
    	$select1->from(array('pro' => 'cap_protestos'), array());
    	
    	$select1->joinLeft(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array());
    	
    	$select1->joinInner(array('ser' => 'cap_serasa'), 'pro.idProtesto = ser.idProtesto', array('idProtesto', 'codigooperacao'));
    	
    	$select1->joinInner(array('td' => 'cap_titulos'), 'pro.idTitulo = td.idTitulo', array('vencimento' => 'datavencimentotitulo', 'numerotitulo' => 'numerotitulo', 'valor' => 'valortitulo'));
    	
    	$select1->joinInner(array('pes' => 'cap_pessoa'), 'td.idPessoa_devedor = pes.idPessoa', array('devedor' => 'nome'));
    	
    	$select1->joinInner(array('pesc' => 'cap_pessoa'), 'td.idPessoa_cedente = pesc.idPessoa', array('credor' => 'nome'));
    	
    	$select1->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('apontamento' => 'protocolo'));
    	
    	$select1->where('arq.tipo = 7 OR pro.idArquivo= 0'); //todos arquivos digitalizados
    	
    	$select1->where("ser.data_serasa = '$date'");
    	
    	//Selecione os titulos importados
    	$select2 = $this->select();
    	
    	$select2->setIntegrityCheck(false);
    	
    	$select2->from(array('pro' => 'cap_protestos'), array());
    	
    	$select2->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array());
    	
    	$select2->joinInner(array('ser' => 'cap_serasa'), 'pro.idProtesto = ser.idProtesto', array('idProtesto', 'codigooperacao'));
    	
    	$select2->joinInner(array('td' => 'cap_titulos_importados'), 'pro.idTitulo = td.idTitulo', array('vencimento' => 'datavencimentotitulo', 'numerotitulo' => 'numerotitulo', 'valor' => 'valortitulo', 'devedor' => 'nomedevedor', 'credor' => 'nomecedente', ));

    	$select2->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('apontamento' => 'protocolo'));
    	
    	$select2->where('arq.tipo <> 7'); //todos arquivos digitalizados
    	
    	$select2->where("ser.data_serasa = '$date'");
    	
    	//UNION com os dois selects
    	$select = $this->select()
    				 ->union(array($select1, $select2))
    				 ->order(array('devedor'));
    	
		/*$sql = (string) $select;    
    	print_r("<pre>");
    	print_r($sql);
    	print_r("</pre>");
    	exit;  */  				 
    				 
    	$data = $this->fetchAll($select);

    	return $data;
    }
    
	public function getTitulo($id){
		
		$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->order(array('data_serasa DESC'));
    	
    	$select->where('idProtesto = ?', $id);    	
    	//$sql = (string) $select;    	
    	//print_r($sql);exit;    	
    	$data = $this->fetchAll($select);

    	return $data;
	}

}

