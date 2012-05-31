<?php

class TituloImportado extends Zend_Db_Table_Abstract
{

	protected $_name = 'cap_titulos_importados';
	
	public function getTitulosByProtocolo($protocolo, $numTitulo)
    {
    	//Selecione os titulos digitalizados
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	
    	$select->from(array('td' => 'cap_titulos_importados'), array('idTitulo'));
    	
    	$select->joinInner(array('pro' => 'cap_protocolos'), 'pro.idProtocolo = td.idProtocolo', array());
    	
    	$select->where("pro.protocolo = '$protocolo'");
    	
    	$select->where("td.numerotitulo = '$numTitulo'");
    	
    	//$sql = (string) $select;    
    	//print_r($sql);exit;
    	
    	$data = $this->fetchAll($select);
    	
    	return $data->Current();
    }
    
	public function getSituacaoTitulo($idTitulo)
    {
    	//Selecione os titulos digitalizados
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);
    	    	
    	$select->where('idTitulo = ?', $idTitulo);
    	
    	//$sql = (string) $select;    
    	//print_r($sql);exit;
    	
    	$data = $this->fetchAll($select);
    	
    	return $data->Current()->idSituacao;
    }
    
	public function getTitulo($idCabecalho)//Pega o título para confirmacao
    {
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);

    	$select->from(array('td' => 'cap_titulos_importados'), array('*'));
    	
    	$select->joinInner(array('cab' => 'cap_cabecalhos'), 'td.idCabecalho = cab.idCabecalho', array());

    	$select->joinInner(array('por' => 'cap_portadores'), 'cab.idPortador = por.idPortador', array('idPortador', 'numerocodigoportador', 'nomeportador'));
    	
    	$select->joinLeft(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo', 'data_protocolo'));
    	
    	$select->joinInner(array('pro' => 'cap_protestos'), 'td.idTitulo = pro.idTitulo',array());
    	
    	$select->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('arquivo'));
    	
    	$select->where('td.idCabecalho = ?', $idCabecalho);
    	
    	//$sql = (string) $select;    
    	//print_r($sql);exit;
    	
    	$data = $this->fetchAll($select);
    	
    	return $data;
    	
    }

    public function getTituloRetorno($idPortador){
    	
    	$intervalo = $this->verificaDiasUteis();
    	  					
    	$select = $this->select();
    	
    	$select->setIntegrityCheck(false);

    	$select->from(array('td' => 'cap_titulos_importados'), array('*'));
    	
    	$select->joinInner(array('por' => 'cap_portadores'), '', array('idPortador', 'numerocodigoportador', 'nomeportador'));
    	
    	$select->joinLeft(array('prt' => 'cap_protocolos'), 'td.idProtocolo = prt.idProtocolo', array('protocolo', 'data_protocolo'));
    	
    	$select->joinInner(array('pro' => 'cap_protestos'), 'td.idTitulo = pro.idTitulo',array());
    	
    	$select->joinInner(array('sit' => 'cap_situacao'), 'td.idSituacao = sit.idSituacao',array( 'tipoocorrencia' => 'codigo', 'descricao'));
    	
    	$select->joinInner(array('arq' => 'cap_arquivos'), 'pro.idArquivo = arq.idArquivo', array('arquivo'));
    	
    	$select->joinInner(array('his' => 'cap_historico'), 'pro.idProtesto = his.idProtesto', array('data_historico', 'diahistorico' => 'DAYNAME(data_historico)'));
    	
    	$select->where('por.idPortador = ?', $idPortador);
    	
    	$select->where('his.idSituacao > 1'); //titulos que foram devolvidos, pagos, protestado, cancelado, suspenso, sustado
    	
    	$select->where("TO_DAYS(NOW()) - TO_DAYS(his.data_historico) <= " . $intervalo);//busca os titulos dos ultimos 5 dias uteis
    	
    	$select->where("TO_DAYS(NOW()) - TO_DAYS(his.data_historico) > 0");
    	
    	$select->where("td.idSituacao <> 20");//exclui os titulos em aberto
    	
    	$select->where("td.idSituacao <> 21"); // q estao sendo notificados
    	
    	$select->where("td.idSituacao <> 22"); // ou que ja receberam o aceite mas nao foram protestados
    	//$sql = (string) $select;    
    	//print_r($sql);exit;    	
    	$data = $this->fetchAll($select);
    	
    	return $data;
    }
    
    /**
     * Verifica-se quantos dias deve-se buscar no banco de dados,
     * considerando se é feriado acrescenta-se 1 dia no intervalo
     * da data, e caso seja final de semana acrescenta-se 2 dias,
     * totalizando nesse caso 5 dias úteis.
     */
	public function verificaDiasUteis($dias=5){
    	
    	$model_feriado = new Feriado();

    	$intervalo = $dias;
    	$diasUteis = 0;
		
    	for($i=1; $i <= $dias; $i++){
			$date = date('Y-m-d', strtotime("-$i days"));
			
			$feriado = $model_feriado->getFeriadosbyDate($date); //verifica se a data é um feriado 
			
    		$select = $this->select(); //verifica se a data é um final de semana
				   	  $select->setIntegrityCheck(false);
    			      $select->from(array('td' => 'cap_titulos_importados'), array( 'dia' => 'DISTINCT(DAYNAME("'.$date.'"))'));
    		$dia = $this->fetchAll($select);
    		
    		if($dia->Current()->dia == 'Sunday'){ //se for final de semana, acrescenta 2 dias ao intervalo
				$intervalo = $intervalo + 2;
				$dias++;
			}else if(count($feriado) > 0){ // se for um feriado acrescenta um dia ao feriado
				$intervalo++;
				$dias++;				
			}else{
				$diasUteis++;
			}
		}
    	
		/*
		 if($diasUteis > 5){
		 	//Rertona-se um erro
		 } 
		*/
		
    	return   $intervalo;  	
    }
    
}

