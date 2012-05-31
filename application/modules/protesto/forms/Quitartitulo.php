<?php

class Protesto_Form_Quitartitulo extends Zend_Form
{

	public function init(){		
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
		
    	$idTitulo = new Zend_Form_Element_Hidden('idTitulo');
		$idTitulo -> clearDecorators();
		$idTitulo -> addDecorators($decorator_default);
		
		$idProtesto = new Zend_Form_Element_Hidden('idProtesto');
		$idProtesto -> clearDecorators();
		$idProtesto -> addDecorators($decorator_default);
		
    	$tipo = new Zend_Form_Element_Hidden('tipo');  
		$tipo -> clearDecorators();
		$tipo -> addDecorators($decorator_default);
		
        $protocolo = new Zend_Form_Element_Text('protocolo');
		$protocolo -> clearDecorators();
		$protocolo -> addDecorators($decorator_default);
    	$protocolo->setLabel("Protocolo:")
    			  ->setAttrib('disabled', 'disabled')
    		 	  ->setAttrib('size', '10');

    	$credor = new Zend_Form_Element_Text('nomecedente');
		$credor -> clearDecorators();
		$credor -> addDecorators($decorator_default);	
    	$credor->setLabel("Cedente:")
    			->setAttrib('disabled', 'disabled')
    		 	->setAttrib('size', '40');
    		 	  
    	$devedor = new Zend_Form_Element_Text('nome');
		$devedor -> clearDecorators();
		$devedor -> addDecorators($decorator_default);
    	$devedor->setLabel("Devedor:")
    			->setAttrib('disabled', 'disabled')
    		 	->setAttrib('size', '40');
    		 	  
    	$documento = new Zend_Form_Element_Text('numeroidentificacao');
		$documento -> clearDecorators();
		$documento -> addDecorators($decorator_default);
    	$documento->setLabel("CPF/CNPJ:")
    			  ->setAttrib('disabled', 'disabled')
    		 	  ->setAttrib('size', '15');
    		 	  
    	$especietitulo = new Zend_Form_Element_Text('codigo');
		$especietitulo -> clearDecorators();
		$especietitulo -> addDecorators($decorator_default);
    	$especietitulo->setLabel("Espécie do Título:")
    				  ->setAttrib('disabled', 'disabled')
    		 	  	  ->setAttrib('size', '30');
    		 	  	  
    	$valortitulo = new Zend_Form_Element_Text('valortitulo');
		$valortitulo -> clearDecorators();
		$valortitulo -> addDecorators($decorator_default);
    	$valortitulo->setLabel("Valor:")
    		 	  	->setAttrib('size', '30');
    	
    	
        $submit = new Zend_Form_Element_Submit('Quitar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($idTitulo, $idProtesto, $tipo, $protocolo, $credor, $devedor, $documento, $especietitulo, $valortitulo, $submit));
    }
    
	public function setAsEditForm(Zend_Db_Table_Row $row){
        //print_r($row);exit;
		$this->populate($row->toArray());
        $this->setAction(sprintf('pagamento/quitartitulo/idProtesto/%d', $row->idProtesto));

        $this->getElement('idTitulo');
        $this->getElement('idProtesto');
        $this->getElement('protocolo');
        $this->getElement('nomecedente');
        $this->getElement('nome');
        $this->getElement('numeroidentificacao');
        $this->getElement('codigo');

        return $this;
    }


}

