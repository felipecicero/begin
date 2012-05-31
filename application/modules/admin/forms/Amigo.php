<?php

class Admin_Form_Amigo extends Zend_Form
{

    public function init()
    {
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
		
        //$this->setName('login');
        $idAmigo = new Zend_Form_Element_Hidden('idAmigo');
		$idAmigo -> clearDecorators();
		$idAmigo -> addDecorators($decorator_default);
    	
        $nome = new Zend_Form_Element_Text('nome');
		$nome -> clearDecorators();
		$nome -> addDecorators($decorator_default);
        $nome -> setLabel('Nome:')
        	  -> setAttrib('size', '40')
              -> setRequired(true);

        $tipo = new Zend_Form_Element_Radio('tipo_documento');
		$tipo -> clearDecorators();
		$tipo -> addDecorators($decorator_default);
        $tipo -> setLabel('Tipo do documento:')
              -> addMultiOptions(array(
        		  			'1' => 'CNPJ',
       			 			'2' => 'CPF'
      			))
      			->setSeparator('')
      			->setValue('1');
                
        $validator_doc = new Hazel_Validate_Or();
		$validator_doc->addValidator(new Hazel_Validate_Cpf())
          		  	  ->addValidator(new Hazel_Validate_Cnpj()); 
        $documento = new Zend_Form_Element_Text('documento');
		$documento -> clearDecorators();
		$documento -> addDecorators($decorator_default);
        $documento->setLabel('CPF/CNPJ:')
              	  ->setRequired(true)
              	  ->addValidator($validator_doc);
              
        $telefone = new Zend_Form_Element_Text('telefone');
		$telefone -> clearDecorators();
		$telefone -> addDecorators($decorator_default);
        $telefone ->setLabel('Telefone:')
        		  -> setAttrib('size', '15');
         
        $celular = new Zend_Form_Element_Text('celular');
		$celular -> clearDecorators();
		$celular -> addDecorators($decorator_default);
        $celular ->setLabel('Celular:')
        		  -> setAttrib('size', '15');
             	  
        $email = new Zend_Form_Element_Text('email');
		$email -> clearDecorators();
		$email -> addDecorators($decorator_default);
        $email ->setLabel('e-mail:')
        		  -> setAttrib('size', '40');
 
        		  
		$cep = new Zend_Form_Element_Text('cep');
		$cep -> clearDecorators();
		$cep -> addDecorators($decorator_default);
    	$cep->setLabel("CEP:")
    		 ->setAttrib('size', '10');
    	
    	$endereco = new Zend_Form_Element_Text('endereco');
		$endereco -> clearDecorators();
		$endereco -> addDecorators($decorator_default);
    	$endereco->setLabel("Endereço:")
    		 ->setAttrib('size', '40');

    	$complemento = new Zend_Form_Element_Text('complemento');
		$complemento -> clearDecorators();
		$complemento -> addDecorators($decorator_default);
    	$complemento->setLabel("Complemento:")
    		 ->setAttrib('size', '20');
    		 
    	$bairro = new Zend_Form_Element_Text('bairro');
		$bairro -> clearDecorators();
		$bairro -> addDecorators($decorator_default);
    	$bairro->setLabel("Bairro:")
    		 ->setAttrib('size', '20');
    		 
    	$numero = new Zend_Form_Element_Text('numero');
		$numero -> clearDecorators();
		$numero -> addDecorators($decorator_default);
    	$numero->setLabel("Número:")
    		 ->setAttrib('size', '5');
    				 
			 
		$estado = new Zend_Form_Element_Select('idEstado');
		$estado -> clearDecorators();
		$estado -> addDecorators($decorator_default);
		$estado->setLabel('UF:');
		$model_estado = new Estado();
		$estado->addMultiOption('0', 'Selecione o Estado');
	    foreach ($model_estado->findForSelect() as $uf) {
	    	$estado->addMultiOption($uf->idEstado, $uf->sigla);
		} 
    		
    	$cidade = new Zend_Form_Element_Select('idCidade');
    	$cidade -> setLabel("Cidade:");
		$cidade -> clearDecorators();
		$cidade -> addDecorators($decorator_default)
    				    -> setRegisterInArrayValidator(false);        		  
        		  
        $observacoes = new Zend_Form_Element_Textarea('observacoes');
		$observacoes -> clearDecorators();
		$observacoes -> addDecorators($decorator_default);
        $observacoes ->setLabel('Observações:')
			         ->setAttrib('rows','5')
					 ->setAttrib('cols','40')
					 ->addFilter('StripTags');
        
 
        $submit = new Zend_Form_Element_Submit('Salvar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($idAmigo, $nome, $tipo, $documento, $telefone, $celular, $email, $cep, $endereco, $complemento, $bairro, $numero, $estado, $cidade, $observacoes, $submit));
    }

	public function setAsEditForm(Zend_Db_Table_Row $row){
        
	 	$model_cidade = new Cidade();
		foreach ($model_cidade->findForSelect($row['idEstado']) as $banc) {
	    	$this->idCidade->addMultiOption($banc->idCidade, $banc->nome);
		}
		$this->populate($row->toArray());
        //$this->setAction(sprintf('editaramigo/idAmigo/%d', $row->idAmigo));]
		
        $this->getElement('nome');
        $this->getElement('tipo_documento');
        $this->getElement('documento');
        $this->getElement('telefone');
        $this->getElement('celular');
        $this->getElement('email');
        $this->getElement('cep');
        $this->getElement('complemento');
        $this->getElement('bairro');
        $this->getElement('numero');
        $this->getElement('idEstado');
        $this->getElement('idCidade');
        $this->getElement('observacoes');


        return $this;
    }

}

