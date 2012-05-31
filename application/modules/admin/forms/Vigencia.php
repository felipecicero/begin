<?php

class Admin_Form_Vigencia extends Zend_Form
{

    public function init(){
       
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
	
    	$idVigencia = new Zend_Form_Element_Hidden('idVigencia'); 
		$idVigencia -> clearDecorators();
		$idVigencia -> addDecorators($decorator_default);
		
		$validate = new Zend_Validate_Date(array('locale' => 'pt-Br'));
    	$vigencia = new Zend_Form_Element_Text('vigencia');
		$vigencia -> clearDecorators();
		$vigencia -> addDecorators($decorator_default);
    	$vigencia->setLabel("Início da Vigência:")    	 
    				->setAttrib('size', '10')
    				->setAttrib('maxlength', '10')
    				->addValidator($validate);//valida a data
    		 	      	
        $submit = new Zend_Form_Element_Submit('Salvar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($idVigencia, $vigencia, $submit));
    }
    
	/*public function setAsEditForm(Zend_Db_Table_Row $row){
        $this->populate($row->toArray());
        //$this->setAction(sprintf('editarcusta/idCusta/%d', $row->idCusta));

        $this->getElement('idVigencia');
        $this->getElement('vigencia');
        
        return $this;
    }*/


}

