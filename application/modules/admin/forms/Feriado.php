<?php

class Admin_Form_Feriado extends Zend_Form
{

	public function init(){
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
		
        //$this->setName('login');
        $idFeriado = new Zend_Form_Element_Hidden('idFeriado');
		$idFeriado -> clearDecorators();
		$idFeriado -> addDecorators($decorator_default);
 
        $validate = new Zend_Validate_Date(array('locale' => 'pt-Br'));
    	$date = new Zend_Form_Element_Text('date');
		$date -> clearDecorators();
		$date -> addDecorators($decorator_default);
    	$date -> setLabel("Data:")    	 
			  -> setAttrib('size', '20')
    		  -> setAttrib('maxlength', '10')
    		  -> setAttrib('onKeyDown', 'Mascara(this,mdata);')
    		  -> setAttrib('onKeyPress', 'Mascara(this,mdata);')
    		  -> setAttrib('onKeyUp', 'Mascara(this,mdata);')
    		  -> addValidator($validate)    				
    		  -> setRequired(true);
              
        $descricao = new Zend_Form_Element_Text('descricao');
		$descricao -> clearDecorators();
		$descricao -> addDecorators($decorator_default);
        $descricao -> setLabel('Descrição:')
                	-> setRequired(true);
               	
 
        
 
        $submit = new Zend_Form_Element_Submit('Salvar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($idFeriado, $date, $descricao, $submit));
    }
    
	public function setAsEditForm(Zend_Db_Table_Row $row){
        $this->populate($row->toArray());
        $this->setAction(sprintf('user/feriados/idFeriado/%d', $row->idFeriado));

        $this->getElement('idFeriado');
        $this->getElement('date');
        $this->getElement('descricao');
            

        return $this;
    }


}

