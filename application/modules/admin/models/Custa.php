<?php

class Custa extends Zend_Db_Table_Abstract{
//class Admin_Model_Custa{
	protected $_name = 'cap_custas';

	public function getCustas($idProtesto, $valorTitulo){
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('pro' => 'cap_protestos'), array('idProtesto'));
    	
    	$select->joinInner(array('vig' => 'cap_vigencias'), 'pro.idVigencia = vig.idVigencia', array());

    	$select->joinInner(array('cus' => 'cap_custas'), 'vig.idVigencia = cus.idVigencia', array('nome', 'valor'));
    	//pega apenas o emolumento correspondente
    	$select->joinLeft(array('emo' => 'cap_emolumentos'), 'vig.idVigencia = emo.idVigencia', array('emolumento'));
    	
    	$select->where('idProtesto = ?', $idProtesto);
    	
    	$select->where('valor_inicial <= ?', $valorTitulo);
    	
    	$select->where('valor_final >= ?', $valorTitulo);
    	    	
    	/*$sql = (string) $select;
    	print_r("<pre>");
	    print_r($sql);
	    print_r("</pre>");exit;*/
    	
    	$data = $this->fetchAll($select);
    	
    	return $data;
    }
    
	public function getCustaByName($nome)
    {
    	$select = $this->select();
    	
    	$select->where('nome = "' . $nome . '"');
    	
    	$data = $this->fetchAll($select);
    	
    	if(count($data) > 0){
    		$valor = number_format($data->Current()->valor, 2, ",", ".");
    		
    		return $valor;
    	}
    	//$sql = (string) $select;
    	//print_r($sql);exit;
    	
    	return "0,00";
    }

	public function getCustas_certidao(){
    	
		$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('cus' => 'cap_custas'), array('nome', 'valor'));
    	
    	$select->joinInner(array('vig' => 'cap_vigencias'), '', array());

    	$select->where('vig.idVigencia = (SELECT MAX(idVigencia) FROM cap_vigencias)');
    	
    	$select->where("cus.nome = 'taxa judiciaria' OR cus.nome = 'certidao' OR cus.nome = 'funcivil'");
    	    	
    	//$sql = (string) $select;
    	//print_r($sql);exit;
    	
    	$data = $this->fetchAll($select);
    	
    	return $data;
    }
	
	public function getCustas_certidaop(){
    	
		$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('cus' => 'cap_custas'), array('nome', 'valor'));
    	
    	$select->joinInner(array('vig' => 'cap_vigencias'), '', array());

    	$select->where('vig.idVigencia = (SELECT MAX(idVigencia) FROM cap_vigencias)');
    	
    	$select->where("cus.nome = 'taxa judiciaria' OR cus.nome = 'certidao'");
    	    	
    	//$sql = (string) $select;
    	//print_r($sql);exit;
    	
    	$data = $this->fetchAll($select);
    	
    	return $data;
    }

	public function getCustasPT($idProtesto, $valorTitulo){//Pega as custa excluindo algumas delas, para apresentar na tabela
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('pro' => 'cap_protestos'), array('idProtesto'));
    	
    	$select->joinInner(array('vig' => 'cap_vigencias'), 'pro.idVigencia = vig.idVigencia', array());

    	$select->joinInner(array('cus' => 'cap_custas'), 'vig.idVigencia = cus.idVigencia', array('nome', 'valor'));
    	//pega apenas o emolumento correspondente
    	$select->joinLeft(array('emo' => 'cap_emolumentos'), 'vig.idVigencia = emo.idVigencia', array('emolumento'));
    	
    	$select->where("cus.nome != 'cancelamento' ");
    	
    	$select->where("cus.nome != 'certidao' ");
    	
    	$select->where('idProtesto = ?', $idProtesto);
    	
    	$select->where('valor_inicial <= ?', $valorTitulo);
    	
    	$select->where('valor_final >= ?', $valorTitulo);
    	    	
    	/*$sql = (string) $select;
    	print_r("<pre>");
	    print_r($sql);
	    print_r("</pre>");exit;*/
    	
    	$data = $this->fetchAll($select);
    	
    	return $data;
    }

}

