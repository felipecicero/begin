<?php

class Admin_Form_Abrangencia extends Zend_Form
{

    public function init()
    {
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
    	
		$idFaixacep = new Zend_Form_Element_Hidden('idFaixacep');
		$idFaixacep -> clearDecorators();
		$idFaixacep -> addDecorators($decorator_default);
		
    	$model_cidade = new Cidade();
    	$model_cartorio = new Cartorio();
    	$data_cartorio = $model_cartorio->getCartorio();

	    $cidade = new Zend_Form_Element_Select('idCidade');
		$cidade -> clearDecorators();
		$cidade -> addDecorators($decorator_default);
		$cidade->setLabel('Comarca:')
				->setRequired(true);
        //populando o select 
	    foreach ($model_cidade->findForSelect($data_cartorio->idEstado) as $prot) {
	    	$cidade->addMultiOption($prot->idCidade, $prot->nome);
		}
    	
        $faixaInicial = new Zend_Form_Element_Text('inicio');
		$faixaInicial -> clearDecorators();
		$faixaInicial -> addDecorators($decorator_default);
        $faixaInicial -> setLabel('Faixa Inicial:')
              		  -> setRequired(true);
              
        $faixalimite = new Zend_Form_Element_Text('limite');
		$faixalimite -> clearDecorators();
		$faixalimite -> addDecorators($decorator_default);
        $faixalimite -> setLabel('Faixa Limite:');
 
        
 
        $submit = new Zend_Form_Element_Submit('Salvar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($idFaixacep, $cidade, $faixaInicial, $faixalimite, $submit));
    }
    
	public function setAsEditForm(Zend_Db_Table_Row $row){
        $this->populate($row->toArray());
        //$this->setAction(sprintf('agencias/idBanco/%d', $row->idBanco));

        $this->getElement('idFaixacep');
        $this->getElement('idCidade');
        $this->getElement('inicio');
        $this->getElement('limite');

        return $this;
    }

}

