<?php

class Admin_Form_Agencia extends Zend_Form
{

	public function init(){
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
        //$this->setName('login');
 
		$idAgencia = new Zend_Form_Element_Hidden('idAgencia');
		$idAgencia -> clearDecorators();
		$idAgencia -> addDecorators($decorator_default);
		
		$model_banco = new Banco();
	    $banco = new Zend_Form_Element_Select('idBanco');
		$banco -> clearDecorators();
		$banco -> addDecorators($decorator_default);
		$banco ->setLabel('Banco:')		         
               ->setRequired(true);
        //populando o select    	
	    foreach ($model_banco->findForSelect() as $banc) {
	    	$banco->addMultiOption($banc->idBanco, $banc->codigo . " - " .$banc->nome);
		}
              
        $codigo = new Zend_Form_Element_Text('codigo');
		$codigo -> clearDecorators();
		$codigo -> addDecorators($decorator_default);
        $codigo ->setLabel('Código:')
                ->setRequired(true);
         
        $descricao = new Zend_Form_Element_Text('descricao');
		$descricao -> clearDecorators();
		$descricao -> addDecorators($decorator_default);
        $descricao ->setLabel('Descrição:')
                   ->setRequired(true);        
 
        $submit = new Zend_Form_Element_Submit('Salvar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($idAgencia, $banco, $codigo, $descricao, $submit));
    }
    
	public function setAsEditForm(Zend_Db_Table_Row $row){
        $this->populate($row->toArray());
        //$this->setAction(sprintf('agencias/idBanco/%d', $row->idBanco));

        $this->getElement('idAgencia');
        $this->getElement('idBanco');
        $this->getElement('codigo');
        $this->getElement('descricao');
            

        return $this;
    }


}

