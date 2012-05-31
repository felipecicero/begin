<?php

class Protesto_Form_Especietitulo extends Zend_Form
{

    public function init()
    {
        //$this->setName('login');
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
              
        $codigo = new Zend_Form_Element_Text('codigo');
		$codigo -> clearDecorators();
		$codigo -> addDecorators($decorator_default);
        $codigo->setLabel('Código:')
              ->setRequired(true)
              ->addValidator('Db_NoRecordExists', false,
                 array(
                     'table' => 'cap_especietitulos',
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

