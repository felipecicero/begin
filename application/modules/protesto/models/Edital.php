<?php

class Edital extends Zend_Db_Table_Abstract{

	protected $_name = 'cap_editais';
	
	public function selectEditais(){
		
		$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->group ( array ("data_edital") );
    	
    	$select->order(array('data_edital DESC'));
    	//$sql = (string) $select;    	
    	//print_r($sql);exit;    	
    	$data = $this->fetchAll($select);

    	return $data;
	}
	
	public function selectTitulosEdital($date)
    {
    	//Selecione os titulos digitalizados
    	$select1 = $this->select();
    	
    	$select1->setIntegrityCheck(false);
    	
    	$select1->from(array('pro' => 'cap_protestos'), array());
    	
    	$select1->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array());
    	
    	$select1->joinInner(array('edi' => 'cap_editais'), 'pro.idProtesto = edi.idProtesto', array('idProtesto'));
    	
    	$select1->joinInner(array('td' => 'cap_titulos'), 'pro.idTitulo = td.idTitulo', array('vencimento' => 'datavencimentotitulo', 'numerotitulo', 'valor' => 'valortitulo'));
    	
    	$select1->joinInner(array('pes' => 'cap_pessoa'), 'td.idPessoa_devedor = pes.idPessoa', array('devedor' => 'nome'));
    	
    	$select1->joinInner(array('pesc' => 'cap_pessoa'), 'td.idPessoa_cedente = pesc.idPessoa', array('credor' => 'nome'));
    	
    	$select1->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('apontamento' => 'protocolo'));
    	
    	$select1->joinLeft(array('amg' => 'cap_amigos'), 'amg.documento = pes.numeroidentificacao', array('docamigo' => 'documento'));
    	
    	$select1->where('arq.tipo = 7'); //todos arquivos digitalizados
    	
    	$select1->where("edi.data_edital = '$date'");
    	
    	//Selecione os titulos importados
    	$select2 = $this->select();
    	
    	$select2->setIntegrityCheck(false);
    	
    	$select2->from(array('pro' => 'cap_protestos'), array());
    	
    	$select2->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array());
    	
    	$select2->joinInner(array('edi' => 'cap_editais'), 'pro.idProtesto = edi.idProtesto', array('idProtesto'));
    	
    	$select2->joinInner(array('td' => 'cap_titulos_importados'), 'pro.idTitulo = td.idTitulo', array('vencimento' => 'datavencimentotitulo', 'numerotitulo', 'valor' => 'valortitulo', 'devedor' => 'nomedevedor', 'credor' => 'nomecedente', ));

    	$select2->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('apontamento' => 'protocolo'));
    	
    	$select2->joinLeft(array('amg' => 'cap_amigos'), 'amg.documento = td.numeroidentificacaodevedor', array('docamigo' => 'documento'));
    	
    	$select2->where('arq.tipo <> 7'); //todos arquivos digitalizados
    	
    	$select2->where("edi.data_edital = '$date'");
    	
    	//UNION com os dois selects
    	$select = $this->select()
    				 ->union(array($select1, $select2))
    				 ->order(array('devedor'));
    	
    	$data = $this->fetchAll($select);

    	return $data;
    }
	
	public function getEdital($id){
		
		$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->where('idProtesto = ?', $id);
    	    	
    	$data = $this->fetchAll($select);

    	return $data;
	}
}

