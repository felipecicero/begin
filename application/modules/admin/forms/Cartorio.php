<?php

class Admin_Form_Cartorio extends Zend_Form
{

    public function init()
    {
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
		
		$idEndereco = new Zend_Form_Element_Hidden('idEndereco');
		$idEndereco -> clearDecorators();
		$idEndereco -> addDecorators($decorator_default);
		
       	$nome = new Zend_Form_Element_Text('nome');
		$nome -> clearDecorators();
		$nome -> addDecorators($decorator_default);
    	$nome -> setLabel("Nome:")
    		  -> setAttrib('size', '40');
    		
    	$nomefantasia = new Zend_Form_Element_Text('nomefantasia');
		$nomefantasia -> clearDecorators();
		$nomefantasia -> addDecorators($decorator_default);
    	$nomefantasia -> setLabel("Nome Fantasia:")
    				  -> setAttrib('size', '20');
    	
		$tabeliao = new Zend_Form_Element_Text('tabeliao');
		$tabeliao -> clearDecorators();
		$tabeliao -> addDecorators($decorator_default);
    	$tabeliao -> setLabel("Oficial/Tabelião:")
    			  -> setAttrib('size', '20');
    			  
    	$substituto = new Zend_Form_Element_Text('substituto');
		$substituto -> clearDecorators();
		$substituto -> addDecorators($decorator_default);
    	$substituto -> setLabel("Suboficial/Substituto:")
    			    -> setAttrib('size', '20');
    			   
    	$escrevente = new Zend_Form_Element_Text('escrevente');
		$escrevente -> clearDecorators();
		$escrevente -> addDecorators($decorator_default);
    	$escrevente -> setLabel("Escrevente Autorizado:")
    			    -> setAttrib('size', '20');
    			   
    	$notificacao = new Zend_Form_Element_Radio('notificacao');
		$notificacao -> clearDecorators();
		$notificacao -> addDecorators($decorator_default);
    	$notificacao -> setLabel("Tipo de Notificação:");
    	$notificacao -> setMultiOptions(array(
			                '1'  => 'Boleto',
			                '2'  => 'Notificação Comum'));
    				  
    	$validator_doc = new Hazel_Validate_Or();
		$validator_doc->addValidator(new Hazel_Validate_Cpf())
          		  	  ->addValidator(new Hazel_Validate_Cnpj());   	
    	$cnpj = new Zend_Form_Element_Text('cnpj');
		$cnpj -> clearDecorators();
		$cnpj -> addDecorators($decorator_default);
    	$cnpj -> setLabel("CNPJ:")
	     	  -> setAttrib('size', '18') 
	     	  -> addValidator($validator_doc);

    	$codigo = new Zend_Form_Element_Text('codigo');
		$codigo -> clearDecorators();
		$codigo -> addDecorators($decorator_default);
    	$codigo -> setLabel("Código:")
    		    -> setAttrib('size', '5');
    		 
    	$telefone = new Zend_Form_Element_Text('telefone');
		$telefone -> clearDecorators();
		$telefone -> addDecorators($decorator_default);
    	$telefone -> setLabel("Telefone:")
    		      -> setAttrib('size', '14');
    	
    	$email = new Zend_Form_Element_Text('email');
		$email -> clearDecorators();
		$email -> addDecorators($decorator_default);
    	$email -> setLabel("e-mail:")
    		   -> setRequired(true)
    		   -> addValidator('EmailAddress')
    		   -> setAttrib('size', '40')
    		   -> setAttrib('maxlength', '60');
            
        $site = new Zend_Form_Element_Text('site');
		$site -> clearDecorators();
		$site -> addDecorators($decorator_default);
    	$site -> setLabel("Site:")
    		  -> setAttrib('size', '30');
    		 
    	$cep = new Zend_Form_Element_Text('cep');
		$cep -> clearDecorators();
		$cep -> addDecorators($decorator_default);
    	$cep -> setLabel("CEP:")
    		 -> setAttrib('size', '30');    		 
    	
    	$endereco = new Zend_Form_Element_Text('endereco');
		$endereco -> clearDecorators();
		$endereco -> addDecorators($decorator_default);
    	$endereco->setLabel("Endereço:")
    		 ->setAttrib('size', '30');

    	$complemento = new Zend_Form_Element_Text('complemento');
		$complemento -> clearDecorators();
		$complemento -> addDecorators($decorator_default);
    	$complemento->setLabel("Complemento:")
    		 ->setAttrib('size', '30');
    		 
    	$bairro = new Zend_Form_Element_Text('bairro');
		$bairro -> clearDecorators();
		$bairro -> addDecorators($decorator_default);
    	$bairro->setLabel("Bairro:")
    		 ->setAttrib('size', '30');
    		 
    	$numero = new Zend_Form_Element_Text('numero');
		$numero -> clearDecorators();
		$numero -> addDecorators($decorator_default);
    	$numero->setLabel("Número:")
    		 ->setAttrib('size', '30');
    		 
    	$estado = new Zend_Form_Element_Select('uf');
		$estado -> clearDecorators();
		$estado -> addDecorators($decorator_default);
		$estado -> setLabel('UF:');
		
		$model_estado = new Estado();
		$estado->addMultiOption('0', 'Selecione o Estado');		
	    foreach ($model_estado->findForSelect() as $uf) {
	    	$estado->addMultiOption($uf->idEstado, $uf->sigla);
		}
    		
    	$cidade = new Zend_Form_Element_Select('idCidade');
    	$cidade -> clearDecorators();
		$cidade -> addDecorators($decorator_default);
		$cidade->setLabel("Cidade:")
    				   ->setRegisterInArrayValidator(false);

    		 
    	$model_banco = new Banco();
	    $banco = new Zend_Form_Element_Select('banco');
		$banco -> clearDecorators();
		$banco -> addDecorators($decorator_default);
		$banco -> setLabel('Banco:');
        //populando o select
        $banco->addMultiOption('0', "Selecione o Banco");    	
	    foreach ($model_banco->findForSelect() as $banc) {
	    	$banco->addMultiOption($banc->idBanco, $banc->codigo . " - " .$banc->nome);
		}
				 
		$agencia = new Zend_Form_Element_Select('idAgencia');
    	$agencia -> setLabel("Agência:");
		$agencia -> clearDecorators();
		$agencia -> addDecorators($decorator_default)
    			 -> setRegisterInArrayValidator(false);

    		 	 
    	$conta = new Zend_Form_Element_Text('conta');
		$conta -> clearDecorators();
		$conta -> addDecorators($decorator_default);
    	$conta -> setLabel("Conta:")
    		   -> setAttrib('size', '20');
    		 
    	$carteira = new Zend_Form_Element_Text('carteira');
		$carteira -> clearDecorators();
		$carteira -> addDecorators($decorator_default);
    	$carteira -> setLabel("Carteira:")
    		      -> setAttrib('size', '5');
    	
    
    	$submit = new Zend_Form_Element_Submit('Salvar');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($idEndereco, $nome, $nomefantasia, $tabeliao, $substituto, $escrevente, $notificacao, $cnpj, $codigo, $telefone, $email, $site, $cep, $endereco, $complemento, $bairro,
        					$numero, $estado, $cidade, $banco, $agencia, $conta, $carteira, $submit));
    }
    
	public function setAsEditForm(Zend_Db_Table_Row $row){
        $data = $row->toArray();
		$this->populate($data);
        //$this->setAction(sprintf('editarcartorio/idCusta/%d', $row->idCusta));

        $this->populate(array("uf" => $data['idEstado']));
        $this->populate(array("banco" => $data['idBanco']));
                
        $model_cidade = new Cidade();
		foreach ($model_cidade->findForSelect($data['idEstado']) as $banc) {
	    	$this->idCidade->addMultiOption($banc->idCidade, $banc->nome);
		}
		
		$model_agencia = new Agencia();
		foreach ($model_agencia->findForSelect($data['idBanco']) as $banc) {
	    	$this->idAgencia->addMultiOption($banc->idAgencia, $banc->descricao);
		}
        
        $this->getElement('idEndereco');
        $this->getElement('nome');
        $this->getElement('nomefantasia');
        $this->getElement('notificacao');
        $this->getElement('cnpj');
        $this->getElement('codigo');
        $this->getElement('telefone');
        $this->getElement('email');
        $this->getElement('site');
        $this->getElement('cep');
        $this->getElement('endereco');
        $this->getElement('complemento');
        $this->getElement('bairro');
        $this->getElement('numero');
        $this->getElement('idCidade');
        $this->getElement('uf');        
        $this->getElement('banco');
        $this->getElement('idAgencia');
        $this->getElement('conta');
        $this->getElement('carteira');
        $this->getElement('tabeliao');
        $this->getElement('substituto');
        $this->getElement('escrevente');
        
        return $this;
    }


}

