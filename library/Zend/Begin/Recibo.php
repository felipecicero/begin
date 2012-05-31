<?php 
require('fpdf/fpdf.php');

class Begin_Recibo extends FPDF{
	
	public function gerarRecibo($data_devedor, $data_cartorio){

		$endereco_devedor = trim($data_devedor->endereco) . ", " . trim($data_devedor->bairro) . ", " . trim($data_devedor->cidade) . "-" . trim($data_devedor->estado) . " - " . $this->ajustaCEP(trim($data_devedor->cep));
		$doccedente='';
		if(isset($data_devedor->tipoidentificacaosacador))
			$tipoidsacador = $data_devedor->tipoidentificacaosacador;
		else
			$tipoidsacador = 1;
			
		if(trim($data_devedor->nomecedente) == trim($data_devedor->nomesacador) ){
			$doccedente = $this->ajustaCPF_CNPJ($data_devedor->documentosacador, $tipoidsacador);
		}
		
		if(isset($data_devedor->nomeapresentante))
			$nomepresentante = trim($data_devedor->nomeapresentante);
		else
			$nomepresentante = trim($data_devedor->nomesacador);

		if(isset($data_devedor->tipoidentificacaoapresentante))
			$tipoidapresentante = $data_devedor->tipoidentificacaoapresentante;
		else
			$tipoidapresentante = $tipoidsacador;
			
		if(isset($data_devedor->documentoapresentante))
			$docpresentante = $this->ajustaCPF_CNPJ($data_devedor->documentoapresentante, $tipoidapresentante);
		else
			$docpresentante = '';
		if($data_devedor->tipoendosso == 'M')
			$tipoendosso = 'Mandato';
		else if($data_devedor->tipoendosso == 'T')
			$tipoendosso = 'Translativo';
		else
			$tipoendosso = '';
			
		
		$pdf= new FPDF("P","cm","A4");
	
		$pdf->AddPage();	
		$pdf->Image('images/brasao.gif', 3, 1, 2.3);
		$pdf->Image('images/logo.gif', 15, 1.2, 2);
		///////////////////////
		$pdf->SetFont('times', 'I', 34);
		$pdf->Cell(18.6, 5, "", 1, 0, 'C');
		$pdf->SetY(1.5);
		$pdf->Cell(18.6, 1, "MOROMIZATO",0,1,'C');
		$pdf->SetFont('times', 'IB', 16);
		$pdf->SetY(2.5);
		$pdf->Cell(18.6, 1, "Cartório de Tabelionato de Protestos",0,1,'C');
		$pdf->SetY(3);
		$pdf->SetFont('times', '', 12);
		$pdf->Cell(18.6, 1, $data_cartorio->nome,0,1,'C');
		$pdf->SetY(3.5);
		$pdf->SetFont('times', 'B', 10);
		$pdf->Cell(18.6, 1, $data_cartorio->tabeliao,0,1,'C');
		$pdf->SetY(3.9);
		$pdf->SetFont('times', 'I', 10);
		$pdf->Cell(18.6, 1, "Oficial/Tabelião",0,1,'C');

		$pdf->SetXY(3, 4.3);
		$pdf->SetFont('times', 'B', 10);
		$pdf->Cell(5, 1, $data_cartorio->substituto,0,1,'C');
		$pdf->SetXY(3, 4.7);
		$pdf->SetFont('times', 'I', 10);
		$pdf->Cell(5, 1, "Suboficial/Tabelião Substituto",0,1,'C');
		
		$pdf->SetXY(12, 4.3);
		$pdf->SetFont('times', 'B', 10);
		$pdf->Cell(5, 1, $data_cartorio->escrevente,0,1,'C');
		$pdf->SetXY(12, 4.7);
		$pdf->SetFont('times', 'I', 10);
		$pdf->Cell(5, 1, "Escrevente Autorizado",0,1,'C');
		
		$pdf->SetY(5.3);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(18.6, 1, $data_cartorio->endereco . " " . $data_cartorio->complemento . " - " . $data_cartorio->bairro . " - Fone/Fax: " . $this->ajustaTelefone($data_cartorio->telefone) . " - CEP: " . $this->ajustaCEP($data_cartorio->cep) . " - " . $data_cartorio->cidade . "-". $data_cartorio->uf,0,0,'C');
		
		////////////////////////////
		$pdf->SetFont('times', 'B', 19);
		$pdf->SetY(6.3);
		$pdf->Cell(18.6, 1, "RECIBO",0,1,'C');		
		
		$pdf->SetFillColor(1, 1, 1);
		$pdf->SetTextColor(256, 256, 256);		
		$pdf->SetFont('times', 'B', 10);
		$pdf->SetXY(16.6, 6.2);
		$pdf->Cell(3, 0.8, "R$ " . $this->converte($data_devedor->valortitulo),1, 1, 'C', true);
		
		$pdf->SetTextColor(0);
		$pdf->SetY(7.2);
		$pdf->Cell(18.6, 1.5, "", 1, 1, 'C');
		
		$pdf->SetFont('arial', '', 11);
		$pdf->SetY(7.3);
		$pdf->Cell(18.6, 0.5, "Recebemos do(a) Sr.(a)", 0, 1, 'L');		
		$pdf->SetY(8);
		$pdf->Cell(18.6, 0.5, $data_devedor->nome, 0, 1, 'L');
		
		$pdf->SetY(8.8);
		$pdf->Cell(18.6, 2.5, "", 1, 1, 'C');		
		$pdf->SetY(8.8);
		$pdf->Cell(18.6, 1, "RECEBEMOS A IMPORTÂNCIA E O VALOR DESCRITO.", 0, 1, 'L');
		
		$pdf->SetFont('arial', '', 10);		
		$pdf->SetY(9.5);
		$pdf->Cell(18.6, 0.5, "Referente a emolumentos fixados no regimento de custas.", 0, 1, 'L');
		$pdf->SetY(10);
		$pdf->Cell(18.6, 0.5, "Registro de contrato de abertura de crédito fixo/conta corrente garantida ou de depósito.", 0, 1, 'L');
		
		$pdf->SetY(11.4);
		$pdf->Cell(18.6, 0.7, $data_cartorio->cidade . " - " . $data_cartorio->uf . ", " . $this->_data(), 1, 1, 'L');
		
		$pdf->SetY(12.2);
		$pdf->Cell(18.6, 1, "Entregue a", 1, 1, 'L');
				
		$pdf->SetXY(3, 12.1);
		$pdf->Cell(7, 1, "__________________________________________________", 0, 1, 'L');
		$pdf->SetXY(6, 12.5);
		$pdf->SetFont('arial', 'B', 7);
		$pdf->Cell(7, 1, "Assinar por extenso e legível", 0, 1, 'L');
		
		$pdf->SetFont('arial', '', 10);
		$pdf->SetXY(13, 12.1);
		$pdf->Cell(5, 1, "Documento:", 0, 1, 'L');
		$pdf->SetXY(15, 12.1);
		$pdf->Cell(5, 1, "____________________", 0, 1, 'L');
		
		$pdf->SetFont('arial', 'B', 10);
		$pdf->SetY(13);
		$pdf->Cell(5, 1, "1º VIA - ", 0, 1, 'L');
		
		////////////////////////////////
		////////// 2ª via /////////////
		///////////////////////////////
		
		$pdf->Image('images/brasao.gif', 3, 14.5, 2.3);
		$pdf->Image('images/logo.gif', 15, 14.7, 2);
		///////////////////////
		$pdf->SetY(14.5);
		$pdf->SetFont('times', 'I', 34);
		$pdf->Cell(18.6, 5, "", 1, 0, 'C');
		$pdf->SetY(15);
		$pdf->Cell(18.6, 1, "MOROMIZATO",0,1,'C');
		$pdf->SetFont('times', 'IB', 16);
		$pdf->SetY(16);
		$pdf->Cell(18.6, 1, "Cartório de Tabelionato de Protestos",0,1,'C');
		$pdf->SetY(16.5);
		$pdf->SetFont('times', '', 12);
		$pdf->Cell(18.6, 1, $data_cartorio->nome,0,1,'C');
		$pdf->SetY(17);
		$pdf->SetFont('times', 'B', 10);
		$pdf->Cell(18.6, 1, $data_cartorio->tabeliao,0,1,'C');
		$pdf->SetY(17.4);
		$pdf->SetFont('times', 'I', 10);
		$pdf->Cell(18.6, 1, "Oficial/Tabelião",0,1,'C');

		$pdf->SetXY(3, 17.8);
		$pdf->SetFont('times', 'B', 10);
		$pdf->Cell(5, 1, $data_cartorio->substituto,0,1,'C');
		$pdf->SetXY(3, 18.2);
		$pdf->SetFont('times', 'I', 10);
		$pdf->Cell(5, 1, "Suboficial/Tabelião Substituto",0,1,'C');
		
		$pdf->SetXY(12, 17.8);
		$pdf->SetFont('times', 'B', 10);
		$pdf->Cell(5, 1, $data_cartorio->escrevente,0,1,'C');
		$pdf->SetXY(12, 18.2);
		$pdf->SetFont('times', 'I', 10);
		$pdf->Cell(5, 1, "Escrevente Autorizado",0,1,'C');
		
		$pdf->SetY(18.8);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(18.6, 1, $data_cartorio->endereco . " " . $data_cartorio->complemento . " - " . $data_cartorio->bairro . " - Fone/Fax: " . $this->ajustaTelefone($data_cartorio->telefone) . " - CEP: " . $this->ajustaCEP($data_cartorio->cep) . " - " . $data_cartorio->cidade . "-". $data_cartorio->uf,0,0,'C');
		
		////////////////////////////
		$pdf->SetFont('times', 'B', 19);
		$pdf->SetY(19.8);
		$pdf->Cell(18.6, 1, "RECIBO",0,1,'C');		
		
		$pdf->SetFillColor(1, 1, 1);
		$pdf->SetTextColor(256, 256, 256);		
		$pdf->SetFont('times', 'B', 10);
		$pdf->SetXY(16.6, 19.7);
		$pdf->Cell(3, 0.8, "R$ " . $this->converte($data_devedor->valortitulo), 1, 1, 'C', true);
		
		$pdf->SetTextColor(0);
		$pdf->SetY(20.7);
		$pdf->Cell(18.6, 1.5, "", 1, 1, 'C');
		
		$pdf->SetFont('arial', '', 11);
		$pdf->SetY(20.8);
		$pdf->Cell(18.6, 0.5, "Recebemos do(a) Sr.(a)", 0, 1, 'L');		
		$pdf->SetY(21.5);
		$pdf->Cell(18.6, 0.5, $data_devedor->nome, 0, 1, 'L');
		
		$pdf->SetY(22.3);
		$pdf->Cell(18.6, 2.5, "", 1, 1, 'C');		
		$pdf->SetY(22.3);
		$pdf->Cell(18.6, 1, "RECEBEMOS A IMPORTÂNCIA E O VALOR DESCRITO.", 0, 1, 'L');
		
		$pdf->SetFont('arial', '', 10);		
		$pdf->SetY(23);
		$pdf->Cell(18.6, 0.5, "Referente a emolumentos fixados no regimento de custas.", 0, 1, 'L');
		$pdf->SetY(23.5);
		$pdf->Cell(18.6, 0.5, "Registro de contrato de abertura de crédito fixo/conta corrente garantida ou de depósito.", 0, 1, 'L');
		
		$pdf->SetY(24.9);
		$pdf->Cell(18.6, 0.7, $data_cartorio->cidade . " - " . $data_cartorio->uf . ", " . $this->_data(), 1, 1, 'L');
		
		$pdf->SetY(25.7);
		$pdf->Cell(18.6, 1, "Entregue a", 1, 1, 'L');
		
		$pdf->SetXY(3, 25.6);
		$pdf->Cell(7, 1, "__________________________________________________", 0, 1, 'L');
		$pdf->SetXY(6, 26);
		$pdf->SetFont('arial', 'B', 7);
		$pdf->Cell(7, 1, "Assinar por extenso e legível", 0, 1, 'L');
		
		$pdf->SetFont('arial', '', 10);
		$pdf->SetXY(13, 25.6);
		$pdf->Cell(5, 1, "Documento:", 0, 1, 'L');
		$pdf->SetXY(15, 25.6);
		$pdf->Cell(5, 1, "____________________", 0, 1, 'L');
		
		$pdf->SetFont('arial', 'B', 10);
		$pdf->SetY(26.6);
		$pdf->Cell(5, 1, "2º VIA - CARTÓRIO", 0, 1, 'L');
		
		
		
								
		$pdf->Output("RECIBO_" . $data_devedor->idProtesto . ".pdf", "I");
		exit;
	}
	
	/**Converter um valor monetario 0.000,00*/	
	public function converte($dado){			
		if ($dado != ""){	
			return number_format($dado, 2, ",", ".");	
		} 
		else {	
			return "0,00";	
		}	
	}
     
	function getDataTimestamp($date){
		$date = substr($date, 0, 10);
		
		$date =implode("/", array_reverse(explode("-", $date )));
		
		return $date;
	}

	function ajustaCEP($data){
		$dados = str_split($data);
		$aux = '';
				
		for($i = 0; $i < count($dados); $i++){
			
			if($i==2){
				$aux = $aux . ".";
			}
			if($i==5){
				$aux = $aux . "-";
			}

			$aux = $aux . $dados[$i];
		}
		
		return $aux;
	}
	
	function ajustaCPF_CNPJ($data, $tipo=1){
		$dados = str_split($data);
		$aux = '';
				
		if($tipo == 1){//CNPJ
			for($i = 0; $i < count($dados); $i++){
				
				if($i==2 || $i==5  ){
					$aux = $aux . ".";
				}
				if($i==8){
					$aux = $aux . "/";
				}
				if($i==12){
					$aux = $aux . "-";
				}
	
				$aux = $aux . $dados[$i];
			}
		}
		
		if($tipo == 2){//CPF
			for($i = 0; $i < count($dados); $i++){
				
				if($i==3 || $i==6  ){
					$aux = $aux . ".";
				}				
				if($i==9){
					$aux = $aux . "-";
				}
	
				$aux = $aux . $dados[$i];
			}
		}
		
		return $aux;
	}

	function ajustaTelefone($data){
		$dados = str_split($data);
		$aux = '';
				
		for($i = 0; $i < count($dados); $i++){
			
			if($i == 0){
				$aux .= "(";
			}
			
			if($i==2){
				$aux = $aux . ") ";
			}
			if($i==6){
				$aux = $aux . "-";
			}
			
			$aux = $aux . $dados[$i];
		}
		
		return $aux;
	}
	 
	function extenso($valor=0, $maiusculas=false) {
		//print_r($valor);exit;
		$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
		$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
	
		$c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
		$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
		$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
		$u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
	
		$z=0;
		$rt = '';
	
		$valor = number_format($valor, 2, ".", ".");
		$inteiro = explode(".", $valor);
		for($i=0;$i<count($inteiro);$i++)
			for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
				$inteiro[$i] = "0".$inteiro[$i];
	
	 	$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
		for ($i=0;$i<count($inteiro);$i++) {
			$valor = $inteiro[$i];
			$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
			$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
			$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
	
			$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&$ru) ? " e " : "").$ru;
			$t = count($inteiro)-1-$i;
			$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
			if ($valor == "000")$z++; elseif ($z > 0) $z--;
			if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
			if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) &&($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
		}

         if(!$maiusculas){
             return($rt ? $rt : "zero");
         } else {
             return (strtoupper($rt) ? strtoupper($rt) : "ZERO");
         }

	}
	
	function _data(){
			switch (date('m')){
				case "01": $mes = " de janeiro de "; break;
				case "02": $mes = " de fevereiro de "; break;
				case "03": $mes = " de março de "; break;
				case "04": $mes = " de abril de "; break;
				case "05": $mes = " de maio de "; break;
				case "06": $mes = " de junho de "; break;
				case "07": $mes = " de julho de "; break;
				case "08": $mes = " de agosto de "; break;
				case "09": $mes = " de setembro de "; break;
				case "10": $mes = " de outubro de "; break;
				case "11": $mes = " de novembro de "; break;
				case "12": $mes = " de dezembro de "; break;
				default: $mes = " de _______________ de ";
			}
			
		return (date('d') . $mes . date('Y') . ".");
	}

}