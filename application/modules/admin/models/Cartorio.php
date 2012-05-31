<?php

class Cartorio extends Zend_Db_Table_Abstract{

	protected $_name = 'cap_cartorio';
	
	public function getCartorio()
    {
    	$select = $this->select();  
    	  	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('car' => 'cap_cartorio'), array('idCartorio', 'nome', 'codigo', 'telefone', 'site', 'email', 'nomefantasia', 'cnpj', 'conta', 'carteira', 'tabeliao', 'substituto', 'escrevente', 'notificacao', 'codigo_empresa', 'razao'));
    	
    	$select->joinInner(array('end' => 'cap_enderecos'), 'car.idEndereco = end.idEndereco', array('idEndereco', 'endereco' => 'rua', 'numero' => 'numero', 'complemento' => 'complemento', 'cep' => 'cep', 'bairro' => 'bairro'));
    	    	
    	$select->joinInner(array('cid' => 'cap_cidades'), 'cid.idCidade = end.idCidade', array('idCidade', 'cidade' => 'nome' ));
    	
    	$select->joinInner(array('est' => 'cap_estados'), 'cid.idEstado = est.idEstado', array('idEstado', 'uf' => 'sigla' ));
    	
    	$select->joinInner(array('age' => 'cap_agencias'), 'car.idAgencia = age.idAgencia', array('idAgencia', 'agencia'=>'codigo', 'descricao'));
    	
    	$select->joinInner(array('ban' => 'cap_bancos'), 'age.idBanco = ban.idBanco', array('idBanco', 'banco' => 'nome', 'numerobanco'=>'codigo'));
    	
    	$data = $this->fetchAll($select);
    	/*$sql = (string) $select;
    	print_r('<pre>');
		print_r($sql);
		print_r('</pre>');
		exit;*/
    	return $data->current();
    }

}

