<?php

class Protesto_Form_Inteiroteor extends Zend_Form
{

	public function init()
    {
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
		
 	 			
    	$protocolo = new Zend_Form_Element_Text('protocolo');
		$protocolo -> clearDecorators();
		$protocolo -> addDecorators($decorator_default);
    	$protocolo -> setLabel("Apontamento:")
     		  -> setAttrib('size', '25')
     		  -> setRequired(true);
 
        $submit = new Zend_Form_Element_Submit('Enviar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($protocolo, $submit));
    }


}

