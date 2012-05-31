<?php

class Admin_Form_Custa extends Zend_Form
{

	public function init(){       
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
    	
		$idCusta = new Zend_Form_Element_Hidden('idCusta'); 
		$idCusta -> clearDecorators();
		$idCusta -> addDecorators($decorator_default);
		
        $nome = new Zend_Form_Element_Text('nome');
		$nome -> clearDecorators();
		$nome -> addDecorators($decorator_default);
    	$nome->setLabel("Nome da Custa:")
    		 	  ->setAttrib('size', '30');

    	$valor = new Zend_Form_Element_Text('valor');
		$valor -> clearDecorators();
		$valor -> addDecorators($decorator_default);
    	$valor->setLabel("Valor da Custa:")
    		 	->setAttrib('size', '10');
    		 	      	
        $submit = new Zend_Form_Element_Submit('Salvar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($idCusta, $nome, $valor, $submit));
    }
    
	public function setAsEditForm(Zend_Db_Table_Row $row){
        $this->populate($row->toArray());
        $this->setAction(sprintf('editarcusta/idCusta/%d', $row->idCusta));

        $this->getElement('idCusta');
        $this->getElement('nome');
        $this->getElement('valor');
        
        return $this;
    }


}

