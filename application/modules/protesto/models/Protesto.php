<?php

class Protesto extends Zend_Db_Table_Abstract
{

	protected $_name = 'cap_protestos';
	
	public function selectTitulos($idSituacao = 1)
    {
    	//Selecione os titulos digitalizados
    	$select1 = $this->select();
    	
    	$select1->setIntegrityCheck(false);
    	
    	$select1->from(array('pro' => 'cap_protestos'), array('idProtesto',  'data_entrada'));
    	
    	$select1->joinLeft(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo' => new Zend_Db_Expr("CASE pro.idArquivo WHEN 0 THEN 7 ELSE arq.tipo END")));
    	
    	$select1->joinInner(array('td' => 'cap_titulos'), 'pro.idTitulo = td.idTitulo', array('valortitulo'));
    	
    	$select1->joinInner(array('pes' => 'cap_pessoa'), 'td.idPessoa_devedor = pes.idPessoa', array('nome', 'numeroidentificacao', 'tipoidentificacao' => 'tipo_identificacao'));
    	
    	$select1->joinInner(array('esp' => 'cap_especietitulos'), 'td.idEspecietitulo = esp.idEspecietitulo', array('codigo', 'especie' => 'descricao'));
    	
    	$select1->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo'));
    	
    	//Pega os dados se estiver na lista de amigos/políticos    	
    	$select1->joinLeft(array('amg' => 'cap_amigos'), 'amg.documento = pes.numeroidentificacao', array('docamigo' => 'documento'));
    	
    	//Pega os dados se estiver em algum edital    	
    	$select1->joinLeft(array('edi' => 'cap_editais'), 'pro.idProtesto = edi.idProtesto', array('idEdital'));
    	    	
    	$select1->joinInner(array('sit' => 'cap_situacao'), 'sit.idSituacao = td.idSituacao', array('situacaoatual' => 'descricao' ));
    	
    	$select1->where('arq.tipo = 7 OR pro.idArquivo= 0'); //todos arquivos digitalizados
    	
    	if($idSituacao != 0)
    	$select1->where('td.idSituacao = ?', $idSituacao);
    	
    	
    	//Selecione os titulos importados
    	$select2 = $this->select();
    	
    	$select2->setIntegrityCheck(false);
    	
    	$select2->from(array('pro' => 'cap_protestos'), array('idProtesto',  'data_entrada'));
    	
    	$select2->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo'));
    	
    	$select2->joinInner(array('td' => 'cap_titulos_importados'), 'pro.idTitulo = td.idTitulo', array('valortitulo', 'nome' => 'nomedevedor', 'numeroidentificacao'=>'numeroidentificacaodevedor', 'tipoidentificacao' => 'tipoidentificacaodevedor', 'codigo' => 'especietitulo'));
    	
    	$select2->joinLeft(array('esp' => 'cap_especietitulos'), 'esp.idEspecietitulo > 0', array('especie' => 'descricao'));
    	
    	$select2->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo'));
    	//Pega os dados se estiver na lista de amigos/políticos
    	$select2->joinLeft(array('amg' => 'cap_amigos'), 'amg.documento = td.numeroidentificacaodevedor', array('docamigo' => 'documento'));
    	
    	$select2->joinLeft(array('edi' => 'cap_editais'), 'pro.idProtesto = edi.idProtesto', array('idEdital'));
    	
    	$select2->joinInner(array('sit' => 'cap_situacao'), 'sit.idSituacao = td.idSituacao', array('situacaoatual' => 'descricao' ));
    	
    	$select2->where('arq.tipo <> 7'); //todos arquivos digitalizados
    	
    	if($idSituacao != 0)
    	$select2->where('td.idSituacao = ?', $idSituacao);
    	
    	$select2->where("esp.codigo = td.especietitulo");
    	
    	
    	//UNION com os dois selects
    	$select = $this->select()
    				 ->union(array($select1, $select2))
    				 ->order(array('nome'));
    	
    	/*$sql = (string) $select;    
    	print_r("<pre>");
    	print_r($sql);
    	print_r("</pre>");
    	exit;*/
    	
    	return $select;
    }
    
	public function selectDevedor($idProtesto)
    {
    	$devedor = null;
    	
    	$arquivo = $this->selectArquivoByIdProtesto($idProtesto);
    	/*print_r('<pre>');
    	print_r($arquivo);
    	print_r('</pre>');
    	exit;*/
    	$tipo = 7;
    	
    	if(isset($arquivo[0])){
    		$tipo = $arquivo[0]->tipo;
    	}
    	
    	if($tipo == 7){
    		$devedor = $this->selectTituloD($idProtesto);
    	}
    	else{
    		$devedor = $this->selectTituloI($idProtesto);
    	}
    	
    	return $devedor->Current();
    }

	public function selectArquivoByIdProtesto($idProtesto)
    {    	
        $select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('pro' => 'cap_protestos'), array());
    	
    	$select->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('idUsuario', 'arquivo', 'tipo', 'data_envio'));
	
    	$select->where('pro.idProtesto = ?', $idProtesto);
    	
    	//$sql = (string) $select;    
    	//print_r($sql);exit;
    	
    	return $this->fetchAll($select);
    }

    public function selectTituloD($idProtesto){
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('pro' => 'cap_protestos'), array('idProtesto', 'idTitulo'));
    	    	
    	$select->joinLeft(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo' => new Zend_Db_Expr("CASE pro.idArquivo WHEN 0 THEN 7 ELSE arq.tipo END")));
    	
    	$select->joinLeft(array('liv' => 'cap_livro'), 'pro.idLivro = liv.idLivro', array('idLivro', 'folha', 'livro'));
    	
    	$select->joinInner(array('td' => 'cap_titulos'), 'pro.idTitulo = td.idTitulo', array('idSituacao', 'numerotitulo', 'valortitulo', 'saldotitulo', 'vencimento' => 'datavencimentotitulo', 'dataemissao' => 'dataemissaotitulo', 'tipoendosso', 'codigocedente_agencia', 'nossonumero' => 'titulo_bancario'));
    	
    	$select->joinInner(array('pes' => 'cap_pessoa'), 'td.idPessoa_devedor = pes.idPessoa', array('nome', 'numeroidentificacao', 'tipoidentificacao' => 'tipo_identificacao'));

    	$select->joinInner(array('ced' => 'cap_pessoa'), 'td.idPessoa_cedente = ced.idPessoa', array('nomecedente' => 'nome', 'documentocedente' => 'numeroidentificacao', 'tipoidentificacaocedente' => 'tipo_identificacao'));
    	
    	$select->joinInner(array('sac' => 'cap_pessoa'), 'td.idPessoa_sacador = sac.idPessoa', array('nomesacador' => 'nome', 'documentosacador' => 'numeroidentificacao', 'tipoidentificacaosacador' => 'tipo_identificacao'));
    	
    	$select->joinInner(array('apr' => 'cap_pessoa'), 'td.idPessoa_apresentante = apr.idPessoa', array('nomeapresentante' => 'nome', 'documentoapresentante' => 'numeroidentificacao', 'tipoidentificacaoapresentante' => 'tipo_identificacao'));
    	
    	$select->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo', 'data_protocolo'));
    	
    	$select->joinInner(array('end' => 'cap_enderecos'), 'pes.idEndereco = end.idEndereco', array('endereco' => 'rua', 'numero' => 'numero', 'complemento' => 'complemento', 'cep' => 'cep', 'bairro' => 'bairro'));
    	    	
    	$select->joinInner(array('cid' => 'cap_cidades'), 'end.idCidade = cid.idCidade', array('cidade' => 'nome' ));
    	
    	$select->joinInner(array('est' => 'cap_estados'), 'est.idEstado = cid.idEstado', array('estado' => 'sigla' ));
    	
    	$select->joinInner(array('esp' => 'cap_especietitulos'), 'td.idEspecietitulo = esp.idEspecietitulo', array('especie' => 'descricao', 'codigo'));
    	
    	$select->joinInner(array('pra' => 'cap_cidades'), 'td.pracaprotesto = pra.idCidade', array('pracaprotesto' => 'nome' ));
    	
    	$select->joinInner(array('sit' => 'cap_situacao'), 'sit.idSituacao = td.idSituacao', array('situacaoatual' => 'descricao' ));
    	
    	$select->where('pro.idProtesto = ?', $idProtesto);
    	
    	//$sql = (string) $select;    
    	//print_r($sql);exit;	
    	
    	$data = $this->fetchAll($select);
    	//print_r($data);exit;
    	$data[0]->endereco = $data[0]->endereco . ", " . $data[0]->numero . ", " . $data[0]->complemento;
    	
    	return ($data);
    }

 	public function selectTituloI($idProtesto){
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('pro' => 'cap_protestos'), array('idProtesto', 'idTitulo', 'data_entrada'));
    	
    	$select->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo'));
    	
    	$select->joinLeft(array('liv' => 'cap_livro'), 'pro.idLivro = liv.idLivro', array('idLivro', 'folha', 'livro'));
    	
    	$select->joinInner(array('td' => 'cap_titulos_importados'), 'pro.idTitulo = td.idTitulo', array('idSituacao', 'nome' => 'nomedevedor', 'numeroidentificacao'=>'numeroidentificacaodevedor', 'tipoidentificacao' => 'tipoidentificacaodevedor', 'codigo' => 'especietitulo', 'endereco' => 'enderecodevedor', 'cep' => 'cepdevedor', 'cidade' => 'cidadedevedor', 'estado' => 'ufdevedor', 'bairro' => 'bairrodevedor', 'vencimento' => 'datavencimentotitulo', 'numerotitulo' => 'numerotitulo', 'valortitulo', 'saldotitulo', 'dataemissao' => 'dataemissaotitulo', 'tipoendosso', 'pracaprotesto', 'codigocedente_agencia', 'nossonumero', 'nomecedente', 'nomesacador', 'documentosacador'));
    	
    	$select->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo', 'data_protocolo'));

    	$select->joinInner(array('cab' => 'cap_cabecalhos'), 'td.idCabecalho = cab.idCabecalho', array());
    	
    	$select->joinInner(array('por' => 'cap_portadores'), 'cab.idPortador = por.idPortador', array('nomeapresentante' => 'nomeportador'));
    	
    	$select->joinLeft(array('esp' => 'cap_especietitulos'), 'esp.idEspecietitulo > 0', array('especie' => 'descricao'));

    	$select->joinInner(array('sit' => 'cap_situacao'), 'sit.idSituacao = td.idSituacao', array('situacaoatual' => 'descricao' ));
    	
    	$select->where('pro.idProtesto = ?', $idProtesto);
    	
    	$select->where("esp.codigo = td.especietitulo");
    	//$sql = (string) $select;    
    	//print_r($sql);exit;	
    	$data = $this->fetchAll($select);
    	
    	return $data;
    }

    public function getIdTitulo($idProtesto){
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('pro' => 'cap_protestos'), array('idProtesto', 'idTitulo', 'data_entrada'));
    	    	
    	$select->joinLeft(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo' => new Zend_Db_Expr("CASE pro.idArquivo WHEN 0 THEN 7 ELSE arq.tipo END")));
    	
    	$select->where('idProtesto = ?', $idProtesto);
    	    	
    	/*$sql = (string) $select;    
    	print_r("<pre>");
    	print_r($sql);
    	print_r("</pre>");
    	exit;*/
    	
    	$data = $this->fetchAll($select);
    	
    	return $data;
    }

	public function selectTitulosPagamento() // seleciona todos os títulos que são aptos a serem pagos.
    {
    	//Selecione os titulos digitalizados
    	$select1 = $this->select();
    	
    	$select1->setIntegrityCheck(false);
    	
    	$select1->from(array('pro' => 'cap_protestos'), array('idProtesto',  'data_entrada'));
    	
    	$select1->joinLeft(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo' => new Zend_Db_Expr("CASE pro.idArquivo WHEN 0 THEN 7 ELSE arq.tipo END")));
    	
    	$select1->joinInner(array('td' => 'cap_titulos'), 'pro.idTitulo = td.idTitulo', array());
    	
    	$select1->joinInner(array('pes' => 'cap_pessoa'), 'td.idPessoa_devedor = pes.idPessoa', array('nome', 'numeroidentificacao', 'tipoidentificacao' => 'tipo_identificacao'));
    	
    	$select1->joinInner(array('esp' => 'cap_especietitulos'), 'td.idEspecietitulo = esp.idEspecietitulo', array('codigo', 'especie' => 'descricao'));
    	
    	$select1->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo'));
    	
    	//Pega os dados se estiver na lista de amigos/políticos    	
    	$select1->joinLeft(array('amg' => 'cap_amigos'), 'amg.documento = pes.numeroidentificacao', array('docamigo' => 'documento'));
    	
    	//Pega os dados se estiver em algum edital    	
    	$select1->joinLeft(array('edi' => 'cap_editais'), 'pro.idProtesto = edi.idProtesto', array('idEdital'));
    	
    	$select1->where('arq.tipo = 7 OR pro.idArquivo= 0'); //todos arquivos digitalizados
    	
    	$select1->where('td.idSituacao <> 1'); // ou pago
    	
    	$select1->where('td.idSituacao <> 3'); // ou cancelado
    	
    	$select1->where('td.idSituacao <> 4'); // ou sustado
    	
    	$select1->where('td.idSituacao <> 5'); // que nao foi devolvido
    	
    	$select1->where('td.idSituacao <> 6'); // que nao foi devolvido com custas
    	//acrescentar mais ocorrencias se necessario
    	//Selecione os titulos importados
    	$select2 = $this->select();
    	
    	$select2->setIntegrityCheck(false);
    	
    	$select2->from(array('pro' => 'cap_protestos'), array('idProtesto',  'data_entrada'));
    	
    	$select2->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo'));
    	
    	$select2->joinInner(array('td' => 'cap_titulos_importados'), 'pro.idTitulo = td.idTitulo', array('nome' => 'nomedevedor', 'numeroidentificacao'=>'numeroidentificacaodevedor', 'tipoidentificacao' => 'tipoidentificacaodevedor', 'codigo' => 'especietitulo'));
    	
    	$select2->joinLeft(array('esp' => 'cap_especietitulos'), 'esp.idEspecietitulo > 0', array('especie' => 'descricao'));
    	
    	$select2->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo'));
    	//Pega os dados se estiver na lista de amigos/políticos
    	$select2->joinLeft(array('amg' => 'cap_amigos'), 'amg.documento = td.numeroidentificacaodevedor', array('docamigo' => 'documento'));
    	
    	$select2->joinLeft(array('edi' => 'cap_editais'), 'pro.idProtesto = edi.idProtesto', array('idEdital'));
    	
    	$select2->where('arq.tipo <> 7'); //todos arquivos digitalizados
    	
    	$select2->where('td.idSituacao <> 1');
    	
    	$select2->where('td.idSituacao <> 3');
    	
    	$select2->where('td.idSituacao <> 4');
    	
    	$select2->where('td.idSituacao <> 5');
    	
    	$select2->where('td.idSituacao <> 6');
    	
    	$select2->where("esp.codigo = td.especietitulo");
    	
    	
    	//UNION com os dois selects
    	$select = $this->select()
    				 ->union(array($select1, $select2))
    				 ->order(array('nome'));
    	
    	/*$sql = (string) $select;    
    	print_r("<pre>");
    	print_r($sql);
    	print_r("</pre>");
    	exit;*/
    	
    	return $select;
    }

    public function selectTitulosCadastrado($data)//Retorna  se existir algum titulo cadastrado com os paramentros, pra não se cadastar 2 titulos identicos
    {
    	//Selecione os titulos digitalizados
    	$select1 = $this->select();
    	
    	$select1->setIntegrityCheck(false);
    	
    	$select1->from(array('pro' => 'cap_protestos'), array('idProtesto',  'idTitulo'));
    	
    	$select1->joinLeft(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo' => new Zend_Db_Expr("CASE pro.idArquivo WHEN 0 THEN 7 ELSE arq.tipo END")));
    	
    	$select1->joinInner(array('td' => 'cap_titulos'), 'pro.idTitulo = td.idTitulo', array('idProtocolo'));
    	
    	$select1->joinInner(array('pes' => 'cap_pessoa'), 'td.idPessoa_devedor = pes.idPessoa', array());
    	
    	$select1->where('arq.tipo = 7'); //todos arquivos digitalizados
    	
    	$select1->where("pes.numeroidentificacao = '" . $data['numeroidentificacaodevedor'] . "'");
    	
    	$select1->where("td.numerotitulo = '" . $data['numerotitulo'] . "'");
    	
    	$select1->where("td.dataemissaotitulo = '" . $data['dataemissaotitulo'] . "'");
    	
    	//Selecione os titulos importados
    	$select2 = $this->select();
    	
    	$select2->setIntegrityCheck(false);
    	
    	$select2->from(array('pro' => 'cap_protestos'), array('idProtesto',  'idTitulo'));
    	
    	$select2->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo'));
    	
    	$select2->joinInner(array('td' => 'cap_titulos_importados'), 'pro.idTitulo = td.idTitulo', array('idProtocolo'));
    	
    	$select2->where('arq.tipo <> 7'); //todos arquivos digitalizados

    	$select2->where("td.numeroidentificacaodevedor = '" . $data['numeroidentificacaodevedor'] . "'");
    	
    	$select2->where("td.numerotitulo = '" . $data['numerotitulo'] . "'");
    	
    	$select2->where("td.dataemissaotitulo = '" . $data['dataemissaotitulo'] . "'");
    	
    	//UNION com os dois selects
    	$select = $this->select()
    				 ->union(array($select1, $select2));
    	
    	//$sql = (string) $select;    
    	//print_r($sql);exit;
    	
    	return $select;
    }

	public function selectTitulosSerasa($date, $codigo='i')
    {
    	//Selecione os titulos digitalizados
    	$select1 = $this->select();
    	
    	$select1->setIntegrityCheck(false);
    	
    	$select1->from(array('ser' => 'cap_serasa'), array());
    	    	
    	$select1->joinInner(array('pro' => 'cap_protestos'), 'pro.idProtesto = ser.idProtesto', array());
    	
    	$select1->joinLeft(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array());
    	
    	$select1->joinLeft(array('liv' => 'cap_livro'), 'pro.idLivro = liv.idLivro', array('folha', 'livro'));
    	
    	$select1->joinInner(array('td' => 'cap_titulos'), 'pro.idTitulo = td.idTitulo', array('numerotitulo', 'valortitulo', 'saldotitulo', 'vencimento' => 'datavencimentotitulo', 'dataemissao' => 'dataemissaotitulo', 'tipoendosso', 'codigocedente_agencia', 'nossonumero' => 'titulo_bancario'));
    	
    	$select1->joinInner(array('pes' => 'cap_pessoa'), 'td.idPessoa_devedor = pes.idPessoa', array('nome', 'numeroidentificacao', 'tipoidentificacao' => 'tipo_identificacao'));

    	$select1->joinInner(array('sac' => 'cap_pessoa'), 'td.idPessoa_sacador = sac.idPessoa', array('nomesacador' => 'nome', 'documentosacador' => 'numeroidentificacao'));
    	
    	$select1->joinInner(array('end' => 'cap_enderecos'), 'pes.idEndereco = end.idEndereco', array('endereco' => 'rua', 'cep' => 'cep', 'bairro' => 'bairro'));    	

    	$select1->joinInner(array('cid' => 'cap_cidades'), 'end.idCidade = cid.idCidade', array('cidade' => 'nome' ));
    	
    	$select1->joinInner(array('est' => 'cap_estados'), 'est.idEstado = cid.idEstado', array('estado' => 'sigla' ));
    	
    	$select1->joinInner(array('esp' => 'cap_especietitulos'), 'td.idEspecietitulo = esp.idEspecietitulo', array('codigo'));
    	
    	$select1->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo', 'data_protocolo'));
    	
    	$select1->where('arq.tipo = 7 OR pro.idArquivo= 0'); //todos arquivos digitalizados
    	
    	$select1->where("ser.codigooperacao = '".$codigo."'");
    	
    	$select1->where("ser.data_serasa = '$date'");
    	
    	
    	//Selecione os titulos importados
    	$select2 = $this->select();
    	
    	$select2->setIntegrityCheck(false);
    	
    	$select2->from(array('ser' => 'cap_serasa'), array());
    	    	
    	$select2->joinInner(array('pro' => 'cap_protestos'), 'pro.idProtesto = ser.idProtesto', array());
    	
    	$select2->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array());
    	
    	$select2->joinLeft(array('liv' => 'cap_livro'), 'pro.idLivro = liv.idLivro', array('folha', 'livro'));
    	
    	$select2->joinInner(array('td' => 'cap_titulos_importados'), 'pro.idTitulo = td.idTitulo', array('numerotitulo' => 'numerotitulo', 'valortitulo', 'saldotitulo',  'vencimento' => 'datavencimentotitulo', 'dataemissao' => 'dataemissaotitulo', 'tipoendosso', 'codigocedente_agencia','nossonumero', 'nome' => 'nomedevedor', 'numeroidentificacao'=>'numeroidentificacaodevedor', 'tipoidentificacao' => 'tipoidentificacaodevedor', 'nomesacador', 'documentosacador', 'endereco' => 'enderecodevedor', 'cep' => 'cepdevedor', 'bairro' => 'bairrodevedor', 'cidade' => 'cidadedevedor', 'estado' => 'ufdevedor', 'codigo' => 'especietitulo'));
    	
    	$select2->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo', 'data_protocolo'));

    	$select2->where('arq.tipo <> 7'); //todos arquivos digitalizados
    	
    	$select2->where("ser.codigooperacao = '".$codigo."'");
    	
    	$select2->where("ser.data_serasa = '$date'");
    	
    	
    	
    	//UNION com os dois selects
    	$select = $this-> select()
    				   -> union(array($select1, $select2));
    	
    	/*$sql = (string) $select;    
    	print_r("<pre>");
    	print_r($sql);
    	print_r("</pre>");
    	exit;*/
    	
    	return $this->fetchAll($select);
    }
    
    
    
    /*--------------------------------*/  //Betas  
	public function selectNotificador($idProtesto='', $idSituacao=20)
    {		
		$select1 = $this->select();
		
		$select1->setIntegrityCheck(false);
		
		$select1->from(array('pro' => 'cap_protestos'), array('idProtesto', 'idTitulo', 'data_entrada'));
		
		$select1->joinLeft(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo' => new Zend_Db_Expr("CASE pro.idArquivo WHEN 0 THEN 7 ELSE arq.tipo END")));
		
		$select1->joinLeft(array('liv' => 'cap_livro'), 'pro.idLivro = liv.idLivro', array('idLivro', 'folha', 'livro'));
		
		$select1->joinInner(array('td' => 'cap_titulos'), 'pro.idTitulo = td.idTitulo', array('idSituacao', 'numerotitulo', 'valortitulo', 'saldotitulo', 'vencimento' => 'datavencimentotitulo', 'dataemissao' => 'dataemissaotitulo', 'tipoendosso', 'codigocedente_agencia', 'nossonumero' => 'titulo_bancario'));
		
		$select1->joinInner(array('pes' => 'cap_pessoa'), 'td.idPessoa_devedor = pes.idPessoa', array('nome', 'numeroidentificacao', 'tipoidentificacao' => 'tipo_identificacao'));
		
		$select1->joinInner(array('end' => 'cap_enderecos'), 'pes.idEndereco = end.idEndereco', array('endereco' => 'rua', 'numero' => 'numero', 'complemento' => 'complemento', 'cep' => 'cep', 'bairro' => 'bairro'));
		
		$select1->joinInner(array('cid' => 'cap_cidades'), 'end.idCidade = cid.idCidade',array('cidade' => 'nome' ));
		
		$select1->joinInner(array('est' => 'cap_estados'), 'est.idEstado = cid.idEstado', array('estado' => 'sigla' ));
		
		$select1->joinInner(array('ced' => 'cap_pessoa'), 'td.idPessoa_cedente = ced.idPessoa', array('nomecedente' => 'nome', 'documentocedente' => 'numeroidentificacao', 'tipoidentificacaocedente' => 'tipo_identificacao'));
		
		$select1->joinInner(array('sac' => 'cap_pessoa'), 'td.idPessoa_sacador = sac.idPessoa', array('nomesacador' => 'nome', 'documentosacador' => 'numeroidentificacao', 'tipoidentificacaosacador' => 'tipo_identificacao'));
		
		$select1->joinInner(array('pra' => 'cap_cidades'), 'td.pracaprotesto = pra.idCidade', array('pracaprotesto' => 'nome' ));
		
		$select1->joinInner(array('esp' => 'cap_especietitulos'), 'td.idEspecietitulo = esp.idEspecietitulo', array('codigo', 'especie' => 'descricao'));
		
		$select1->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo', 'data_protocolo'));
		
		$select1->joinInner(array('apr' => 'cap_pessoa'), 'td.idPessoa_apresentante = apr.idPessoa', array('nomeapresentante' => 'nome', 'documentoapresentante' => 'numeroidentificacao', 'tipoidentificacaoapresentante' => 'tipo_identificacao'));		
		
		$select1->joinInner(array('sit' => 'cap_situacao'), 'sit.idSituacao = td.idSituacao', array('situacaoatual' => 'descricao' ));
	
		$select1->where('arq.tipo = 7');
		
		$select1->where('td.idSituacao = ?', $idSituacao);
		
		if($idProtesto){
			$select1->where('pro.idProtesto = ?', $idProtesto);
		}
		
		
		$select2 = $this->select();
		
		$select2->setIntegrityCheck(false);
		
		$select2->from(array('pro' => 'cap_protestos'), array('idProtesto', 'idTitulo', 'data_entrada'));
		
		$select2->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo'));
		
		$select2->joinLeft(array('liv' => 'cap_livro'), 'pro.idLivro = liv.idLivro', array('idLivro', 'folha', 'livro'));
		
		$select2->joinInner(array('td' => 'cap_titulos_importados'), 'pro.idTitulo = td.idTitulo', array('idSituacao', 'numerotitulo', 'valortitulo', 'saldotitulo', 'vencimento' => 'datavencimentotitulo','dataemissao' => 'dataemissaotitulo', 'tipoendosso', 'codigocedente_agencia', 'nossonumero','nome' => 'nomedevedor', 'numeroidentificacao'=>'numeroidentificacaodevedor', 'tipoidentificacao' => 'tipoidentificacaodevedor', 'endereco' => 'enderecodevedor', 'numero' => new Zend_Db_Expr('null'), 'complemento' =>new Zend_Db_Expr('null'), 'cep' => 'cepdevedor', 'bairro' => 'bairrodevedor','cidade' => 'cidadedevedor', 'estado' => 'ufdevedor', 'nomecedente', 'documentocedente' => new Zend_Db_Expr('null'), 'tipoidentificacaocedente' => new Zend_Db_Expr('null'),'nomesacador', 'documentosacador', 'tipoidentificacaosacador' => new Zend_Db_Expr('null'),'pracaprotesto', 'codigo' => 'especietitulo'));
		
		$select2->joinLeft(array('esp' => 'cap_especietitulos'), 'esp.idEspecietitulo > 0', 
		array('especie' => 'descricao'));
		
		
		$select2->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', 
		array('protocolo', 'data_protocolo'));
		
		$select2->joinInner(array('cab' => 'cap_cabecalhos'), 'td.idCabecalho = cab.idCabecalho', array());
		
		$select2->joinInner(array('por' => 'cap_portadores'), 'cab.idPortador = por.idPortador', array('nomeapresentante' => 'nomeportador', 'documentoapresentante' => new Zend_Db_Expr('null'), 'tipoidentificacaoapresentante' => new Zend_Db_Expr('null')));
		
		$select2->joinInner(array('sit' => 'cap_situacao'), 'sit.idSituacao = td.idSituacao', array('situacaoatual' => 'descricao' ));		
		/////////////
		/*$select2->joinInner(array('vig' => 'cap_vigencias'), 'pro.idVigencia = vig.idVigencia', array());
		
		$select2->joinInner(array('cus' => 'cap_custas'), 'vig.idVigencia = cus.idVigencia', array('nomecusta'=>'nome', 'valor'));
    	
    	$select2->joinLeft(array('emo' => 'cap_emolumentos'), 'vig.idVigencia = emo.idVigencia', array('emolumento'));
		
    	$select2->where('emo.valor_inicial <= td.valortitulo');
    	
    	$select2->where('emo.valor_final >= td.valortitulo');*/
		
		$select2->where("esp.codigo = td.especietitulo");
    	
		$select2->where('arq.tipo <> 7');
		
		$select2->where('td.idSituacao = ?', $idSituacao);
		
    	if($idProtesto){
			$select2->where('pro.idProtesto = ?', $idProtesto);
		}
		
    	//UNION com os dois selects
    	$select = $this->select()
    				 ->union(array($select1, $select2))
    				 ->order(array('protocolo'));
    	
    	/*$sql = (string) $select;    
    	print_r("<pre>");
    	print_r($sql);
    	print_r("</pre>");
    	exit;*/
    	
    	return $this->fetchAll($select);
    }
    
	public function selectCertidao($documento)/*******BETA*********/
    {	
		$date = (date('Y') - 2) . '-' . date('m-d');
    	
		$select1 = $this->select();
		
		$select1->setIntegrityCheck(false);
		
		$select1->from(array('pro' => 'cap_protestos'), array('idProtesto', 'idTitulo', 'data_entrada'));
		
		$select1->joinLeft(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo' => new Zend_Db_Expr("CASE pro.idArquivo WHEN 0 THEN 7 ELSE arq.tipo END")));
		
		$select1->joinLeft(array('liv' => 'cap_livro'), 'pro.idLivro = liv.idLivro', array('idLivro', 'folha', 'livro'));
		
		$select1->joinInner(array('td' => 'cap_titulos'), 'pro.idTitulo = td.idTitulo', array('idSituacao', 'numerotitulo', 'valortitulo', 'saldotitulo', 'vencimento' => 'datavencimentotitulo', 'dataemissao' => 'dataemissaotitulo', 'tipoendosso', 'codigocedente_agencia', 'nossonumero' => 'titulo_bancario'));
		
		$select1->joinInner(array('pes' => 'cap_pessoa'), 'td.idPessoa_devedor = pes.idPessoa', array('nome', 'numeroidentificacao', 'tipoidentificacao' => 'tipo_identificacao'));
		
		$select1->joinInner(array('end' => 'cap_enderecos'), 'pes.idEndereco = end.idEndereco', array('endereco' => 'rua', 'numero' => 'numero', 'complemento' => 'complemento', 'cep' => 'cep', 'bairro' => 'bairro'));
		
		$select1->joinInner(array('cid' => 'cap_cidades'), 'end.idCidade = cid.idCidade',array('cidade' => 'nome' ));
		
		$select1->joinInner(array('est' => 'cap_estados'), 'est.idEstado = cid.idEstado', array('estado' => 'sigla' ));
		
		$select1->joinInner(array('ced' => 'cap_pessoa'), 'td.idPessoa_cedente = ced.idPessoa', array('nomecedente' => 'nome', 'documentocedente' => 'numeroidentificacao', 'tipoidentificacaocedente' => 'tipo_identificacao'));
		
		$select1->joinInner(array('sac' => 'cap_pessoa'), 'td.idPessoa_sacador = sac.idPessoa', array('nomesacador' => 'nome', 'documentosacador' => 'numeroidentificacao', 'tipoidentificacaosacador' => 'tipo_identificacao'));
		
		$select1->joinInner(array('pra' => 'cap_cidades'), 'td.pracaprotesto = pra.idCidade', array('pracaprotesto' => 'nome' ));
		
		$select1->joinInner(array('esp' => 'cap_especietitulos'), 'td.idEspecietitulo = esp.idEspecietitulo', array('codigo', 'especie' => 'descricao'));
		
		$select1->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo', 'data_protocolo'));
		
		$select1->joinInner(array('apr' => 'cap_pessoa'), 'td.idPessoa_apresentante = apr.idPessoa', array('nomeapresentante' => 'nome', 'documentoapresentante' => 'numeroidentificacao', 'tipoidentificacaoapresentante' => 'tipo_identificacao'));		
		
		$select1->joinInner(array('sit' => 'cap_situacao'), 'sit.idSituacao = td.idSituacao', array('situacaoatual' => 'descricao' ));
	
		$select1->where('arq.tipo = 7');
		
		$select1->where('pes.numeroidentificacao = ?', $documento);
		
		$select1->where("liv.idLivro IS NOT NULL");
		
		$select1->where("prt.data_protocolo >= '$date'");
		
		$select1->where("prt.data_protocolo <= '". date('Y-m-d') ."'");
		
		$select1->where('td.idSituacao <> 3');
    	
    	$select1->where('td.idSituacao <> 4');
    	
    	
		
		$select2 = $this->select();
		
		$select2->setIntegrityCheck(false);
		
		$select2->from(array('pro' => 'cap_protestos'), array('idProtesto', 'idTitulo', 'data_entrada'));
		
		$select2->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo'));
		
		$select2->joinLeft(array('liv' => 'cap_livro'), 'pro.idLivro = liv.idLivro', array('idLivro', 'folha', 'livro'));
		
		$select2->joinInner(array('td' => 'cap_titulos_importados'), 'pro.idTitulo = td.idTitulo', array('idSituacao', 'numerotitulo', 'valortitulo', 'saldotitulo', 'vencimento' => 'datavencimentotitulo','dataemissao' => 'dataemissaotitulo', 'tipoendosso', 'codigocedente_agencia', 'nossonumero','nome' => 'nomedevedor', 'numeroidentificacao'=>'numeroidentificacaodevedor', 'tipoidentificacao' => 'tipoidentificacaodevedor', 'endereco' => 'enderecodevedor', 'numero' => new Zend_Db_Expr('null'), 'complemento' =>new Zend_Db_Expr('null'), 'cep' => 'cepdevedor', 'bairro' => 'bairrodevedor','cidade' => 'cidadedevedor', 'estado' => 'ufdevedor', 'nomecedente', 'documentocedente' => new Zend_Db_Expr('null'), 'tipoidentificacaocedente' => new Zend_Db_Expr('null'),'nomesacador', 'documentosacador', 'tipoidentificacaosacador' => new Zend_Db_Expr('null'),'pracaprotesto', 'codigo' => 'especietitulo'));
		
		$select2->joinLeft(array('esp' => 'cap_especietitulos'), 'esp.idEspecietitulo > 0', array('especie' => 'descricao'));
				
		$select2->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo', 'data_protocolo'));
		
		$select2->joinInner(array('cab' => 'cap_cabecalhos'), 'td.idCabecalho = cab.idCabecalho', array());
		
		$select2->joinInner(array('por' => 'cap_portadores'), 'cab.idPortador = por.idPortador', array('nomeapresentante' => 'nomeportador', 'documentoapresentante' => new Zend_Db_Expr('null'), 'tipoidentificacaoapresentante' => new Zend_Db_Expr('null')));
		
		$select2->joinInner(array('sit' => 'cap_situacao'), 'sit.idSituacao = td.idSituacao', array('situacaoatual' => 'descricao' ));		
				
		$select2->where("esp.codigo = td.especietitulo");
    	
		$select2->where('arq.tipo <> 7');
		
		$select2->where("td.numeroidentificacaodevedor = '$documento'");
		
		$select2->where("liv.idLivro IS NOT NULL");
		
		$select2->where("prt.data_protocolo >= '$date'");
		
		$select2->where("prt.data_protocolo <= '". date('Y-m-d') ."'");
		
    	$select2->where('td.idSituacao <> 3');
    	
    	$select2->where('td.idSituacao <> 4');
		
    	//UNION com os dois selects
    	$select = $this->select()
    				 ->union(array($select1, $select2))
    				 ->order(array('protocolo'));
    	
    	/*$sql = (string) $select;    
    	print_r("<pre>");
    	print_r($sql);
    	print_r("</pre>");
    	exit;*/
    	
    	return $this->fetchAll($select);
    }

    
	public function selectInteiroTeor($protocolo)/*******BETA*********/
    {	
		$date = (date('Y') - 2) . '-' . date('m-d');
    	
		$select1 = $this->select();
		
		$select1->setIntegrityCheck(false);
		
		$select1->from(array('pro' => 'cap_protestos'), array('idProtesto', 'idTitulo', 'data_entrada'));
		
		$select1->joinLeft(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo' => new Zend_Db_Expr("CASE pro.idArquivo WHEN 0 THEN 7 ELSE arq.tipo END")));
		
		$select1->joinLeft(array('liv' => 'cap_livro'), 'pro.idLivro = liv.idLivro', array('idLivro', 'folha', 'livro', 'data_protesto'));
		
		$select1->joinInner(array('td' => 'cap_titulos'), 'pro.idTitulo = td.idTitulo', array('idSituacao', 'numerotitulo', 'valortitulo', 'saldotitulo', 'vencimento' => 'datavencimentotitulo', 'dataemissao' => 'dataemissaotitulo', 'tipoendosso', 'codigocedente_agencia', 'nossonumero' => 'titulo_bancario'));
		
		$select1->joinInner(array('pes' => 'cap_pessoa'), 'td.idPessoa_devedor = pes.idPessoa', array('nome', 'numeroidentificacao', 'tipoidentificacao' => 'tipo_identificacao'));
		
		$select1->joinInner(array('end' => 'cap_enderecos'), 'pes.idEndereco = end.idEndereco', array('endereco' => 'rua', 'numero' => 'numero', 'complemento' => 'complemento', 'cep' => 'cep', 'bairro' => 'bairro'));
		
		$select1->joinInner(array('cid' => 'cap_cidades'), 'end.idCidade = cid.idCidade',array('cidade' => 'nome' ));
		
		$select1->joinInner(array('est' => 'cap_estados'), 'est.idEstado = cid.idEstado', array('estado' => 'sigla' ));
		
		$select1->joinInner(array('ced' => 'cap_pessoa'), 'td.idPessoa_cedente = ced.idPessoa', array('nomecedente' => 'nome', 'documentocedente' => 'numeroidentificacao', 'tipoidentificacaocedente' => 'tipo_identificacao'));
		
		$select1->joinInner(array('sac' => 'cap_pessoa'), 'td.idPessoa_sacador = sac.idPessoa', array('nomesacador' => 'nome', 'documentosacador' => 'numeroidentificacao', 'tipoidentificacaosacador' => 'tipo_identificacao'));
		
		$select1->joinInner(array('pra' => 'cap_cidades'), 'td.pracaprotesto = pra.idCidade', array('pracaprotesto' => 'nome' ));
		
		$select1->joinInner(array('esp' => 'cap_especietitulos'), 'td.idEspecietitulo = esp.idEspecietitulo', array('codigo', 'especie' => 'descricao'));
		
		$select1->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo', 'data_protocolo'));
		
		$select1->joinInner(array('apr' => 'cap_pessoa'), 'td.idPessoa_apresentante = apr.idPessoa', array('nomeapresentante' => 'nome', 'documentoapresentante' => 'numeroidentificacao', 'tipoidentificacaoapresentante' => 'tipo_identificacao'));		
		
		$select1->joinInner(array('sit' => 'cap_situacao'), 'sit.idSituacao = td.idSituacao', array('situacaoatual' => 'descricao' ));
	
		$select1->where('arq.tipo = 7');
				
		$select1->where("prt.protocolo = '$protocolo'");
		
    	
    	
		
		$select2 = $this->select();
		
		$select2->setIntegrityCheck(false);
		
		$select2->from(array('pro' => 'cap_protestos'), array('idProtesto', 'idTitulo', 'data_entrada'));
		
		$select2->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo'));
		
		$select2->joinLeft(array('liv' => 'cap_livro'), 'pro.idLivro = liv.idLivro', array('idLivro', 'folha', 'livro', 'data_protesto'));
		
		$select2->joinInner(array('td' => 'cap_titulos_importados'), 'pro.idTitulo = td.idTitulo', array('idSituacao', 'numerotitulo', 'valortitulo', 'saldotitulo', 'vencimento' => 'datavencimentotitulo','dataemissao' => 'dataemissaotitulo', 'tipoendosso', 'codigocedente_agencia', 'nossonumero','nome' => 'nomedevedor', 'numeroidentificacao'=>'numeroidentificacaodevedor', 'tipoidentificacao' => 'tipoidentificacaodevedor', 'endereco' => 'enderecodevedor', 'numero' => new Zend_Db_Expr('null'), 'complemento' =>new Zend_Db_Expr('null'), 'cep' => 'cepdevedor', 'bairro' => 'bairrodevedor','cidade' => 'cidadedevedor', 'estado' => 'ufdevedor', 'nomecedente', 'documentocedente' => new Zend_Db_Expr('null'), 'tipoidentificacaocedente' => new Zend_Db_Expr('null'),'nomesacador', 'documentosacador', 'tipoidentificacaosacador' => new Zend_Db_Expr('null'),'pracaprotesto', 'codigo' => 'especietitulo'));
		
		$select2->joinLeft(array('esp' => 'cap_especietitulos'), 'esp.idEspecietitulo > 0', array('especie' => 'descricao'));
				
		$select2->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo', 'data_protocolo'));
		
		$select2->joinInner(array('cab' => 'cap_cabecalhos'), 'td.idCabecalho = cab.idCabecalho', array());
		
		$select2->joinInner(array('por' => 'cap_portadores'), 'cab.idPortador = por.idPortador', array('nomeapresentante' => 'nomeportador', 'documentoapresentante' => new Zend_Db_Expr('null'), 'tipoidentificacaoapresentante' => new Zend_Db_Expr('null')));
		
		$select2->joinInner(array('sit' => 'cap_situacao'), 'sit.idSituacao = td.idSituacao', array('situacaoatual' => 'descricao' ));		
				
		$select2->where("esp.codigo = td.especietitulo");
    	
		$select2->where('arq.tipo <> 7');
		
		$select2->where("prt.protocolo = '$protocolo'");
		
    	//UNION com os dois selects
    	$select = $this->select()
    				 ->union(array($select1, $select2))
    				 ->order(array('protocolo'));
    	
    	/*$sql = (string) $select;    
    	print_r("<pre>");
    	print_r($sql);
    	print_r("</pre>");
    	exit;*/
    	
    	return $this->fetchAll($select);
    }
    
	public function selectTitulosSuspensao()
    {
    	//Selecione os titulos digitalizados
    	$select1 = $this->select();
    	
    	$select1->setIntegrityCheck(false);
    	
    	$select1->from(array('pro' => 'cap_protestos'), array('idProtesto',  'data_entrada'));
    	
    	$select1->joinLeft(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo' => new Zend_Db_Expr("CASE pro.idArquivo WHEN 0 THEN 7 ELSE arq.tipo END")));
    	
    	$select1->joinInner(array('td' => 'cap_titulos'), 'pro.idTitulo = td.idTitulo', array('valortitulo'));
    	
    	$select1->joinInner(array('pes' => 'cap_pessoa'), 'td.idPessoa_devedor = pes.idPessoa', array('nome', 'numeroidentificacao', 'tipoidentificacao' => 'tipo_identificacao'));
    	
    	$select1->joinInner(array('esp' => 'cap_especietitulos'), 'td.idEspecietitulo = esp.idEspecietitulo', array('codigo', 'especie' => 'descricao'));
    	
    	$select1->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo'));
    	
    	//Pega os dados se estiver na lista de amigos/políticos    	
    	$select1->joinLeft(array('amg' => 'cap_amigos'), 'amg.documento = pes.numeroidentificacao', array('docamigo' => 'documento'));
    	
    	//Pega os dados se estiver em algum edital    	
    	$select1->joinLeft(array('edi' => 'cap_editais'), 'pro.idProtesto = edi.idProtesto', array('idEdital'));
    	    	
    	$select1->joinInner(array('sit' => 'cap_situacao'), 'sit.idSituacao = td.idSituacao', array('situacaoatual' => 'descricao' ));
    	
    	$select1->where('arq.tipo = 7 OR pro.idArquivo= 0'); //todos arquivos digitalizados
    	
    	$select1->where('td.idSituacao > 19');
    	
    	$select1->where('td.idSituacao < 23');
    	
    	
    	//Selecione os titulos importados
    	$select2 = $this->select();
    	
    	$select2->setIntegrityCheck(false);
    	
    	$select2->from(array('pro' => 'cap_protestos'), array('idProtesto',  'data_entrada'));
    	
    	$select2->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('tipo'));
    	
    	$select2->joinInner(array('td' => 'cap_titulos_importados'), 'pro.idTitulo = td.idTitulo', array('valortitulo', 'nome' => 'nomedevedor', 'numeroidentificacao'=>'numeroidentificacaodevedor', 'tipoidentificacao' => 'tipoidentificacaodevedor', 'codigo' => 'especietitulo'));
    	
    	$select2->joinLeft(array('esp' => 'cap_especietitulos'), 'esp.idEspecietitulo > 0', array('especie' => 'descricao'));
    	
    	$select2->joinInner(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo'));
    	//Pega os dados se estiver na lista de amigos/políticos
    	$select2->joinLeft(array('amg' => 'cap_amigos'), 'amg.documento = td.numeroidentificacaodevedor', array('docamigo' => 'documento'));
    	
    	$select2->joinLeft(array('edi' => 'cap_editais'), 'pro.idProtesto = edi.idProtesto', array('idEdital'));
    	
    	$select2->joinInner(array('sit' => 'cap_situacao'), 'sit.idSituacao = td.idSituacao', array('situacaoatual' => 'descricao' ));
    	
    	$select2->where('arq.tipo <> 7'); //todos arquivos digitalizados
    	
    	$select2->where('td.idSituacao > 19');
    	
    	$select2->where('td.idSituacao < 23');
    	
    	$select2->where("esp.codigo = td.especietitulo");
    	
    	
    	//UNION com os dois selects
    	$select = $this->select()
    				 ->union(array($select1, $select2))
    				 ->order(array('nome'));
    	
    	/*$sql = (string) $select;    
    	print_r("<pre>");
    	print_r($sql);
    	print_r("</pre>");
    	exit;*/
    	
    	return $select;
    }
    
}

