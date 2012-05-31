<?php 
require('fpdf/fpdf.php');

class Begin_Notificacao extends FPDF{
	
	public function gerarNotificacao($data_devedor, $data_cartorio){
	
		$model_custas = new Custa();
		$data_custas = $model_custas->getCustas($data_devedor->idProtesto, $data_devedor->valortitulo);
		
		$endereco_devedor = trim($data_devedor->endereco) . ", " . trim($data_devedor->bairro);
		$endereco_devedor2 = trim($data_devedor->cidade) . "-" . trim($data_devedor->estado) . " - " . $this->ajustaCEP(trim($data_devedor->cep));
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
			
		
		$pdf= new FPDF("P","cm","A4");
	
		$pdf->AddPage();	
		$pdf->Image('images/brasao.gif', 1.2, 1.2, 2.3);
		///////////////////////
		$pdf->SetFont('arial', 'B', 10);
		$pdf->Cell(15, 2.7, "", 1, 0, 'C');
		$pdf->SetY(1);
		$pdf->Cell(17, 1, "TABELIONATO DE PROTESTO DE TÍTULOS DE " . strtoupper($data_cartorio->cidade),0,1,'C');
		$pdf->SetFont('arial', 'B', 9);
		$pdf->SetY(1.8);
		$pdf->Cell(17, 1, $data_cartorio->tabeliao,0,1,'C');
		$pdf->SetY(2.3);
		$pdf->Cell(17, 1, "Tabelião",0,1,'C');		
		$pdf->SetY(3);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(17, 1, $data_cartorio->endereco . " " . $data_cartorio->complemento . " - " . $data_cartorio->bairro . " - Fone/Fax: " . $this->ajustaTelefone($data_cartorio->telefone) . " - CEP: " . $data_cartorio->cep . " - " . $data_cartorio->cidade . "-". $data_cartorio->uf,0,0,'C');
		//////////////////
		$pdf->SetXY(16.1, 1);
		$pdf->SetFont('arial', 'B', 10);
		$pdf->Cell(3.5, 0.9, "1ª Via", 1, 0, 'C');		
		$pdf->SetXY(16.1, 1.9);
		$pdf->SetFont('arial', '', 10);
		$pdf->Cell(3.5, 0.9, "PROTOCOLO", 1, 0, 'C');		
		$pdf->SetXY(16.1, 2.8);
		$pdf->SetFont('arial', 'B', 10);
		$pdf->Cell(3.5, 0.9, $data_devedor->protocolo, 1, 0, 'C');
		////////////////////////////
		$pdf->SetFont('arial','',10);
		$texto = "Levamos ao conhecimento de V.S. que se acha devidamente apontado neste cartório, o título mencionado. O pagamento deverá ser efetuado até o 3º dia útil da protocolização (Lei nº 9492 de 10/09/97), sendo protestado caso não haja o devido pagamento no prazo mencionado.";		
		$pdf->SetXY(1, 3.8);
		$pdf->MultiCell(18.6, 0.5, $texto, 1, 'J');
		///////////////////////////////
		//$pdf->SetFont('arial','',7);
		//$texto = "O pagamento de títulos levados a protesto far-se-á através de Dinheiro ou Cheque administrativo. É favor comparecer munido desta notificação, no horário das 08:00 às 17:00 horas.";		
		//$pdf->SetXY(12.6, 3.8);
		//$pdf->MultiCell(7, 0.37, $texto, 1, 'J');
		////////////////////////////////////////////
		$pdf->SetXY(1, 5.4);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, "ESPÉCIE", 1, 0, 'C');
		$pdf->SetXY(1, 5.9);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(7, 0.5, $data_devedor->codigo, 1, 0, 'C');
		
		$pdf->SetXY(8, 5.4);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(3, 0.5, "VENCIMENTO", 1, 0, 'C');
		$pdf->SetXY(8, 5.9);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, $this->getDataTimestamp($data_devedor->vencimento), 1, 0, 'C');
		
		$pdf->SetXY(11, 5.4);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(5, 0.5, "NÚMERO DO TÍTULO", 1, 0, 'C');
		$pdf->SetXY(11, 5.9);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(5, 0.5, $data_devedor->numerotitulo, 1, 0, 'C');
		
		$pdf->SetXY(16, 5.4);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(3.6, 0.5, "VALOR DO TÍTULO", 1, 0, 'C');
		$pdf->SetXY(16, 5.9);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3.6, 0.5, $this->converte($data_devedor->valortitulo), 1, 0, 'C');
		/////////////////////////////////////////
		$pdf->SetY(6.5);
		$pdf->SetFont('arial', 'B', 7.5);
		$pdf->Cell(18.6, 2.5, "", 1, 0, 'L');
		$pdf->SetY(6.5);
		$pdf->Cell(18.6, 0.75, "CPF/CNPJ: " . $data_devedor->numeroidentificacao, 0, 0, 'L');
		$pdf->SetY(7.2);
		$pdf->Cell(18.6, 0.75, "DEVEDOR: " . $data_devedor->nome, 0, 0, 'L');
		$pdf->SetY(7.9);
		$pdf->Cell(18.6, 0.75, "ENDEREÇO: " . $endereco_devedor, 0, 1, 'L');
		$pdf->SetX(2.8);
		$pdf->Cell(18.6, 0.2, $endereco_devedor2, 0, 0, 'L');
		
		/*$pdf->SetXY(15, 6.5);
		$pdf->Cell(5, 0.5, "CUSTAS: R$ ", 0, 0, 'L');
		$pdf->SetXY(15, 6.8);
		$pdf->Cell(5, 0.5, "TX. JUDICIÁRIA: R$ ", 0, 0, 'L');
		$pdf->SetXY(15, 7.1);
		$pdf->Cell(5, 0.5, "INTIMAÇÃO: R$", 0, 0, 'L');
		$pdf->SetXY(15, 7.5);
		$pdf->Cell(5, 0.3, "FUNCIVIL: R$", 0, 0, 'L');
		$pdf->SetXY(15, 7.8);
		$pdf->Cell(5, 0.5, "TOTAL: R$", 0, 0, 'L');*/
		
		/******CALCULO DAS TAXAS DO PROTESTO******/
		//calcula o local onde o $x vai começar
		$teto = ceil((count($data_custas)+1) / 4);
		if((count($data_custas)+1)%4 == 0){
				$teto += 1; 
		}
		$x = 18.5;
		for($i=1; $i<$teto; $i++){
			$x -= 3; 
		}
		$y = 7;
		$cont = 1;		
		$total = 0;
		$pdf->SetFont('arial','',6.5);
		for($i=0; $i<count($data_custas); $i++){						
			$pdf->SetXY($x, $y);		
			$pdf->Cell(1, 0.5, strtoupper($data_custas[$i]->nome) . ": " . $this->converte($data_custas[$i]->valor), 0, 0, 'R');
			//se atigiu o limite da coluna, passa para a proxima coluna		
			if($cont == 4){				
				$cont = 0;
				$x += 3;
				$y = 6.5;				
			}			
			$y += 0.5;
			$cont++;
			$total += $data_custas[$i]->valor;
		}
		
		$total += $data_custas[0]->emolumento;
		$total += $data_devedor->valortitulo;
		
		$pdf->SetXY($x, $y);		
		$pdf->Cell(1, 0.5, "CUSTAS: " . $this->converte($data_custas[0]->emolumento), 0, 0, 'R');
		$pdf->SetXY(18.5, 8.5);
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(1, 0.5, "TOTAL: R$ " . $this->converte($total) , 0, 0, 'R');
		
		
		
		/////////////////////////////////////////
		$pdf->SetY(9.1);
		$pdf->SetFont('arial', 'B', 8);
		$pdf->Cell(18.6, 2.2, "", 1, 0, 'L');
				
		$pdf->SetY(9.1);
		$pdf->Cell(15, 0.5, "CEDENTE: " . trim($data_devedor->nomecedente), 0, 0, 'L');
		$pdf->SetXY(14.2, 9.1);
						
		$pdf->Cell(6, 0.5, "CPF/CNPJ: " . $doccedente, 0, 0, 'L');
				
		$pdf->SetY(9.7);
		$pdf->Cell(15, 0.5, "SACADOR: " . trim($data_devedor->nomesacador), 0, 0, 'L');
		$pdf->SetXY(14.2, 9.7);
		$pdf->Cell(6, 0.5, "CPF/CNPJ: " . $this->ajustaCPF_CNPJ($data_devedor->documentosacador, $tipoidsacador), 0, 0, 'L');
		
		$pdf->SetY(10.3);
		$pdf->Cell(15, 0.5, "APRESENTANTE: " . $nomepresentante, 0, 0, 'L');
		$pdf->SetXY(14.2, 10.3);
		$pdf->Cell(6, 0.5, "CPF/CNPJ: " . $docpresentante, 0, 0, 'L');
		
		$pdf->SetY(10.9);
		$pdf->Cell(15, 0.5, "DATA PROTOCOLO: " .  $this->getDataTimestamp($data_devedor->data_protocolo), 0, 0, 'L');
		$pdf->SetXY(13, 10.9);
		$pdf->Cell(6, 0.5, "Nº TÍTULO NO BANCO: ", 0, 0, 'L');
		
		/////////////////////////////////////////
		$pdf->SetY(11.4);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(18.6, 1.5, "", 1, 0, 'L');
		
		$pdf->SetY(11.85);
		$pdf->Cell(4, 0.5, "______/______/_______", 0, 0, 'C');
		$pdf->SetY(12.4);
		$pdf->Cell(4, 0.5, "DATA", 0, 0, 'C');
		
		$pdf->SetXY(5, 11.83);
		$pdf->Cell(7, 0.5, "_____________________________________________", 0, 0, 'C');
		$pdf->SetXY(5, 12.4);
		$pdf->Cell(7, 0.5, "NOME POR EXTENSO/CARIMBO DA EMPRESA", 0, 0, 'C');
		
		$pdf->SetXY(12, 11.8);
		$pdf->Cell(7, 0.5, "_________________________________________", 0, 0, 'C');
		$pdf->SetXY(12, 12.2);
		$pdf->Cell(7, 0.5, $data_cartorio->tabeliao, 0, 0, 'C');
		$pdf->SetXY(12, 12.5);
		$pdf->Cell(7, 0.5, "Tabelião", 0, 0, 'C');
		
		/////////////////////////////////////////
		$pdf->SetY(13);
		$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
		$pdf->SetXY(1.5, 13);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(3.5, 0.5, "Fechado o imóvel indicado.", 1, 0, 'L');
		
		$pdf->SetY(13.5);
		$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
		$pdf->SetXY(1.5, 13.5);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(3.5, 0.5, "Nº não localizado.", 1, 0, 'L');
		
		$pdf->SetXY(5, 13);
		$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
		$pdf->SetXY(5.5, 13);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(4.5, 0.5, "Mudou-se, segundo informado.", 1, 0, 'L');
				
		$pdf->SetXY(5, 13.5);
		$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
		$pdf->SetXY(5.5, 13.5);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(4.5, 0.5, "Desconhecido no endereço.", 1, 0, 'L');
		
		$pdf->SetXY(10, 13);
		$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
		$pdf->SetXY(10.5, 13);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(4.5, 0.5, "Endereço desconhecido.", 1, 0, 'L');
		
		$pdf->SetXY(10, 13.5);
		$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
		$pdf->SetXY(10.5, 13.5);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(4.5, 0.5, "Endereço insulficiente.", 1, 0, 'L');
		
		$pdf->SetXY(15, 13);
		$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
		$pdf->SetXY(15.5, 13);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(4.1, 0.5, "Recusado por:", 1, 0, 'L');
		
		$pdf->SetXY(15, 13.5);
		$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
		$pdf->SetXY(15.5, 13.5);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(4.1, 0.5, "", 1, 0, 'L');
		
		///////////////////
		
		$pdf->SetY(15.7);
		$pdf->Cell(0, 0.5, "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 1, 'C');
		
		/////////////2ª via
		$pdf->SetY(17);
		$pdf->Image('images/brasao.gif', 1.2, 17.2, 2.3);
		///////////////////////
		$pdf->SetFont('arial', 'B', 10);
		$pdf->Cell(15, 2.7, "", 1, 0, 'C');
		$pdf->SetY(17);
		$pdf->Cell(17, 1, "TABELIONATO DE PROTESTO DE TÍTULOS DE " . strtoupper($data_cartorio->cidade),0,1,'C');
		$pdf->SetFont('arial', 'B', 9);
		$pdf->SetY(17.8);
		$pdf->Cell(17, 1, $data_cartorio->tabeliao,0,1,'C');
		$pdf->SetY(18.3);
		$pdf->Cell(17, 1, "Tabelião",0,1,'C');		
		$pdf->SetY(19);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(17, 1, $data_cartorio->endereco . " " . $data_cartorio->complemento . " - " . $data_cartorio->bairro . " - Fone/Fax: " . $this->ajustaTelefone($data_cartorio->telefone) . " - CEP: " . $data_cartorio->cep . " - " . $data_cartorio->cidade . "-". $data_cartorio->uf,0,0,'C');
		//////////////////
		$pdf->SetXY(16.1, 17);
		$pdf->SetFont('arial', 'B', 10);
		$pdf->Cell(3.5, 0.9, "1ª Via", 1, 0, 'C');		
		$pdf->SetXY(16.1, 17.9);
		$pdf->SetFont('arial', '', 10);
		$pdf->Cell(3.5, 0.9, "PROTOCOLO", 1, 0, 'C');		
		$pdf->SetXY(16.1, 18.8);
		$pdf->SetFont('arial', 'B', 10);
		$pdf->Cell(3.5, 0.9, $data_devedor->protocolo, 1, 0, 'C');
		////////////////////////////
		$pdf->SetFont('arial','',10);
		$texto = "Levamos ao conhecimento de V.S. que se acha devidamente apontado neste cartório, o título mencionado. O pagamento deverá ser efetuado até o 3º dia útil da protocolização (Lei nº 9492 de 10/09/97), sendo protestado caso não haja o devido pagamento no prazo mencionado.";		
		$pdf->SetXY(1, 19.8);
		$pdf->MultiCell(18.6, 0.5, $texto, 1, 'J');
		///////////////////////////////
		//$pdf->SetFont('arial','',7);
		//$texto = "O pagamento de títulos levados a protesto far-se-á através de Dinheiro ou Cheque administrativo. É favor comparecer munido desta notificação, no horário das 08:00 às 17:00 horas.";		
		//$pdf->SetXY(12.6, 3.8);
		//$pdf->MultiCell(7, 0.37, $texto, 1, 'J');
		////////////////////////////////////////////
		$pdf->SetXY(1, 21.4);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, "ESPÉCIE", 1, 0, 'C');
		$pdf->SetXY(1, 21.9);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(7, 0.5, $data_devedor->codigo, 1, 0, 'C');
		
		$pdf->SetXY(8, 21.4);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(3, 0.5, "VENCIMENTO", 1, 0, 'C');
		$pdf->SetXY(8, 21.9);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, $this->getDataTimestamp($data_devedor->vencimento), 1, 0, 'C');
		
		$pdf->SetXY(11, 21.4);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(5, 0.5, "NÚMERO DO TÍTULO", 1, 0, 'C');
		$pdf->SetXY(11, 21.9);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(5, 0.5, $data_devedor->numerotitulo, 1, 0, 'C');
		
		$pdf->SetXY(16, 21.4);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(3.6, 0.5, "VALOR DO TÍTULO", 1, 0, 'C');
		$pdf->SetXY(16, 21.9);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3.6, 0.5, $this->converte($data_devedor->valortitulo), 1, 0, 'C');
		/////////////////////////////////////////
		$pdf->SetY(22.5);
		$pdf->SetFont('arial', 'B', 7.5);
		$pdf->Cell(18.6, 2.5, "", 1, 0, 'L');
		$pdf->SetY(22.5);
		$pdf->Cell(18.6, 0.75, "CPF/CNPJ: " . $data_devedor->numeroidentificacao, 0, 0, 'L');
		$pdf->SetY(23.2);
		$pdf->Cell(18.6, 0.75, "DEVEDOR: " . $data_devedor->nome, 0, 0, 'L');
		$pdf->SetY(23.9);
		$pdf->Cell(18.6, 0.75, "ENDEREÇO: " . $endereco_devedor, 0, 1, 'L');
		$pdf->SetX(2.8);
		$pdf->Cell(18.6, 0.2, $endereco_devedor2, 0, 0, 'L');
		
		/******CALCULO DAS TAXAS DO PROTESTO******/
		//calcula o local onde o $x vai começar
		$teto = ceil((count($data_custas)+1) / 5);
		if((count($data_custas)+1)%5 == 0){
				$teto += 1; 
		}
		$x = 18.5;
		for($i=1; $i<$teto; $i++){
			$x -= 3; 
		}
		$y = 22.5;
		$cont = 1;		
		$total = 0;
		$pdf->SetFont('arial','',6.5);
		for($i=0; $i<count($data_custas); $i++){						
			$pdf->SetXY($x, $y);		
			$pdf->Cell(1, 0.5, strtoupper($data_custas[$i]->nome) . ": " . $this->converte($data_custas[$i]->valor), 0, 0, 'R');
			//se atigiu o limite da coluna, passa para a proxima coluna		
			if($cont == 5){				
				$cont = 0;
				$x += 3;
				$y = 22;				
			}			
			$y += 0.5;
			$cont++;
			$total += $data_custas[$i]->valor;
		}
		
		$total += $data_custas[0]->emolumento;
		$total += $data_devedor->valortitulo;
		
		$pdf->SetXY($x, $y);		
		$pdf->Cell(1, 0.5, "CUSTAS: " . $this->converte($data_custas[0]->emolumento), 0, 0, 'R');
		$pdf->SetXY(18.5, 24);
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(1, 0.5, "TOTAL: R$ " . $this->converte($total) , 0, 0, 'R');
		
		
		
		/////////////////////////////////////////
		$pdf->SetY(25.1);
		$pdf->SetFont('arial', 'B', 8);
		$pdf->Cell(18.6, 2.2, "", 1, 0, 'L');
				
		$pdf->SetY(25.1);
		$pdf->Cell(15, 0.5, "CEDENTE: " . trim($data_devedor->nomecedente), 0, 0, 'L');
		$pdf->SetXY(14.2, 25.1);
						
		$pdf->Cell(6, 0.5, "CPF/CNPJ: " . $doccedente, 0, 0, 'L');
				
		$pdf->SetY(25.7);
		$pdf->Cell(15, 0.5, "SACADOR: " . trim($data_devedor->nomesacador), 0, 0, 'L');
		$pdf->SetXY(14.2, 25.7);
		$pdf->Cell(6, 0.5, "CPF/CNPJ: " . $this->ajustaCPF_CNPJ($data_devedor->documentosacador, $tipoidsacador), 0, 0, 'L');
		
		$pdf->SetY(26.3);
		$pdf->Cell(15, 0.5, "APRESENTANTE: " . $nomepresentante, 0, 0, 'L');
		$pdf->SetXY(14.2, 26.3);
		$pdf->Cell(6, 0.5, "CPF/CNPJ: " . $docpresentante, 0, 0, 'L');
		
		$pdf->SetY(26.9);
		$pdf->Cell(15, 0.5, "DATA PROTOCOLO: " .  $this->getDataTimestamp($data_devedor->data_protocolo), 0, 0, 'L');
		$pdf->SetXY(13, 26.9);
		$pdf->Cell(6, 0.5, "Nº TÍTULO NO BANCO: ", 0, 0, 'L');
		
		
		//print_r($data_cartorio);exit;
		
		$pdf->Output("Notificação_" . $data_devedor->idProtesto . ".pdf","I");exit;
	}
	
	
	public function gerarNotificacoes($data_devedores, $data_cartorio){
		
		$pdf= new FPDF("P","cm","A4");
		
		$model_custas = new Custa();
			
		foreach($data_devedores as $data_devedor){
		
			$data_custas = $model_custas->getCustas($data_devedor->idProtesto, $data_devedor->valortitulo);
		
			$endereco_devedor = trim($data_devedor->endereco) . ", " . trim($data_devedor->bairro);
			$endereco_devedor2 = trim($data_devedor->cidade) . "-" . trim($data_devedor->estado) . " - " . $this->ajustaCEP(trim($data_devedor->cep));
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
			
				
			$pdf->AddPage();	
			$pdf->Image('images/brasao.gif', 1.2, 1.2, 2.3);
			///////////////////////
			$pdf->SetFont('arial', 'B', 10);
			$pdf->Cell(15, 2.7, "", 1, 0, 'C');
			$pdf->SetY(1);
			$pdf->Cell(17, 1, "TABELIONATO DE PROTESTO DE TÍTULOS DE " . strtoupper($data_cartorio->cidade),0,1,'C');
			$pdf->SetFont('arial', 'B', 9);
			$pdf->SetY(1.8);
			$pdf->Cell(17, 1, $data_cartorio->tabeliao,0,1,'C');
			$pdf->SetY(2.3);
			$pdf->Cell(17, 1, "Tabelião",0,1,'C');		
			$pdf->SetY(3);
			$pdf->SetFont('arial', '', 7);
			$pdf->Cell(17, 1, $data_cartorio->endereco . " " . $data_cartorio->complemento . " - " . $data_cartorio->bairro . " - Fone/Fax: " . $this->ajustaTelefone($data_cartorio->telefone) . " - CEP: " . $data_cartorio->cep . " - " . $data_cartorio->cidade . "-". $data_cartorio->uf,0,0,'C');
			//////////////////
			$pdf->SetXY(16.1, 1);
			$pdf->SetFont('arial', 'B', 10);
			$pdf->Cell(3.5, 0.9, "1ª Via", 1, 0, 'C');		
			$pdf->SetXY(16.1, 1.9);
			$pdf->SetFont('arial', '', 10);
			$pdf->Cell(3.5, 0.9, "PROTOCOLO", 1, 0, 'C');		
			$pdf->SetXY(16.1, 2.8);
			$pdf->SetFont('arial', 'B', 10);
			$pdf->Cell(3.5, 0.9, $data_devedor->protocolo, 1, 0, 'C');
			////////////////////////////
			$pdf->SetFont('arial','',10);
			$texto = "Levamos ao conhecimento de V.S. que se acha devidamente apontado neste cartório, o título mencionado. O pagamento deverá ser efetuado até o 3º dia útil da protocolização (Lei nº 9492 de 10/09/97), sendo protestado caso não haja o devido pagamento no prazo mencionado.";		
			$pdf->SetXY(1, 3.8);
			$pdf->MultiCell(18.6, 0.5, $texto, 1, 'J');
			///////////////////////////////
			//$pdf->SetFont('arial','',7);
			//$texto = "O pagamento de títulos levados a protesto far-se-á através de Dinheiro ou Cheque administrativo. É favor comparecer munido desta notificação, no horário das 08:00 às 17:00 horas.";		
			//$pdf->SetXY(12.6, 3.8);
			//$pdf->MultiCell(7, 0.37, $texto, 1, 'J');
			////////////////////////////////////////////
			$pdf->SetXY(1, 5.4);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, "ESPÉCIE", 1, 0, 'C');
			$pdf->SetXY(1, 5.9);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(7, 0.5, $data_devedor->codigo, 1, 0, 'C');
			
			$pdf->SetXY(8, 5.4);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(3, 0.5, "VENCIMENTO", 1, 0, 'C');
			$pdf->SetXY(8, 5.9);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, $this->getDataTimestamp($data_devedor->vencimento), 1, 0, 'C');
			
			$pdf->SetXY(11, 5.4);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(5, 0.5, "NÚMERO DO TÍTULO", 1, 0, 'C');
			$pdf->SetXY(11, 5.9);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(5, 0.5, $data_devedor->numerotitulo, 1, 0, 'C');
			
			$pdf->SetXY(16, 5.4);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(3.6, 0.5, "VALOR DO TÍTULO", 1, 0, 'C');
			$pdf->SetXY(16, 5.9);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3.6, 0.5, $this->converte($data_devedor->valortitulo), 1, 0, 'C');
			/////////////////////////////////////////
			$pdf->SetY(6.5);
			$pdf->SetFont('arial', 'B', 7.5);
			$pdf->Cell(18.6, 2.5, "", 1, 0, 'L');
			$pdf->SetY(6.5);
			$pdf->Cell(18.6, 0.75, "CPF/CNPJ: " . $data_devedor->numeroidentificacao, 0, 0, 'L');
			$pdf->SetY(7.2);
			$pdf->Cell(18.6, 0.75, "DEVEDOR: " . $data_devedor->nome, 0, 0, 'L');
			$pdf->SetY(7.9);
			$pdf->Cell(18.6, 0.75, "ENDEREÇO: " . $endereco_devedor, 0, 1, 'L');
			$pdf->SetX(2.8);
			$pdf->Cell(18.6, 0.2, $endereco_devedor2, 0, 0, 'L');
			
			/*$pdf->SetXY(15, 6.5);
			$pdf->Cell(5, 0.5, "CUSTAS: R$ ", 0, 0, 'L');
			$pdf->SetXY(15, 6.8);
			$pdf->Cell(5, 0.5, "TX. JUDICIÁRIA: R$ ", 0, 0, 'L');
			$pdf->SetXY(15, 7.1);
			$pdf->Cell(5, 0.5, "INTIMAÇÃO: R$", 0, 0, 'L');
			$pdf->SetXY(15, 7.5);
			$pdf->Cell(5, 0.3, "FUNCIVIL: R$", 0, 0, 'L');
			$pdf->SetXY(15, 7.8);
			$pdf->Cell(5, 0.5, "TOTAL: R$", 0, 0, 'L');*/
			
			/******CALCULO DAS TAXAS DO PROTESTO******/
			//calcula o local onde o $x vai começar
			$teto = ceil((count($data_custas)+1) / 4);
			if((count($data_custas)+1)%4 == 0){
					$teto += 1; 
			}
			$x = 18.5;
			for($i=1; $i<$teto; $i++){
				$x -= 3; 
			}
			$y = 7;
			$cont = 1;		
			$total = 0;
			$pdf->SetFont('arial','',6.5);
			for($i=0; $i<count($data_custas); $i++){						
				$pdf->SetXY($x, $y);		
				$pdf->Cell(1, 0.5, strtoupper($data_custas[$i]->nome) . ": " . $this->converte($data_custas[$i]->valor), 0, 0, 'R');
				//se atigiu o limite da coluna, passa para a proxima coluna		
				if($cont == 4){				
					$cont = 0;
					$x += 3;
					$y = 6.5;				
				}			
				$y += 0.5;
				$cont++;
				$total += $data_custas[$i]->valor;
			}
			
			$total += $data_custas[0]->emolumento;
			$total += $data_devedor->valortitulo;
			
			$pdf->SetXY($x, $y);		
			$pdf->Cell(1, 0.5, "CUSTAS: " . $this->converte($data_custas[0]->emolumento), 0, 0, 'R');
			$pdf->SetXY(18.5, 8.5);
			$pdf->SetFont('arial','B',8);
			$pdf->Cell(1, 0.5, "TOTAL: R$ " . $this->converte($total) , 0, 0, 'R');
			
			
			
			/////////////////////////////////////////
			$pdf->SetY(9.1);
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(18.6, 2.2, "", 1, 0, 'L');
					
			$pdf->SetY(9.1);
			$pdf->Cell(15, 0.5, "CEDENTE: " . trim($data_devedor->nomecedente), 0, 0, 'L');
			$pdf->SetXY(14.2, 9.1);
							
			$pdf->Cell(6, 0.5, "CPF/CNPJ: " . $doccedente, 0, 0, 'L');
					
			$pdf->SetY(9.7);
			$pdf->Cell(15, 0.5, "SACADOR: " . trim($data_devedor->nomesacador), 0, 0, 'L');
			$pdf->SetXY(14.2, 9.7);
			$pdf->Cell(6, 0.5, "CPF/CNPJ: " . $this->ajustaCPF_CNPJ($data_devedor->documentosacador, $tipoidsacador), 0, 0, 'L');
			
			$pdf->SetY(10.3);
			$pdf->Cell(15, 0.5, "APRESENTANTE: " . $nomepresentante, 0, 0, 'L');
			$pdf->SetXY(14.2, 10.3);
			$pdf->Cell(6, 0.5, "CPF/CNPJ: " . $docpresentante, 0, 0, 'L');
			
			$pdf->SetY(10.9);
			$pdf->Cell(15, 0.5, "DATA PROTOCOLO: " .  $this->getDataTimestamp($data_devedor->data_protocolo), 0, 0, 'L');
			$pdf->SetXY(13, 10.9);
			$pdf->Cell(6, 0.5, "Nº TÍTULO NO BANCO: ", 0, 0, 'L');
			
			/////////////////////////////////////////
			$pdf->SetY(11.4);
			$pdf->SetFont('arial', '', 7);
			$pdf->Cell(18.6, 1.5, "", 1, 0, 'L');
			
			$pdf->SetY(11.85);
			$pdf->Cell(4, 0.5, "______/______/_______", 0, 0, 'C');
			$pdf->SetY(12.4);
			$pdf->Cell(4, 0.5, "DATA", 0, 0, 'C');
			
			$pdf->SetXY(5, 11.83);
			$pdf->Cell(7, 0.5, "_____________________________________________", 0, 0, 'C');
			$pdf->SetXY(5, 12.4);
			$pdf->Cell(7, 0.5, "NOME POR EXTENSO/CARIMBO DA EMPRESA", 0, 0, 'C');
			
			$pdf->SetXY(12, 11.8);
			$pdf->Cell(7, 0.5, "_________________________________________", 0, 0, 'C');
			$pdf->SetXY(12, 12.2);
			$pdf->Cell(7, 0.5, $data_cartorio->tabeliao, 0, 0, 'C');
			$pdf->SetXY(12, 12.5);
			$pdf->Cell(7, 0.5, "Tabelião", 0, 0, 'C');
			
			/////////////////////////////////////////
			$pdf->SetY(13);
			$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
			$pdf->SetXY(1.5, 13);
			$pdf->SetFont('arial', '', 7);
			$pdf->Cell(3.5, 0.5, "Fechado o imóvel indicado.", 1, 0, 'L');
			
			$pdf->SetY(13.5);
			$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
			$pdf->SetXY(1.5, 13.5);
			$pdf->SetFont('arial', '', 7);
			$pdf->Cell(3.5, 0.5, "Nº não localizado.", 1, 0, 'L');
			
			$pdf->SetXY(5, 13);
			$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
			$pdf->SetXY(5.5, 13);
			$pdf->SetFont('arial', '', 7);
			$pdf->Cell(4.5, 0.5, "Mudou-se, segundo informado.", 1, 0, 'L');
					
			$pdf->SetXY(5, 13.5);
			$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
			$pdf->SetXY(5.5, 13.5);
			$pdf->SetFont('arial', '', 7);
			$pdf->Cell(4.5, 0.5, "Desconhecido no endereço.", 1, 0, 'L');
			
			$pdf->SetXY(10, 13);
			$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
			$pdf->SetXY(10.5, 13);
			$pdf->SetFont('arial', '', 7);
			$pdf->Cell(4.5, 0.5, "Endereço desconhecido.", 1, 0, 'L');
			
			$pdf->SetXY(10, 13.5);
			$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
			$pdf->SetXY(10.5, 13.5);
			$pdf->SetFont('arial', '', 7);
			$pdf->Cell(4.5, 0.5, "Endereço insulficiente.", 1, 0, 'L');
			
			$pdf->SetXY(15, 13);
			$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
			$pdf->SetXY(15.5, 13);
			$pdf->SetFont('arial', '', 7);
			$pdf->Cell(4.1, 0.5, "Recusado por:", 1, 0, 'L');
			
			$pdf->SetXY(15, 13.5);
			$pdf->Cell(0.5, 0.5, "", 1, 0, 'L');
			$pdf->SetXY(15.5, 13.5);
			$pdf->SetFont('arial', '', 7);
			$pdf->Cell(4.1, 0.5, "", 1, 0, 'L');
			
			///////////////////
			
			$pdf->SetY(15.7);
			$pdf->Cell(0, 0.5, "-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 1, 'C');
			
			/////////////2ª via
			$pdf->SetY(17);
			$pdf->Image('images/brasao.gif', 1.2, 17.2, 2.3);
			///////////////////////
			$pdf->SetFont('arial', 'B', 10);
			$pdf->Cell(15, 2.7, "", 1, 0, 'C');
			$pdf->SetY(17);
			$pdf->Cell(17, 1, "TABELIONATO DE PROTESTO DE TÍTULOS DE " . strtoupper($data_cartorio->cidade),0,1,'C');
			$pdf->SetFont('arial', 'B', 9);
			$pdf->SetY(17.8);
			$pdf->Cell(17, 1, $data_cartorio->tabeliao,0,1,'C');
			$pdf->SetY(18.3);
			$pdf->Cell(17, 1, "Tabelião",0,1,'C');		
			$pdf->SetY(19);
			$pdf->SetFont('arial', '', 7);
			$pdf->Cell(17, 1, $data_cartorio->endereco . " " . $data_cartorio->complemento . " - " . $data_cartorio->bairro . " - Fone/Fax: " . $this->ajustaTelefone($data_cartorio->telefone) . " - CEP: " . $data_cartorio->cep . " - " . $data_cartorio->cidade . "-". $data_cartorio->uf,0,0,'C');
			//////////////////
			$pdf->SetXY(16.1, 17);
			$pdf->SetFont('arial', 'B', 10);
			$pdf->Cell(3.5, 0.9, "1ª Via", 1, 0, 'C');		
			$pdf->SetXY(16.1, 17.9);
			$pdf->SetFont('arial', '', 10);
			$pdf->Cell(3.5, 0.9, "PROTOCOLO", 1, 0, 'C');		
			$pdf->SetXY(16.1, 18.8);
			$pdf->SetFont('arial', 'B', 10);
			$pdf->Cell(3.5, 0.9, $data_devedor->protocolo, 1, 0, 'C');
			////////////////////////////
			$pdf->SetFont('arial','',10);
			$texto = "Levamos ao conhecimento de V.S. que se acha devidamente apontado neste cartório, o título mencionado. O pagamento deverá ser efetuado até o 3º dia útil da protocolização (Lei nº 9492 de 10/09/97), sendo protestado caso não haja o devido pagamento no prazo mencionado.";		
			$pdf->SetXY(1, 19.8);
			$pdf->MultiCell(18.6, 0.5, $texto, 1, 'J');
			///////////////////////////////
			//$pdf->SetFont('arial','',7);
			//$texto = "O pagamento de títulos levados a protesto far-se-á através de Dinheiro ou Cheque administrativo. É favor comparecer munido desta notificação, no horário das 08:00 às 17:00 horas.";		
			//$pdf->SetXY(12.6, 3.8);
			//$pdf->MultiCell(7, 0.37, $texto, 1, 'J');
			////////////////////////////////////////////
			$pdf->SetXY(1, 21.4);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, "ESPÉCIE", 1, 0, 'C');
			$pdf->SetXY(1, 21.9);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(7, 0.5, $data_devedor->codigo, 1, 0, 'C');
			
			$pdf->SetXY(8, 21.4);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(3, 0.5, "VENCIMENTO", 1, 0, 'C');
			$pdf->SetXY(8, 21.9);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, $this->getDataTimestamp($data_devedor->vencimento), 1, 0, 'C');
			
			$pdf->SetXY(11, 21.4);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(5, 0.5, "NÚMERO DO TÍTULO", 1, 0, 'C');
			$pdf->SetXY(11, 21.9);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(5, 0.5, $data_devedor->numerotitulo, 1, 0, 'C');
			
			$pdf->SetXY(16, 21.4);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(3.6, 0.5, "VALOR DO TÍTULO", 1, 0, 'C');
			$pdf->SetXY(16, 21.9);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3.6, 0.5, $this->converte($data_devedor->valortitulo), 1, 0, 'C');
			/////////////////////////////////////////
			$pdf->SetY(22.5);
			$pdf->SetFont('arial', 'B', 7.5);
			$pdf->Cell(18.6, 2.5, "", 1, 0, 'L');
			$pdf->SetY(22.5);
			$pdf->Cell(18.6, 0.75, "CPF/CNPJ: " . $data_devedor->numeroidentificacao, 0, 0, 'L');
			$pdf->SetY(23.2);
			$pdf->Cell(18.6, 0.75, "DEVEDOR: " . $data_devedor->nome, 0, 0, 'L');
			$pdf->SetY(23.9);
			$pdf->Cell(18.6, 0.75, "ENDEREÇO: " . $endereco_devedor, 0, 1, 'L');
			$pdf->SetX(2.8);
			$pdf->Cell(18.6, 0.2, $endereco_devedor2, 0, 0, 'L');
			
			/******CALCULO DAS TAXAS DO PROTESTO******/
			//calcula o local onde o $x vai começar
			$teto = ceil((count($data_custas)+1) / 5);
			if((count($data_custas)+1)%5 == 0){
					$teto += 1; 
			}
			$x = 18.5;
			for($i=1; $i<$teto; $i++){
				$x -= 3; 
			}
			$y = 22.5;
			$cont = 1;		
			$total = 0;
			$pdf->SetFont('arial','',6.5);
			for($i=0; $i<count($data_custas); $i++){						
				$pdf->SetXY($x, $y);		
				$pdf->Cell(1, 0.5, strtoupper($data_custas[$i]->nome) . ": " . $this->converte($data_custas[$i]->valor), 0, 0, 'R');
				//se atigiu o limite da coluna, passa para a proxima coluna		
				if($cont == 5){				
					$cont = 0;
					$x += 3;
					$y = 22;				
				}			
				$y += 0.5;
				$cont++;
				$total += $data_custas[$i]->valor;
			}
			
			$total += $data_custas[0]->emolumento;
			$total += $data_devedor->valortitulo;
			
			$pdf->SetXY($x, $y);		
			$pdf->Cell(1, 0.5, "CUSTAS: " . $this->converte($data_custas[0]->emolumento), 0, 0, 'R');
			$pdf->SetXY(18.5, 24);
			$pdf->SetFont('arial','B',8);
			$pdf->Cell(1, 0.5, "TOTAL: R$ " . $this->converte($total) , 0, 0, 'R');
			
			
			
			/////////////////////////////////////////
			$pdf->SetY(25.1);
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(18.6, 2.2, "", 1, 0, 'L');
					
			$pdf->SetY(25.1);
			$pdf->Cell(15, 0.5, "CEDENTE: " . trim($data_devedor->nomecedente), 0, 0, 'L');
			$pdf->SetXY(14.2, 25.1);
							
			$pdf->Cell(6, 0.5, "CPF/CNPJ: " . $doccedente, 0, 0, 'L');
					
			$pdf->SetY(25.7);
			$pdf->Cell(15, 0.5, "SACADOR: " . trim($data_devedor->nomesacador), 0, 0, 'L');
			$pdf->SetXY(14.2, 25.7);
			$pdf->Cell(6, 0.5, "CPF/CNPJ: " . $this->ajustaCPF_CNPJ($data_devedor->documentosacador, $tipoidsacador), 0, 0, 'L');
			
			$pdf->SetY(26.3);
			$pdf->Cell(15, 0.5, "APRESENTANTE: " . $nomepresentante, 0, 0, 'L');
			$pdf->SetXY(14.2, 26.3);
			$pdf->Cell(6, 0.5, "CPF/CNPJ: " . $docpresentante, 0, 0, 'L');
			
			$pdf->SetY(26.9);
			$pdf->Cell(15, 0.5, "DATA PROTOCOLO: " .  $this->getDataTimestamp($data_devedor->data_protocolo), 0, 0, 'L');
			$pdf->SetXY(13, 26.9);
			$pdf->Cell(6, 0.5, "Nº TÍTULO NO BANCO: ", 0, 0, 'L');
		}

		$data['arquivo'] = "N" . date('dmY_his') . ".pdf";
    	
        $user = new Zend_Session_Namespace('user_data');
        $data['idUsuario'] = $user->user->idUsuario;
        $data['tipo'] = 10; //tipo notificacao
            
        $arquivo = new Arquivo();
        $arquivo->insert($data);
        $lastId = $arquivo->getAdapter()->lastInsertId();
		
		
		$path = APPLICATION_PATH . '/arquivos/notificacoes';		
		if(!file_exists($path))mkdir($path);
		$path .= "/" . $lastId;
		
		$pdf->Output($path. ".pdf", "F"); 
		$pdf->Output("N" .date('dmY_his') . ".pdf", "D"); exit;
		
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
	
	function converteData($valor){

		$dados = str_split($valor);
		$aux = '';
				
		for($i = 0; $i < count($dados); $i++){
			
			if($i==2 || $i==4){
				$aux = $aux . "/" . $dados[$i];
			}else
			$aux = $aux . $dados[$i];
		}
		
		return $aux;
     }

}