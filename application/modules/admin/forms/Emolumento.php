<?php

class Admin_Form_Emolumento extends Zend_Form
{

	public function init(){
       
    	$idEmolumento = new Zend_Form_Element_Hidden('idEmolumento'); 
		
		$model_vigencia = new Vigencia();
        
		$idVigencia = new Zend_Form_Element_Select('idVigencia');
		$idVigencia->setLabel('Vigencia:')
				 ->setAttrib('disabled', 'disabled');
	    foreach ($model_vigencia->findForSelect() as $vigen) {
	    	$idVigencia->addMultiOption($vigen->idVigencia, $vigen->vigencia);
		}
		
		$emolumento = new Zend_Form_Element_Text('emolumento');
    	$emolumento->setLabel("Valor do Emolumento:")
    		 	  ->setAttrib('size', '30');

    	$valor_inicial = new Zend_Form_Element_Text('valor_inicial');
    	$valor_inicial->setLabel("Valor Inicial do Emolumento:")
    		 	->setAttrib('size', '10');
				
		$valor_final = new Zend_Form_Element_Text('valor_final');
    	$valor_final->setLabel("Valor Final do Emolumento:")
    		 	->setAttrib('size', '10');
    		 	      	
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Enviar')
               ->setAttrib('id', 'submitbutton');
 
        $this->addElements(array($idEmolumento, $idVigencia, $emolumento, $valor_inicial, $valor_final, $submit));
    }
    
	public function setAsEditForm(Zend_Db_Table_Row $row){
        $this->populate($row->toArray());
        $this->setAction(sprintf('editaremolumento/idEmolumento/%d', $row->idEmolumento));

        $this->getElement('idEmolumento');
		$this->getElement('idVigencia');
		$this->getElement('valor_inicial');
        $this->getElement('valor_final');
		$this->getElement('emolumento');
        
        return $this;
    }


}

