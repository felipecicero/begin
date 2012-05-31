<?php

class Protesto_Form_Busca extends Zend_Form
{

	public function init()
    {
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
		
 
        $tipo = new Zend_Form_Element_Radio('tipo');
		$tipo -> clearDecorators();
		$tipo -> addDecorators($decorator_default);
        $tipo->setLabel('Tipo do documento:')
             ->addMultiOptions(array(
        		'1' => 'CNPJ',
       			 '2' => 'CPF'
      			))
      			->setSeparator('')
      			->setValue('1')
                ->setRequired(true);
              
    	$validator_doc = new Hazel_Validate_Or();
		$validator_doc->addValidator(new Hazel_Validate_Cpf())
          		  	  ->addValidator(new Hazel_Validate_Cnpj());   	
    	$documento = new Zend_Form_Element_Text('documento');
		$documento -> clearDecorators();
		$documento -> addDecorators($decorator_default);
    	$documento->setLabel("Documento:")
    		 			  ->setAttrib('size', '18') 
    		 			  ->addValidator($validator_doc)
    		 			  ->setRequired(true);
    		 			
    	$nome = new Zend_Form_Element_Text('nome');
		$nome -> clearDecorators();
		$nome -> addDecorators($decorator_default);
    	$nome -> setLabel("Nome:")
     		  -> setAttrib('size', '25') 
     		  -> addValidator($validator_doc)
     		  -> setRequired(true);
 
        $submit = new Zend_Form_Element_Submit('Enviar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($tipo, $documento, $nome, $submit));
    }

}

