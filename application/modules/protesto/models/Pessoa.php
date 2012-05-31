<?php

class Pessoa extends Zend_Db_Table_Abstract
{

	protected $_name = 'cap_pessoa';
	
	public function findByDocumento($numdoc){
    	
		$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('pes' => 'cap_pessoa'), array('idPessoa',  'nome', 'numeroidentificacao', 'idEndereco', 'observacoes'));

    	$select->joinInner(array('end' => 'cap_enderecos'), 'pes.idEndereco = end.idEndereco', array('endereco' => 'rua', 'numero', 'complemento', 'cep', 'bairro'));
    	    	
    	$select->joinInner(array('cid' => 'cap_cidades'), 'end.idCidade = cid.idCidade', array('cidade' => 'idCidade' ));
    	
    	$select->joinInner(array('est' => 'cap_estados'), 'est.idEstado = cid.idEstado', array('estado' => 'idEstado' ));
    	
    	$select->where("numeroidentificacao = '" . $numdoc . "'");
    	
		$sql = (string) $select;    
    	//print_r($sql);exit;    				   
    				   
    	return ($this->fetchAll($select));
    		
    }

}

