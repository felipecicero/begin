<?php

class Admin_Form_Editarusuario extends Zend_Form
{

	public function init()
    {	
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
	
		/* Form Elements & Other Definitions Here ... */
    	$idUsuario = new Zend_Form_Element_Hidden('idUsuario');   
		$idUsuario -> clearDecorators();
		$idUsuario -> addDecorators($decorator_default);
    	
    	$nome = new Zend_Form_Element_Text('nome');
		$nome -> clearDecorators();
		$nome -> addDecorators($decorator_default);
    	$nome->setLabel("Nome:")
    		 ->setRequired(true)
    		 ->setAttrib('size', '40')
    		 ->setAttrib('maxlength', '60');    		 
    	      
    	$senha = new Zend_Form_Element_Password('senha', true);
		$senha -> clearDecorators();
		$senha -> addDecorators($decorator_default);
    	$senha->setLabel("Senha:")
    		  //->addFilter('StringTrim')
    	      //->setRequired(true)
    	      ->setAttrib('size', '15')
    	      ->setAttrib('maxlength', '20');    	      
    	      
    	$confirmasenha = new Zend_Form_Element_Password('confirmasenha', true);
		$confirmasenha -> clearDecorators();
		$confirmasenha -> addDecorators($decorator_default);
    	$confirmasenha->setLabel("Confirmar Senha:")
    	    		  //->setRequired(true)
    	    		  ->setAttrib('size', '15')
    	    		  ->setAttrib('maxlength', '20')
    	    		  ->addValidators(array(
            						  array('identical', false, array('token' => 'senha')))); 
    	
    	$validate = new Zend_Validate_Date(array('locale' => 'pt-Br'));
    	$nascimento = new Zend_Form_Element_Text('nascimento');
		$nascimento -> clearDecorators();
		$nascimento -> addDecorators($decorator_default);
    	$nascimento->setLabel("Nascimento:")    	 
    				->setAttrib('size', '10')
    				->setAttrib('maxlength', '10')
    				->addValidator($validate);//valida a data    			   
    	
    	$telefone = new Zend_Form_Element_Text('telefone');
		$telefone -> clearDecorators();
		$telefone -> addDecorators($decorator_default);
    	$telefone->setLabel("Telefone:")
    			 ->setAttrib('maxlength', '14')
    		     ->setAttrib('size', '14');
    	
    	            
        $model_perfil = new Papel();
	    $perfil = new Zend_Form_Element_Select('idPapel');
		$perfil -> clearDecorators();
		$perfil -> addDecorators($decorator_default);
		$perfil->setLabel('Tipo do Cadastro: ');
    	foreach ($model_perfil->getPapeis() as $perfi) {
	    	$perfil->addMultiOption($perfi->idPapel, $perfi->papel);
		}
		$perfil->setValue('2');

        $submit = new Zend_Form_Element_Submit('Salvar');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));

        $this->addElements(array($idUsuario, $nome, $senha, $confirmasenha, $nascimento, $telefone, $perfil, $submit));
    }

    public function setAsEditForm(Zend_Db_Table_Row $row){
        $this->populate($row->toArray());
        $this->setAction(sprintf('editarusuario/idUsuario/%d', $row->idUsuario));

        $this->getElement('nome');        
        $this->getElement('senha');
        $this->getElement('confirmasenha');
        $this->getElement('nascimento');
        $this->getElement('telefone');
        $this->getElement('idPerfil');
            

        return $this;
    }


}

	