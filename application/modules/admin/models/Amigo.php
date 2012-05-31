<?php

class Amigo extends Zend_Db_Table_Abstract{

	protected $_name = 'cap_amigos';

	public function findForSelect()
    {
    	$select = $this->select();
    	$select->order('nome');
    	return $this->fetchAll($select);
    }
    
	public function getAmigo($id='')
    {
    	
    	$select = $this->select();  
    	  	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('ami' => 'cap_amigos'), array('*'));
    	
    	$select->joinInner(array('end' => 'cap_enderecos'), 'ami.idEndereco = end.idEndereco', array('idEndereco', 'endereco' => 'rua', 'numero' => 'numero', 'complemento' => 'complemento', 'cep' => 'cep', 'bairro' => 'bairro'));
    	    	
    	$select->joinInner(array('cid' => 'cap_cidades'), 'cid.idCidade = end.idCidade', array('idCidade', 'cidade' => 'nome' ));
    	
    	$select->joinInner(array('est' => 'cap_estados'), 'cid.idEstado = est.idEstado', array('idEstado', 'uf' => 'sigla' ));
    	
    	if($id){    		
    		$select->where('idAmigo = ?', $id);
    	}
    	/*$sql = (string) $select;
    	print_r('<pre>');
		print_r($sql);
		print_r('</pre>');
		exit;*/
    	return $this->fetchAll($select);
    }

}

