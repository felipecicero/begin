<?php

class Protesto_Form_Portador extends Zend_Form
{

	public function init()
    {	
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
	
   		$model_portador = new Portador();
	    $portador = new Zend_Form_Element_Select('idPortador');
		$portador -> clearDecorators();
		$portador -> addDecorators($decorator_default);
		$portador->setLabel('Instituição:');
        $portador->addMultiOption(0, "Selecione a Instiuição");
		//populando o select 
	    foreach ($model_portador->findForSelect() as $prot) {
	    	$portador->addMultiOption($prot->idPortador, $prot->nomeportador);
		}

        $submit = new Zend_Form_Element_Submit('Gerar');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));

        $this->addElements(array( $portador, $submit));
    }


}

