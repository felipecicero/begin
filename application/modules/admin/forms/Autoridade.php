<?php

class Admin_Form_Autoridade extends Zend_Form
{

	public function init()
    {
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
		
    	$idAutoridade = new Zend_Form_Element_Hidden('idAutoridade');
		$idAutoridade -> clearDecorators();
		$idAutoridade -> addDecorators($decorator_default);
    	    		 
       	$nome = new Zend_Form_Element_Text('nome');
		$nome -> clearDecorators();
		$nome -> addDecorators($decorator_default);
    	$nome->setLabel("Nome:")
    		 ->setAttrib('size', '40');
    		
    	$cargo = new Zend_Form_Element_Text('cargo');
		$cargo -> clearDecorators();
		$cargo -> addDecorators($decorator_default);
    	$cargo->setLabel("Cargo:")
    		 ->setAttrib('size', '20');


    	$submit = new Zend_Form_Element_Submit('Salvar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($idAutoridade, $nome, $cargo, $submit));
    }
    
	public function setAsEditForm(Zend_Db_Table_Row $row){
        $this->populate($row->toArray());
        //$this->setAction(sprintf('editarcartorio/idCusta/%d', $row->idCusta));

        $this->getElement('idAutoridade');
        $this->getElement('nome');
        $this->getElement('Cargo');
        
        
        return $this;
    }


}

