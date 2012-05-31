<?php

class Protesto_Form_Situacao extends Zend_Form
{

    public function init()
    {
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
		
        //$this->setName('login');

    	$codigo = new Zend_Form_Element_Text('codigo');
		$codigo -> clearDecorators();
		$codigo -> addDecorators($decorator_default);
        $codigo->setLabel('Código:')
              ->setRequired(true)
              ->addValidator('Db_NoRecordExists', false,
                 array(
                     'table' => 'cap_situacao',
                     'field' => 'codigo'
                 )
             	);
    	
        $descricao = new Zend_Form_Element_Text('descricao');
		$descricao -> clearDecorators();
		$descricao -> addDecorators($decorator_default);
        $descricao->setLabel('Descrição:')
              ->setRequired(true);
 
        
 
        $submit = new Zend_Form_Element_Submit('Salvar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($codigo, $descricao, $submit));    
    }


}

