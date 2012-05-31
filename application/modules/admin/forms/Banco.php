<?php

class Admin_Form_Banco extends Zend_Form
{

	public function init(){
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
		
        //$this->setName('login');
        $idBanco = new Zend_Form_Element_Hidden('idBanco');
		$idBanco -> clearDecorators();
		$idBanco -> addDecorators($decorator_default);
 
        $banco = new Zend_Form_Element_Text('nome');
		$banco -> clearDecorators();
		$banco -> addDecorators($decorator_default);
        $banco -> setLabel('Nome:')
               -> setRequired(true);
              
        $codigo = new Zend_Form_Element_Text('codigo');
		$codigo -> clearDecorators();
		$codigo -> addDecorators($decorator_default);
        $codigo -> setLabel('Código:')
                -> setRequired(true);/*
                -> addValidator('Db_NoRecordExists', false,
                 array(
                     'table' => 'cap_bancos',
                     'field' => 'codigo'
                 )
             	);*/
 
        
 
        $submit = new Zend_Form_Element_Submit('Salvar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($idBanco, $codigo, $banco, $submit));
    }
    
	public function setAsEditForm(Zend_Db_Table_Row $row){
        $this->populate($row->toArray());
        $this->setAction(sprintf('bancos/idBanco/%d', $row->idBanco));

        $this->getElement('idBanco');
        $this->getElement('banco');
        $this->getElement('codigo');
            

        return $this;
    }


}

