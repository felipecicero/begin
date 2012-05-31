<?php

class Livro extends Zend_Db_Table_Abstract{

	protected $_name = 'cap_livro';
	
	public function getLivros()
    {
    	$select = $this->select()
	      			   ->setIntegrityCheck(false); 
              		   
    	return $this->fetchAll($select);
    }

}

