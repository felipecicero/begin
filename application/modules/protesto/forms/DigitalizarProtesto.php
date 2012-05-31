<?php

class Protesto_Form_DigitalizarProtesto extends Zend_Form
{

    public function init(){
    	
    	$model_custa = new Custa();
    	
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
		$decorator_option = array('ViewHelper','Errors','Label',array(array('row' => 'HtmlTag'),array('class' => 'option-field')));
       
    	
    	$arquivo = new Zend_Form_Element_File('arquivo');
		$arquivo->removeDecorator('HtmlTag');	
        $arquivo;
    	
    	$model_protocolo = new Protocolo();
	    $protocolo = new Zend_Form_Element_Select('idProtocolo');
		$protocolo -> clearDecorators();
		$protocolo -> addDecorators($decorator_default);
		$protocolo->setLabel('Protocolo:')
				  ->setAttrib('disabled', 'disabled');
        //populando o select 
	    foreach ($model_protocolo->findForSelect() as $prot) {
	    	$protocolo->addMultiOption($prot->idProtocolo, $this->completa(10, $prot->protocolo, "0"));
		}
		
    	$model_situacao = new Situacao();
	    $situacao = new Zend_Form_Element_Select('idSituacao');
		$situacao -> clearDecorators();
		$situacao -> addDecorators($decorator_default);
		$situacao->setLabel('Situação:')
				 ->setAttrib('disabled', 'disabled');
        //populando o select 
	    foreach ($model_situacao->findForSelect() as $situa) {
	    	$situacao->addMultiOption($situa->idSituacao, $situa->descricao);
		}
		
    	$model_especie = new Especie();
	    $especie = new Zend_Form_Element_Select('idEspecietitulo');
		$especie -> clearDecorators();
		$especie -> addDecorators($decorator_default);
		$especie -> setLabel('Tipo Título:')
				 -> setRequired(true);		
        //populando o select    	
	    foreach ($model_especie->findForSelect() as $espe) {
	    	$especie -> addMultiOption($espe->idEspecietitulo, $espe->codigo . " - " .$espe->descricao);
		}
		
		$validate = new Zend_Validate_Date(array('locale' => 'pt-Br'));
    	$data_emissao = new Zend_Form_Element_Text('dataemissaotitulo');
		$data_emissao -> clearDecorators();
		$data_emissao -> addDecorators($decorator_default);
    	$data_emissao -> setLabel("Data Emissão:")    	 
					  -> setAttrib('size', '20')
    				  -> setAttrib('maxlength', '10')
    				  -> setAttrib('onKeyDown', 'Mascara(this,mdata);')
    				  -> setAttrib('onKeyPress', 'Mascara(this,mdata);')
    				  -> setAttrib('onKeyUp', 'Mascara(this,mdata);')
    				  -> addValidator($validate)    				
    				  -> setRequired(true);
    	
    				//onKeyDown="Mascara(this,mdata);" onKeyPress="Mascara(this,mdata);" onKeyUp="Mascara(this,mdata);"

    	$validator_date = new Validate_DateHJ();
		
    	$data_vencimento = new Zend_Form_Element_Text('datavencimentotitulo');
		$data_vencimento -> clearDecorators();
		$data_vencimento -> addDecorators($decorator_default);
    	$data_vencimento ->setLabel("Vencimento:")    	 
					 	 ->setAttrib('size', '20')
				 		 ->setAttrib('maxlength', '10')
						 ->setAttrib('onKeyDown', 'Mascara(this,mdata);')
						 ->setAttrib('onKeyPress', 'Mascara(this,mdata);')
						 ->setAttrib('onKeyUp', 'Mascara(this,mdata);')    				
						 ->addValidator($validate)
						 ->addValidator($validator_date)
						 ->setRequired(true);
						 //$confirmPswd->addErrorMessage('The passwords do not match');;
    				
    	$numero_titulo = new Zend_Form_Element_Text('numerotitulo');
		$numero_titulo -> clearDecorators();
		$numero_titulo -> addDecorators($decorator_default);
    	$numero_titulo->setLabel("N° Título:")    		 
					  ->setAttrib('size', '20')
					  ->setAttrib('maxlength', '11')
					  ->setRequired(true);
		
    	$model_banco = new Banco();
	    $banco = new Zend_Form_Element_Select('banco');
		$banco -> clearDecorators();
		$banco -> addDecorators($decorator_default);
		$banco ->setLabel('Banco:');
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
        	 
    	$titulo_bancario = new Zend_Form_Element_Text('titulo_bancario');
		$titulo_bancario -> clearDecorators();
		$titulo_bancario -> addDecorators($decorator_default);
    	$titulo_bancario -> setLabel("N° Título no Banco:")    		 
						 -> setAttrib('size', '20')
						 -> setAttrib('maxlength', '15');

    	$agencia_codigo = new Zend_Form_Element_Text('codigocedente_agencia');
		$agencia_codigo -> clearDecorators();
		$agencia_codigo -> addDecorators($decorator_default);
    	$agencia_codigo -> setLabel("Cód. Cedente/Agência:")    		 
						-> setAttrib('size', '20')
						-> setAttrib('maxlength', '15');
    	
		$praca_pagemento = new Zend_Form_Element_Select('pracaprotesto');
		$praca_pagemento -> clearDecorators();
		$praca_pagemento -> addDecorators($decorator_default);
		$praca_pagemento -> setLabel("Praça de Pagamento:")
					     -> setRequired(true);
		$model_cidade = new Cidade();
	    foreach ($model_cidade->getPracaPag() as $uf) {
	    	$praca_pagemento->addMultiOption($uf->idCidade, $uf->nome);
		} 
    		 
    	$valor_titulo = new Zend_Form_Element_Text('valortitulo');
		$valor_titulo -> clearDecorators();
		$valor_titulo -> addDecorators($decorator_default);
    	$valor_titulo -> setLabel("Valor Título:")    		 
    		 		  -> setAttrib('size', '20')
    		          -> setRequired(true);
    		 
    	$saldo_titulo = new Zend_Form_Element_Text('saldotitulo');
		$saldo_titulo -> clearDecorators();
		$saldo_titulo -> addDecorators($decorator_default);
    	$saldo_titulo -> setLabel("Saldo Título:")
    		 		  -> setAttrib('size', '20');
    	
    	$custas = new Zend_Form_Element_Text('valorcustascartorio');
		$custas -> clearDecorators();
		$custas -> addDecorators($decorator_default);
    	$custas -> setLabel("Custas:")
    		    -> setAttrib('size', '25')
				-> setAttrib('disabled', 'disabled');
    	
    	$pagas = new Zend_Form_Element_Checkbox('pagas');
		$pagas -> clearDecorators();
		$pagas -> addDecorators($decorator_default);
    	$pagas -> setLabel("Pagas:");

    	$valor = $model_custa->getCustaByName('intimação');
    	$intim_out = new Zend_Form_Element_Text('intim_out');
		$intim_out -> clearDecorators();
		$intim_out -> addDecorators($decorator_default);
    	$intim_out -> setLabel("Intimação/Out:")
    			   -> setAttrib('disabled', 'disabled')
				   -> setAttrib('size', '20')
				   -> setValue($valor);
    		
    	$conducao = new Zend_Form_Element_Text('conducao');
		$conducao -> clearDecorators();
		$conducao -> addDecorators($decorator_default);
    	$conducao -> setLabel("Condução:")
    		 	  -> setAttrib('size', '20')
				  -> setAttrib('disabled', 'disabled');
    		 
    	$certidao = new Zend_Form_Element_Text('certidao');
		$certidao -> clearDecorators();
		$certidao -> addDecorators($decorator_default);
    	$certidao -> setLabel("Certidão:")
    		 	  -> setAttrib('size', '20')
				 -> setAttrib('disabled', 'disabled');

    	$valor = $model_custa->getCustaByName('taxa judiciária');
    	$taxa_judiciaria = new Zend_Form_Element_Text('taxajudiciaria');
		$taxa_judiciaria -> clearDecorators();
		$taxa_judiciaria -> addDecorators($decorator_default);
    	$taxa_judiciaria -> setLabel("Taxa Judiciária:")
    		 			 -> setAttrib('size', '20')
    		 			 -> setAttrib('disabled', 'disabled')
    		 			 -> setValue($valor);
    	
    	$endosso = new Zend_Form_Element_Select('tipoendosso');
		$endosso -> clearDecorators();
		$endosso -> addDecorators($decorator_default);
		$endosso->setLabel('Endosso:')
				->addMultiOptions(
		                array(
		                	'' => 'Sem Endosso',
		                    'M' => 'Mandato',
		                    'T' => 'Translativo',
		                )
		          )
		          ->setRequired(true);
                  
                  
        $contra_apresentacao = new Zend_Form_Element_Checkbox('contraapresentacao');
		$contra_apresentacao -> clearDecorators();
		$contra_apresentacao -> addDecorators($decorator_option);
    	$contra_apresentacao->setLabel("Contra Apresentação");
    	
    	$avista = new Zend_Form_Element_Checkbox('avista');
		$avista -> clearDecorators();
		$avista -> addDecorators($decorator_option);
    	$avista->setLabel("À vista");
    	 
    	$contra_protesto = new Zend_Form_Element_Checkbox('contraprotesto');
		$contra_protesto -> clearDecorators();
		$contra_protesto -> addDecorators($decorator_option);
    	$contra_protesto->setLabel("Contra Protesto");
    	
    	$fins_familiares = new Zend_Form_Element_Checkbox('finsfamiliares');
		$fins_familiares -> clearDecorators();
		$fins_familiares -> addDecorators($decorator_option);
    	$fins_familiares->setLabel("Fins Falimentares");
    	
    	$aceite = new Zend_Form_Element_Checkbox('aceite');
		$aceite -> clearDecorators();
		$aceite -> addDecorators($decorator_option);
    	$aceite->setLabel("Aceite");
         
    	
    	/**DEVEDOR*/
    	$tipo_devedor = new Zend_Form_Element_Radio('tipo_identificacao_devedor');
		$tipo_devedor -> clearDecorators();
		$tipo_devedor -> addDecorators($decorator_default);
        $tipo_devedor->setLabel('Tipo do documento:')
             ->addMultiOptions(array(
        		'1' => 'CNPJ',
       			 '2' => 'CPF'
      			))
      			->setSeparator('')
      			->setValue('1')
                ->setRequired(true);
              
    	$validator_doc = new Hazel_Validate_Or();
		$validator_doc->addValidator(new Hazel_Validate_Cpf())
          		  	  ->addValidator(new Hazel_Validate_Cnpj());   	
    	$documento_devedor = new Zend_Form_Element_Text('documento_devedor');
		$documento_devedor -> clearDecorators();
		$documento_devedor -> addDecorators($decorator_default);
    	$documento_devedor->setLabel("Documento:")
    		 			  ->setAttrib('size', '18') 
    		 			  ->addValidator($validator_doc)
    		 			  ->setRequired(true);
                  
        $nome_devedor = new Zend_Form_Element_Text('nome_devedor');
		$nome_devedor -> clearDecorators();
		$nome_devedor -> addDecorators($decorator_default);
    	$nome_devedor->setLabel("Nome:")
    		 ->setAttrib('size', '40')
    		 ->setRequired(true);
    		 
    	$cep_devedor = new Zend_Form_Element_Text('cep_devedor');
		$cep_devedor -> clearDecorators();
		$cep_devedor -> addDecorators($decorator_default);
    	$cep_devedor->setLabel("CEP:")
    		 ->setAttrib('size', '10')
    		 ->setRequired(true);
    	
    	$endereco_devedor = new Zend_Form_Element_Text('endereco_devedor');
		$endereco_devedor -> clearDecorators();
		$endereco_devedor -> addDecorators($decorator_default);
    	$endereco_devedor->setLabel("Endereço:")
    		 ->setAttrib('size', '40')
    		 ->setRequired(true);

    	$complemento_devedor = new Zend_Form_Element_Text('complemento_devedor');
		$complemento_devedor -> clearDecorators();
		$complemento_devedor -> addDecorators($decorator_default);
    	$complemento_devedor->setLabel("Complemento:")
    		 ->setAttrib('size', '20');
    		 
    	$bairro_devedor = new Zend_Form_Element_Text('bairro_devedor');
		$bairro_devedor -> clearDecorators();
		$bairro_devedor -> addDecorators($decorator_default);
    	$bairro_devedor->setLabel("Bairro:")
    		 ->setAttrib('size', '20');
    		 
    	$numero_devedor = new Zend_Form_Element_Text('numero_devedor');
		$numero_devedor -> clearDecorators();
		$numero_devedor -> addDecorators($decorator_default);
    	$numero_devedor->setLabel("Número:")
    		 ->setAttrib('size', '5');
    				 
			 
		$estado_devedor = new Zend_Form_Element_Select('uf_devedor');
		$estado_devedor -> clearDecorators();
		$estado_devedor -> addDecorators($decorator_default);
		$estado_devedor->setLabel('UF:')
					   ->setRequired(true);
		$model_estado = new Estado();
		$estado_devedor->addMultiOption('0', 'Selecione o Estado');
	    foreach ($model_estado->findForSelect() as $uf) {
	    	$estado_devedor->addMultiOption($uf->idEstado, $uf->sigla);
		} 
    		
    	$cidade_devedor = new Zend_Form_Element_Select('cidade_devedor');
    	$cidade_devedor -> setLabel("Cidade:");
		$cidade_devedor -> clearDecorators();
		$cidade_devedor -> addDecorators($decorator_default)
    				    -> setRegisterInArrayValidator(false)
    		 		    -> setRequired(true);
    
    	$obs_devedor = new Zend_Form_Element_Textarea('obs_devedor');
		$obs_devedor -> clearDecorators();
		$obs_devedor -> addDecorators($decorator_default);
        $obs_devedor ->setLabel('Observações: (telefones, emails, etc)')
			         ->setAttrib('rows','5')
					 ->setAttrib('cols','40')
					 ->addFilter('StripTags');
		
    		 
    		 
    	//CEDENTE
    	$tipo_cedente = new Zend_Form_Element_Radio('tipo_identificacao_cedente');
		$tipo_cedente -> clearDecorators();
		$tipo_cedente -> addDecorators($decorator_default);
        $tipo_cedente->setLabel('Tipo do documento:')
             ->addMultiOptions(array(
        		'1' => 'CNPJ',
       			 '2' => 'CPF'
      			))
      			->setSeparator('')
      			->setValue('1')
             	->setRequired(true);
         
    	$documento_cedente = new Zend_Form_Element_Text('documento_cedente');
		$documento_cedente -> clearDecorators();
		$documento_cedente -> addDecorators($decorator_default);
    	$documento_cedente->setLabel("Documento:")
    		 			  ->setAttrib('size', '18')
    		 			  ->setAttrib('id', 'documento_cedente')
    		 			  ->addValidator($validator_doc)
    		 			  ->setRequired(true);
                  
        $nome_cedente = new Zend_Form_Element_Text('nome_cedente');
		$nome_cedente -> clearDecorators();
		$nome_cedente -> addDecorators($decorator_default);
    	$nome_cedente->setLabel("Nome:")
    		 ->setAttrib('size', '40')
    		 ->setRequired(true);
    		 
    	$cep_cedente = new Zend_Form_Element_Text('cep_cedente');
		$cep_cedente -> clearDecorators();
		$cep_cedente -> addDecorators($decorator_default);
    	$cep_cedente->setLabel("CEP:")
    		 ->setAttrib('size', '10')
    		 ->setRequired(true);
    	
    	$endereco_cedente = new Zend_Form_Element_Text('endereco_cedente');
		$endereco_cedente -> clearDecorators();
		$endereco_cedente -> addDecorators($decorator_default);
    	$endereco_cedente->setLabel("Endereço:")
    		 ->setAttrib('size', '40')
    		 ->setRequired(true);

    	$complemento_cedente = new Zend_Form_Element_Text('complemento_cedente');
		$complemento_cedente -> clearDecorators();
		$complemento_cedente -> addDecorators($decorator_default);
    	$complemento_cedente->setLabel("Complemento:")
    		 ->setAttrib('size', '20');
    		 
    	$bairro_cedente = new Zend_Form_Element_Text('bairro_cedente');
		$bairro_cedente -> clearDecorators();
		$bairro_cedente -> addDecorators($decorator_default);
    	$bairro_cedente->setLabel("Bairro:")
    		 ->setAttrib('size', '20');
    		 
    	$numero_cedente = new Zend_Form_Element_Text('numero_cedente');
		$numero_cedente -> clearDecorators();
		$numero_cedente -> addDecorators($decorator_default);
    	$numero_cedente->setLabel("Número:")
    		 ->setAttrib('size', '5');
    	
    	$estado_cedente = new Zend_Form_Element_Select('uf_cedente');
		$estado_cedente -> clearDecorators();
		$estado_cedente -> addDecorators($decorator_default);
		$estado_cedente -> setLabel('UF:')
					    -> setRequired(true);
		$estado_cedente->addMultiOption('0', 'Selecione o Estado');		
	    foreach ($model_estado->findForSelect() as $uf) {
	    	$estado_cedente->addMultiOption($uf->idEstado, $uf->sigla);
		} 
    		
    	$cidade_cedente = new Zend_Form_Element_Select('cidade_cedente');
    	$cidade_cedente -> clearDecorators();
		$cidade_cedente -> addDecorators($decorator_default);
		$cidade_cedente->setLabel("Cidade:")
    				   ->setRegisterInArrayValidator(false)
    		 		   ->setRequired(true);

    	$obs_cedente = new Zend_Form_Element_Textarea('obs_cedente');
		$obs_cedente -> clearDecorators();
		$obs_cedente -> addDecorators($decorator_default);
        $obs_cedente ->setLabel('Observações: (telefones, emails, etc)')
			         ->setAttrib('rows','5')
					 ->setAttrib('cols','40')
					 ->addFilter('StripTags');	 
    		 
    	//APRESENTANTE
    	$tipo_apresentante = new Zend_Form_Element_Radio('tipo_identificacao_apresentante');
		$tipo_apresentante -> clearDecorators();
		$tipo_apresentante -> addDecorators($decorator_default);
        $tipo_apresentante->setLabel('Tipo do documento:')
             			  ->addMultiOptions(array(
        								'1' => 'CNPJ',
       			 						'2' => 'CPF'
      								))
      					  ->setSeparator('')
      					  ->setValue('1');
         
    	$documento_apresentante = new Zend_Form_Element_Text('documento_apresentante');
		$documento_apresentante -> clearDecorators();
		$documento_apresentante -> addDecorators($decorator_default);
    	$documento_apresentante->setLabel("Documento:")
    		 				   ->setAttrib('size', '18');
    		 				   //->addValidator($validator_doc);
                  
        $nome_apresentante = new Zend_Form_Element_Text('nome_apresentante');
		$nome_apresentante -> clearDecorators();
		$nome_apresentante -> addDecorators($decorator_default);
    	$nome_apresentante->setLabel("Nome:")
    		 ->setAttrib('size', '40');
    		 
    	$cep_apresentante = new Zend_Form_Element_Text('cep_apresentante');
		$cep_apresentante -> clearDecorators();
		$cep_apresentante -> addDecorators($decorator_default);
    	$cep_apresentante->setLabel("CEP:")
    		 ->setAttrib('size', '10');
    	
    	$endereco_apresentante = new Zend_Form_Element_Text('endereco_apresentante');
		$endereco_apresentante -> clearDecorators();
		$endereco_apresentante -> addDecorators($decorator_default);
    	$endereco_apresentante->setLabel("Endereço:")
    		 ->setAttrib('size', '40');

    	$complemento_apresentante = new Zend_Form_Element_Text('complemento_apresentante');
		$complemento_apresentante -> clearDecorators();
		$complemento_apresentante -> addDecorators($decorator_default);
    	$complemento_apresentante->setLabel("Complemento:")
    		 ->setAttrib('size', '20');
    		 
    	$bairro_apresentante = new Zend_Form_Element_Text('bairro_apresentante');
		$bairro_apresentante -> clearDecorators();
		$bairro_apresentante -> addDecorators($decorator_default);
    	$bairro_apresentante->setLabel("Bairro:")
    		 ->setAttrib('size', '20');
    		 
    	$numero_apresentante = new Zend_Form_Element_Text('numero_apresentante');
		$numero_apresentante -> clearDecorators();
		$numero_apresentante -> addDecorators($decorator_default);
    	$numero_apresentante->setLabel("Número:")
    		 ->setAttrib('size', '5');
    	
    	$estado_apresentante = new Zend_Form_Element_Select('uf_apresentante');
		$estado_apresentante -> clearDecorators();
		$estado_apresentante -> addDecorators($decorator_default);
		$estado_apresentante -> setLabel('UF:')
							 -> setRequired(true);		
	    $estado_apresentante->addMultiOption('0', 'Selecione o Estado');
		foreach ($model_estado->findForSelect() as $uf) {
	    	$estado_apresentante->addMultiOption($uf->idEstado, $uf->sigla);
		} 
    		    		 		   	 
    	$cidade_apresentante = new Zend_Form_Element_Select('cidade_apresentante');
    	$cidade_apresentante -> clearDecorators();
		$cidade_apresentante -> addDecorators($decorator_default);
		$cidade_apresentante -> setLabel("Cidade:");
    	
    	$obs_apresentante = new Zend_Form_Element_Textarea('obs_apresentante');
		$obs_apresentante -> clearDecorators();
		$obs_apresentante -> addDecorators($decorator_default);
        $obs_apresentante ->setLabel('Observações: (telefones, emails, etc)')
			         ->setAttrib('rows','5')
					 ->setAttrib('cols','40')
					 ->addFilter('StripTags');	 
    		 
    	//SACADOR
    	$tipo_sacador = new Zend_Form_Element_Radio('tipo_identificacao_sacador');
		$tipo_sacador -> clearDecorators();
		$tipo_sacador -> addDecorators($decorator_default);
        $tipo_sacador -> setLabel('Tipo do documento:')
             		  -> addMultiOptions(array(
        								'1' => 'CNPJ',
       			 						'2' => 'CPF'
      								))
      				  -> setSeparator('')
      				  -> setValue('1');
         
    	$documento_sacador = new Zend_Form_Element_Text('documento_sacador');
		$documento_sacador -> clearDecorators();
		$documento_sacador -> addDecorators($decorator_default);
    	$documento_sacador->setLabel("Documento:")
    		 			  ->setAttrib('size', '18');
    		 			  //->addValidator($validator_doc);
                  
        $nome_sacador = new Zend_Form_Element_Text('nome_sacador');
		$nome_sacador -> clearDecorators();
		$nome_sacador -> addDecorators($decorator_default);
    	$nome_sacador->setLabel("Nome:")
    		 ->setAttrib('size', '40');
    		 
    	$cep_sacador = new Zend_Form_Element_Text('cep_sacador');
		$cep_sacador -> clearDecorators();
		$cep_sacador -> addDecorators($decorator_default);
    	$cep_sacador->setLabel("CEP:")
    		 ->setAttrib('size', '10');
    		 //->addValidator(new Validate_Cep());
    	
    	$endereco_sacador = new Zend_Form_Element_Text('endereco_sacador');
		$endereco_sacador -> clearDecorators();
		$endereco_sacador -> addDecorators($decorator_default);
    	$endereco_sacador->setLabel("Endereço:")
    		 ->setAttrib('size', '40');

    	$complemento_sacador = new Zend_Form_Element_Text('complemento_sacador');
		$complemento_sacador -> clearDecorators();
		$complemento_sacador -> addDecorators($decorator_default);
    	$complemento_sacador->setLabel("Complemento:")
    		 ->setAttrib('size', '20');
    		 
    	$bairro_sacador = new Zend_Form_Element_Text('bairro_sacador');
		$bairro_sacador -> clearDecorators();
		$bairro_sacador -> addDecorators($decorator_default);
    	$bairro_sacador->setLabel("Bairro:")
    		 ->setAttrib('size', '20');
    		 
    	$numero_sacador = new Zend_Form_Element_Text('numero_sacador');
		$numero_sacador -> clearDecorators();
		$numero_sacador -> addDecorators($decorator_default);
    	$numero_sacador->setLabel("Número:")
    		 ->setAttrib('size', '5');
    	
    	$estado_sacador = new Zend_Form_Element_Select('uf_sacador');
		$estado_sacador -> clearDecorators();
		$estado_sacador -> addDecorators($decorator_default);
		$estado_sacador -> setLabel('UF:')
					    -> setRequired(true);
		$estado_sacador -> addMultiOption('0', 'Selecione o Estado');		
	    foreach ($model_estado->findForSelect() as $uf) {
	    	$estado_sacador->addMultiOption($uf->idEstado, $uf->sigla);
		} 
    		    		 		   	 
    	$cidade_sacador = new Zend_Form_Element_Select('cidade_sacador');
    	$cidade_sacador -> clearDecorators();
		$cidade_sacador -> addDecorators($decorator_default);
		$cidade_sacador->setLabel("Cidade:");
        
		$obs_sacador = new Zend_Form_Element_Textarea('obs_sacador');
		$obs_sacador -> clearDecorators();
		$obs_sacador -> addDecorators($decorator_default);
        $obs_sacador -> setLabel('Observações: (telefones, emails, etc)')
			         -> setAttrib('rows','5')
					 -> setAttrib('cols','40')
					 -> addFilter('StripTags');
		
        $submit = new Zend_Form_Element_Submit('Salvar');
        $submit -> setAttrib('id', 'submitbutton-import');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
		
 
        $this->addElements(array($arquivo, $protocolo, $situacao, $especie, $data_emissao, $data_vencimento, 
        $numero_titulo, $banco, $agencia, $titulo_bancario, $agencia_codigo, $praca_pagemento, $valor_titulo,
        $saldo_titulo, $custas, $pagas, $intim_out, $conducao, $certidao, $taxa_judiciaria, $endosso,
        $contra_apresentacao, $avista, $contra_protesto, $fins_familiares, $aceite));
        
		$this->addElements(array($tipo_devedor, $documento_devedor, $nome_devedor, $cep_devedor, $endereco_devedor, $complemento_devedor, 
        $bairro_devedor, $numero_devedor, $estado_devedor, $cidade_devedor, $obs_devedor));
        
        $this->addElements(array($tipo_cedente, $documento_cedente, $nome_cedente, $cep_cedente, $endereco_cedente, $complemento_cedente, 
        $bairro_cedente, $numero_cedente,  $estado_cedente, $cidade_cedente, $obs_cedente));
        
        $this->addElements(array($tipo_apresentante, $documento_apresentante, $nome_apresentante, $cep_apresentante, $endereco_apresentante, $complemento_apresentante, 
        $bairro_apresentante, $numero_apresentante, $estado_apresentante, $cidade_apresentante, $obs_apresentante));
        
        $this->addElements(array($tipo_sacador, $documento_sacador, $nome_sacador, $cep_sacador, $endereco_sacador, $complemento_sacador, 
        $bairro_sacador, $numero_sacador, $estado_sacador, $cidade_sacador, $obs_sacador));	
			
		$this->addDisplayGroup(array('arquivo'),'campoarquivo',array('legend' => 'Arquivo'));
        $campoarquivo = $this->getDisplayGroup('campoarquivo');
		$campoarquivo->setDecorators(array(
					'FormElements',
					'Fieldset',
					array('HtmlTag',array('tag'=>'div'))
		));
		
		$this->addDisplayGroup(array(
			'idProtocolo','idSituacao','idEspecietitulo','dataemissaotitulo','datavencimentotitulo','numerotitulo','banco','idAgencia','titulo_bancario','codigocedente_agencia','pracaprotesto','valortitulo','saldotitulo','valorcustascartorio','pagas','intim_out','conducao','certidao','taxajudiciaria','tipoendosso','contraapresentacao','avista','contraprotesto','finsfamiliares','aceite'
		),'titulo',array('legend' => 'Título'));
        $titulo = $this->getDisplayGroup('titulo');
		$titulo->setDecorators(array(
					'FormElements',
					'Fieldset',
					array('HtmlTag',array('tag'=>'div','class'=>'half-form float-fields'))
		));
		
		$this->addDisplayGroup(array(
			'tipo_identificacao_devedor','documento_devedor','nome_devedor','cep_devedor','endereco_devedor','complemento_devedor','bairro_devedor','numero_devedor','uf_devedor' ,'cidade_devedor', 'obs_devedor'
		),'devedor',array('legend' => 'Devedor'));
        $devedor = $this->getDisplayGroup('devedor');
		$devedor->setDecorators(array(
					'FormElements',
					'Fieldset',
					array('HtmlTag',array('tag' => 'div', 'class' => 'half-form', 'id' => 'form-tab', 'openOnly' => true))
		));
		
		$this->addDisplayGroup(array(
			'tipo_identificacao_cedente','documento_cedente','nome_cedente','cep_cedente','endereco_cedente','complemento_cedente','bairro_cedente','numero_cedente','uf_cedente' ,'cidade_cedente', 'obs_cedente'
		),'cedente',array('legend' => 'Cedente'));
        $cedente = $this->getDisplayGroup('cedente');
		$cedente->setDecorators(array(
					'FormElements',
					'Fieldset'
		));
		
		$this->addDisplayGroup(array(
			'tipo_identificacao_apresentante','documento_apresentante','nome_apresentante','cep_apresentante','endereco_apresentante','complemento_apresentante','bairro_apresentante','numero_apresentante','uf_apresentante' ,'cidade_apresentante', 'obs_apresentante'
		),'apresentante',array('legend' => 'Apresentante'));
        $apresentante = $this->getDisplayGroup('apresentante');
		$apresentante->setDecorators(array(
					'FormElements',
					'Fieldset'
		));
		
		$this->addDisplayGroup(array(
			'tipo_identificacao_sacador','documento_sacador','nome_sacador','cep_sacador','endereco_sacador','complemento_sacador','bairro_sacador','numero_sacador','uf_sacador' ,'cidade_sacador', 'obs_sacador'
		),'sacador',array('legend' => 'Sacador'));
        $sacador = $this->getDisplayGroup('sacador');
		$sacador->setDecorators(array(
					'FormElements',
					'Fieldset',
					array('HtmlTag', array('tag' => 'div', 'closeOnly' => true))
		));
		
        $this->addElements(array($submit));
    }
    
    
	public function completa($tamanho, $string, $complemento){
		
		$tamanho_string = strlen($string);
		
		if($complemento == "0"){
			while($tamanho_string < $tamanho){
				$string = $complemento.$string;
				$tamanho_string = strlen($string);
			}
		}
		else{
			while($tamanho_string < $tamanho){
				$string = $string.$complemento;
				$tamanho_string = strlen($string);
			}
		}
		return $string;
	}
	
}

