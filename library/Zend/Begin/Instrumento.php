<?php 
require('fpdf/fpdf.php');

class Begin_Instrumento extends FPDF{
	
	public function gerarInstrumento($data_devedor, $data_cartorio, $data_custas, $data_autoridade){

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
		$pdf->Image('images/brasao.gif', 1.2, 1.2, 2.3);
		///////////////////////
		$pdf->SetFont('arial', 'B', 14);
		$pdf->Cell(18.6, 2.7, "", 1, 0, 'C');
		$pdf->SetY(1);
		$pdf->Cell(20, 1, "TABELIONATO DE PROTESTO DE TÍTULOS DE " . strtoupper($data_cartorio->cidade),0,1,'C');
		$pdf->SetFont('arial', 'B', 10);
		$pdf->SetY(1.8);
		$pdf->Cell(20, 1, $data_cartorio->tabeliao,0,1,'C');
		$pdf->SetY(2.3);
		$pdf->Cell(20, 1, "Tabelião",0,1,'C');		
		$pdf->SetY(3);
		$pdf->SetFont('arial', '', 8);
		$pdf->Cell(20, 1, $data_cartorio->endereco . " " . $data_cartorio->complemento . " - " . $data_cartorio->bairro . " - Fone/Fax: " . $this->ajustaTelefone($data_cartorio->telefone) . " - CEP: " . $data_cartorio->cep . " - " . $data_cartorio->cidade . "-". $data_cartorio->uf,0,0,'C');
		
		////////////////////////////
		$pdf->SetY(3.8);
		$pdf->Cell(18.6, 4.2, "", 1, 0, 'C');
		$pdf->SetXY(1, 3.8);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(7, 0.5, "INSTRUMENTO DE PROTESTO DE", 1, 0, 'C');
		$pdf->SetXY(1, 4.3);
		$pdf->SetFont('arial', 'B', 7);
		$pdf->Cell(7, 0.5, strtoupper($data_devedor->especie), 1, 0, 'C');
		
		$pdf->SetXY(8, 3.8);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "PROTESTO Nº", 1, 0, 'C');
		$pdf->SetXY(8, 4.3);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(3, 0.5, $data_devedor->idProtesto, 1, 0, 'C');
		
		$pdf->SetXY(11, 3.8);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(5, 0.5, "LIVRO/Nº SEQ.", 1, 0, 'C');
		$pdf->SetXY(11, 4.3);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(5, 0.5, $data_devedor->livro, 1, 0, 'C');
		
		$pdf->SetXY(16, 3.8);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3.6, 0.5, "FOLHA", 1, 0, 'C');
		$pdf->SetXY(16, 4.3);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(3.6, 0.5, $data_devedor->folha, 1, 0, 'C');
		
		/////////////////////////////////////////////////////
		$pdf->SetXY(1.2, 5);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "DADOS A FAVOR DE:", 0, 0, 'L');
		$pdf->SetXY(6, 5);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, $data_devedor->nomesacador, 0, 0, 'L');
		
		$pdf->SetXY(1.2, 5.5);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "CPF/CNPJ:", 0, 0, 'L');
		$pdf->SetXY(6, 5.5);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, $this->ajustaCPF_CNPJ($data_devedor->documentosacador, $tipoidsacador), 0, 0, 'L');
		
		$pdf->SetXY(1.2, 6);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "APRESENTANTE:", 0, 0, 'L');
		$pdf->SetXY(6, 6);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, $data_devedor->nomeapresentante, 0, 0, 'L');
		
		$pdf->SetXY(1.2, 6.5);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "ENDEREÇO:", 0, 0, 'L');
		$pdf->SetXY(6, 6.5);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, "", 0, 0, 'L');
		
		$pdf->SetXY(1.2, 7);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "DATA DE APRESENTAÇÃO:", 0, 0, 'L');
		$pdf->SetXY(6, 7);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, $this->getDataTimestamp($data_devedor->data_protocolo), 0, 0, 'L');
		
		$pdf->SetXY(1.2, 7.5);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "PROTOCOLO Nº:", 0, 0, 'L');
		$pdf->SetXY(6, 7.5);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, $data_devedor->protocolo, 0, 0, 'L');
		
		$pdf->SetXY(10.5, 7);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "PROTESTO POR FALTA DE:", 0, 0, 'L');
		$pdf->SetXY(15, 7);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, "Pagamento", 0, 0, 'L');
		
		////////////////////////////////////////
		$pdf->SetY(8.1);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(18.6, 0.5, "TÍTULO ANEXO AO PRESENTE (CÓPIA ARQUIVADA NESTE TABELIONATO)", 0, 0, 'L');
		
		/////////////////////////////////////////
		$pdf->SetY(8.6);
		$pdf->Cell(18.6, 7.5, "", 1, 0, 'L');
		
		$pdf->SetXY(1.2, 8.8);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "ESPÉCIE:", 0, 0, 'L');
		$pdf->SetXY(6, 8.8);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, strtoupper($data_devedor->especie), 0, 0, 'L');
		
		$pdf->SetXY(1.2, 9.3);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "TÍTULO Nº:", 0, 0, 'L');
		$pdf->SetXY(6, 9.3);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, $data_devedor->numerotitulo, 0, 0, 'L');
		
		$pdf->SetXY(1.2, 9.8);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "VENCIMENTO:", 0, 0, 'L');
		$pdf->SetXY(6, 9.8);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, $this->getDataTimestamp($data_devedor->vencimento), 0, 0, 'L');
		
		$pdf->SetXY(1.2, 10.3);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "Nº DO TÍTULO NO BANCO:", 0, 0, 'L');
		$pdf->SetXY(6, 10.3);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, $data_devedor->nossonumero, 0, 0, 'L');
		
		$pdf->SetXY(1.2, 10.8);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "VALOR DO TÍTULO:", 0, 0, 'L');
		$pdf->SetXY(6, 10.8);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, "R$ " . $this->converte($data_devedor->valortitulo), 0, 0, 'L');
		
		$pdf->SetXY(1.2, 11.3);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "DATA DE EMISSÃO:", 0, 0, 'L');
		$pdf->SetXY(6, 11.3);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, $this->getDataTimestamp($data_devedor->dataemissao), 0, 0, 'L');
		
		$pdf->SetXY(1.2, 11.8);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "ENDOSSO:", 0, 0, 'L');
		$pdf->SetXY(6, 11.8);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, $tipoendosso, 0, 0, 'L');
		
		$pdf->SetXY(1.2, 12.3);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "AG/CÓDIGO DO CEDENTE: ", 0, 0, 'L');
		$pdf->SetXY(6, 12.3);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, $data_devedor->codigocedente_agencia, 0, 0, 'L');
		
		$pdf->SetXY(1.2, 12.8);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "VALOR PROTESTADO:", 0, 0, 'L');
		$pdf->SetXY(6, 12.8);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, "R$ " . $this->converte($data_devedor->valortitulo), 0, 0, 'L');
		
		$pdf->SetXY(1.2, 13.3);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "VALOR POR EXTENSO:", 0, 0, 'L');
		$pdf->SetXY(6, 13.3);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, $this->extenso($data_devedor->valortitulo, true), 0, 0, 'L');
		
		$pdf->SetXY(1.2, 13.8);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "OBSERVAÇÃO:", 0, 0, 'L');
		$pdf->SetXY(6, 13.8);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, "", 0, 0, 'L');
		
		/////////////////////////////////////////
		$pdf->SetY(16.2);
		$pdf->Cell(18.6, 5, "", 1, 0, 'L');
		
		$pdf->SetXY(1.2, 16.4);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "DEVEDOR:", 0, 0, 'L');
		$pdf->SetXY(4, 16.4);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, $data_devedor->nome, 0, 0, 'L');
		
		$pdf->SetXY(1.2, 16.9);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "CPF/CNPJ:", 0, 0, 'L');
		$pdf->SetXY(4, 16.9);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, $this->ajustaCPF_CNPJ($data_devedor->numeroidentificacao, $data_devedor->tipoidentificacao), 0, 0, 'L');
		
		$pdf->SetXY(1.2, 17.4);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "ENDEREÇO:", 0, 0, 'L');
		$pdf->SetXY(4, 17.4);
		$pdf->SetFont('arial', 'B', 8.5);
		$pdf->Cell(7, 0.5, $endereco_devedor, 0, 0, 'L');
		
		$pdf->SetXY(1.2, 18.4);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "AVALISTA:", 0, 0, 'L');
		$pdf->SetXY(4, 18.4);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, "", 0, 0, 'L');
		
		$pdf->SetXY(1.2, 19.4);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "OUTROS DEV.:", 0, 0, 'L');
		$pdf->SetXY(4, 19.4);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(7, 0.5, "", 0, 0, 'L');
		
		////////////////////////////
		$pdf->SetY(21.3);
		$pdf->Cell(18.6, 4, "", 1, 0, 'L');
		
		$pdf->SetFont('arial','',9);
		$texto = "CERTIFICO QUE O DEVEDOR FOI NOTIFICADO A VIR PAGAR O REFERIDO TÍTULO, CONFORME RECIBO E NÃO COMPARECEU. CERTIFICO, AINDA, QUE A CÓPPIA DO TÍTULO DIFITALIZADA NO VERSO DESTE INSTRUMENTO CONFERE COM O ORIGINAL APRESENTADO NESTA SERVENTIA, PARA OS FINS DO DISPOSTO NO ART. 39 DA LEI Nº 9.492/97.";		
		$pdf->SetXY(1.2, 21.5);
		$pdf->MultiCell(18, 0.5, $texto, 0, 'J');
		
		$pdf->SetFont('arial','',9);
		$texto = "Eu, " . $data_autoridade->nome. ", " . $data_autoridade->cargo .", o digitei, subscrevi, conferi, dou fé e assino em público e raso. Palmas-TO, 13/03/2012. (Lavrado à hora legal) em test. ________________________ da verdade.";		
		$pdf->SetXY(1.2, 24);
		$pdf->MultiCell(18, 0.5, $texto, 0, 'J');
		
		//////////////////////////////////////////////////////		
		$pdf->SetY(25.4);
		$pdf->Cell(18.6, 2.2, "", 1, 0, 'L');
		
		$pdf->SetXY(1.2, 26.8);
		$pdf->SetFont('arial','',11);
		$pdf->Cell(8, 0.5, $data_cartorio->cidade . "-". $data_cartorio->uf . ", " . date('d/m/Y'), 0, 0, 'L');
		
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
		$y = 25.5;
		$cont = 1;		
		$total = 0;
		$pdf->SetFont('arial','',7);
		for($i=0; $i<count($data_custas); $i++){						
			$pdf->SetXY($x, $y);		
			$pdf->Cell(1, 0.5, strtoupper($data_custas[$i]->nome) . ": " . $this->converte($data_custas[$i]->valor), 0, 0, 'R');
			//se atigiu o limite da coluna, passa para a proxima coluna		
			if($cont == 4){				
				$cont = 0;
				$x += 3;
				$y = 25;				
			}			
			$y += 0.5;
			$cont++;
			$total += $data_custas[$i]->valor;
		}
		
		$total += $data_custas[0]->emolumento;
		
		$pdf->SetXY($x, $y);		
		$pdf->Cell(1, 0.5, "CUSTAS: " . $this->converte($data_custas[0]->emolumento), 0, 0, 'R');
		$pdf->SetXY(18.5, 27);
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(1, 0.5, "TOTAL: R$ " . $this->converte($total) , 0, 0, 'R');
		
		//////////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////////// PRÓXIMA PÁGINA ///////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////////////
		
		$pdf->AddPage();	
		
		$pdf->SetFont('arial', 'B', 18);
		$pdf->Cell(15, 2.7, "", 1, 0, 'C');
		$pdf->SetY(1.5);
		$pdf->Cell(16, 1, "ORDEM DE PROTESTO",0,1,'C');
		$pdf->SetFont('arial', '', 7);		
		$texto = "Nos termos do parágrafo único do art. 8º da Lei 9.492/97, trata-se o presente da INSTRUMENTALIZAÇÃO da indicação da duplicata abaixo caracterizada, recepcionada por meio magnético, sendo os dados de inteira responsabilidade do apresentante."; 		
		$pdf->SetXY(1, 2.5);
		$pdf->MultiCell(15, 0.5, $texto, 0, 'C');
		
		$pdf->SetXY(16.1, 1);
		$pdf->SetFont('arial', '', 10);
		$pdf->Cell(3.5, 0.9, "PROTOCOLO", 1, 0, 'C');		
		$pdf->SetXY(16.1, 1.9);
		$pdf->SetFont('arial', 'B', 8);
		$pdf->Cell(3.5, 0.9, "Nº: " . $data_devedor->protocolo, 1, 0, 'C');		
		$pdf->SetXY(16.1, 2.8);
		$pdf->SetFont('arial', 'B', 8);
		$pdf->Cell(3.5, 0.9, "Em: " . $this->getDataTimestamp($data_devedor->data_protocolo), 1, 0, 'C');

		////////////////////////////////////////////////////////
		$pdf->SetXY(1, 4);
		$pdf->SetFont('arial', 'B', 10);
		$pdf->Cell(5, 0.5, "codigo - ".$data_devedor->nomeapresentante, 0, 0, 'L');
		
		/////////////////////////////////////////////////////////
		$pdf->SetY(4.5);
		$pdf->Cell(18.6, 1.5, "", 1, 0, 'L');
		
		$pdf->SetXY(1, 4.46);
		$pdf->SetFont('arial', '', 7.6);
		$pdf->Cell(3, 0.5, "ESPÉCIE: " . strtoupper($data_devedor->especie), 0, 0, 'L');		
		
		$pdf->SetXY(1, 4.8);
		$pdf->Cell(2.3, 0.5, "VENCIMENTO: " . $this->getDataTimestamp($data_devedor->vencimento), 0, 0, 'L');		
				
		$pdf->SetXY(1, 5.2);
		$pdf->Cell(1.8, 0.5, "VALOR: " . $this->converte($data_devedor->valortitulo), 0, 0, 'L');		
				
		$pdf->SetXY(1, 5.6);
		$pdf->Cell(2, 0.5, "SALDO: ", 0, 0, 'L');		
		
		
		
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
		$y = 4.4;
		$cont = 1;		
		$total = 0;
		$pdf->SetFont('arial', '', 6);
		for($i=0; $i<count($data_custas); $i++){						
			$pdf->SetXY($x, $y);		
			$pdf->Cell(1, 0.5, strtoupper($data_custas[$i]->nome) . ": " . $this->converte($data_custas[$i]->valor), 0, 0, 'R');
			//se atigiu o limite da coluna, passa para a proxima coluna		
			if($cont == 4){				
				$cont = 0;
				$x += 3;
				$y = 4;				
			}			
			$y += 0.4;
			$cont++;
			$total += $data_custas[$i]->valor;
		}
		
		$total += $data_custas[0]->emolumento;
		
		$pdf->SetXY($x, $y);		
		$pdf->Cell(1, 0.5, "CUSTAS: " . $this->converte($data_custas[0]->emolumento), 0, 0, 'R');
		$pdf->SetXY($x, 5.55);
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(1, 0.5, "TOTAL: R$ " . $this->converte($total) , 0, 0, 'R');
		
		
		
		///////////////////////////////////////////////////////////////////
		$pdf->SetY(6.1);
		$pdf->Cell(18.6, 2, "", 1, 0, 'L');
		
		$pdf->SetXY(1.2, 6.1);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "DOCUMENTO: " . $this->ajustaCPF_CNPJ($data_devedor->numeroidentificacao, $data_devedor->tipoidentificacao), 0, 0, 'L');
				
		$pdf->SetXY(1.2, 6.6);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "DEVEDOR: " . $data_devedor->nome, 0, 0, 'L');
				
		$pdf->SetXY(1.2, 7.1);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "ENDEREÇO: " . trim($data_devedor->endereco) . ", " . $data_devedor->bairro, 0, 0, 'L');
				
		$pdf->SetXY(1.2, 7.6);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "CEP: " . $this->ajustaCEP($data_devedor->cep), 0, 0, 'L');
		
		$pdf->SetXY(6, 7.6);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "CIDADE: " . trim($data_devedor->cidade) . '-' . $data_devedor->estado, 0, 0, 'L');
		
		$pdf->SetXY(12, 7.6);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "PÇA PAGTO: " . $data_devedor->pracaprotesto, 0, 0, 'L');
		
		///////////////////////////////////////////////////////////////////
		$pdf->SetY(8.2);
		$pdf->Cell(18.6, 2, "", 1, 0, 'L');
		
		$pdf->SetXY(1.2, 8.2);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "CREDOR: " . $data_devedor->nomesacador, 0, 0, 'L');
				
		$pdf->SetXY(1.2, 8.7);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "FAVORECIDO: " . $data_devedor->nomecedente, 0, 0, 'L');
				
		$pdf->SetXY(1.2, 9.2);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "APRESENTANTE: " . $data_devedor->nomeapresentante, 0, 0, 'L');
				
		$pdf->SetXY(1.2, 9.7);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "AG./COD. CED: " . $data_devedor->codigocedente_agencia, 0, 0, 'L');
		
		$pdf->SetXY(7, 9.7);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "ENDOSSO: " . $tipoendosso, 0, 0, 'L');
		
		$pdf->SetXY(12, 9.7);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(3, 0.5, "Nº TÍTULO NO BANCO: " . $data_devedor->nossonumero, 0, 0, 'L');
						
		$pdf->Output("InstrumentodeProtesto_" . $data_devedor->idProtesto . ".pdf", "I");
		exit;
	}

	
	
	
	public function gerarInstrumentos($data_devedores, $data_cartorio, $data_autoridade){

		$pdf= new FPDF("P","cm","A4");
		$model_custas = new Custa();
		
		foreach($data_devedores as $data_devedor){
			
			$data_custas = $model_custas->getCustas($data_devedor->idProtesto, $data_devedor->valortitulo);
			
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
			
		
			$pdf->AddPage();	
			$pdf->Image('images/brasao.gif', 1.2, 1.2, 2.3);
			///////////////////////
			$pdf->SetFont('arial', 'B', 14);
			$pdf->Cell(18.6, 2.7, "", 1, 0, 'C');
			$pdf->SetY(1);
			$pdf->Cell(20, 1, "TABELIONATO DE PROTESTO DE TÍTULOS DE " . strtoupper($data_cartorio->cidade),0,1,'C');
			$pdf->SetFont('arial', 'B', 10);
			$pdf->SetY(1.8);
			$pdf->Cell(20, 1, $data_cartorio->tabeliao,0,1,'C');
			$pdf->SetY(2.3);
			$pdf->Cell(20, 1, "Tabelião",0,1,'C');		
			$pdf->SetY(3);
			$pdf->SetFont('arial', '', 8);
			$pdf->Cell(20, 1, $data_cartorio->endereco . " " . $data_cartorio->complemento . " - " . $data_cartorio->bairro . " - Fone/Fax: " . $this->ajustaTelefone($data_cartorio->telefone) . " - CEP: " . $data_cartorio->cep . " - " . $data_cartorio->cidade . "-". $data_cartorio->uf,0,0,'C');
			
			////////////////////////////
			$pdf->SetY(3.8);
			$pdf->Cell(18.6, 4.2, "", 1, 0, 'C');
			$pdf->SetXY(1, 3.8);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(7, 0.5, "INSTRUMENTO DE PROTESTO DE", 1, 0, 'C');
			$pdf->SetXY(1, 4.3);
			$pdf->SetFont('arial', 'B', 7);
			$pdf->Cell(7, 0.5, strtoupper($data_devedor->especie), 1, 0, 'C');
			
			$pdf->SetXY(8, 3.8);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "PROTESTO Nº", 1, 0, 'C');
			$pdf->SetXY(8, 4.3);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(3, 0.5, $data_devedor->idProtesto, 1, 0, 'C');
			
			$pdf->SetXY(11, 3.8);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(5, 0.5, "LIVRO/Nº SEQ.", 1, 0, 'C');
			$pdf->SetXY(11, 4.3);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(5, 0.5, $data_devedor->livro, 1, 0, 'C');
			
			$pdf->SetXY(16, 3.8);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3.6, 0.5, "FOLHA", 1, 0, 'C');
			$pdf->SetXY(16, 4.3);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(3.6, 0.5, $data_devedor->folha, 1, 0, 'C');
			
			/////////////////////////////////////////////////////
			$pdf->SetXY(1.2, 5);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "DADOS A FAVOR DE:", 0, 0, 'L');
			$pdf->SetXY(6, 5);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, $data_devedor->nomesacador, 0, 0, 'L');
			
			$pdf->SetXY(1.2, 5.5);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "CPF/CNPJ:", 0, 0, 'L');
			$pdf->SetXY(6, 5.5);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, $this->ajustaCPF_CNPJ($data_devedor->documentosacador, $tipoidsacador), 0, 0, 'L');
			
			$pdf->SetXY(1.2, 6);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "APRESENTANTE:", 0, 0, 'L');
			$pdf->SetXY(6, 6);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, $data_devedor->nomeapresentante, 0, 0, 'L');
			
			$pdf->SetXY(1.2, 6.5);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "ENDEREÇO:", 0, 0, 'L');
			$pdf->SetXY(6, 6.5);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, "", 0, 0, 'L');
			
			$pdf->SetXY(1.2, 7);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "DATA DE APRESENTAÇÃO:", 0, 0, 'L');
			$pdf->SetXY(6, 7);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, $this->getDataTimestamp($data_devedor->data_protocolo), 0, 0, 'L');
			
			$pdf->SetXY(1.2, 7.5);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "PROTOCOLO Nº:", 0, 0, 'L');
			$pdf->SetXY(6, 7.5);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, $data_devedor->protocolo, 0, 0, 'L');
			
			$pdf->SetXY(10.5, 7);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "PROTESTO POR FALTA DE:", 0, 0, 'L');
			$pdf->SetXY(15, 7);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, "Pagamento", 0, 0, 'L');
			
			////////////////////////////////////////
			$pdf->SetY(8.1);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(18.6, 0.5, "TÍTULO ANEXO AO PRESENTE (CÓPIA ARQUIVADA NESTE TABELIONATO)", 0, 0, 'L');
			
			/////////////////////////////////////////
			$pdf->SetY(8.6);
			$pdf->Cell(18.6, 7.5, "", 1, 0, 'L');
			
			$pdf->SetXY(1.2, 8.8);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "ESPÉCIE:", 0, 0, 'L');
			$pdf->SetXY(6, 8.8);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, strtoupper($data_devedor->especie), 0, 0, 'L');
			
			$pdf->SetXY(1.2, 9.3);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "TÍTULO Nº:", 0, 0, 'L');
			$pdf->SetXY(6, 9.3);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, $data_devedor->numerotitulo, 0, 0, 'L');
			
			$pdf->SetXY(1.2, 9.8);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "VENCIMENTO:", 0, 0, 'L');
			$pdf->SetXY(6, 9.8);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, $this->getDataTimestamp($data_devedor->vencimento), 0, 0, 'L');
			
			$pdf->SetXY(1.2, 10.3);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "Nº DO TÍTULO NO BANCO:", 0, 0, 'L');
			$pdf->SetXY(6, 10.3);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, $data_devedor->nossonumero, 0, 0, 'L');
			
			$pdf->SetXY(1.2, 10.8);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "VALOR DO TÍTULO:", 0, 0, 'L');
			$pdf->SetXY(6, 10.8);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, "R$ " . $this->converte($data_devedor->valortitulo), 0, 0, 'L');
			
			$pdf->SetXY(1.2, 11.3);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "DATA DE EMISSÃO:", 0, 0, 'L');
			$pdf->SetXY(6, 11.3);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, $this->getDataTimestamp($data_devedor->dataemissao), 0, 0, 'L');
			
			$pdf->SetXY(1.2, 11.8);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "ENDOSSO:", 0, 0, 'L');
			$pdf->SetXY(6, 11.8);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, $tipoendosso, 0, 0, 'L');
			
			$pdf->SetXY(1.2, 12.3);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "AG/CÓDIGO DO CEDENTE: ", 0, 0, 'L');
			$pdf->SetXY(6, 12.3);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, $data_devedor->codigocedente_agencia, 0, 0, 'L');
			
			$pdf->SetXY(1.2, 12.8);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "VALOR PROTESTADO:", 0, 0, 'L');
			$pdf->SetXY(6, 12.8);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, "R$ " . $this->converte($data_devedor->valortitulo), 0, 0, 'L');
			
			$pdf->SetXY(1.2, 13.3);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "VALOR POR EXTENSO:", 0, 0, 'L');
			$pdf->SetXY(6, 13.3);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, $this->extenso($data_devedor->valortitulo, true), 0, 0, 'L');
			
			$pdf->SetXY(1.2, 13.8);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "OBSERVAÇÃO:", 0, 0, 'L');
			$pdf->SetXY(6, 13.8);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, "", 0, 0, 'L');
			
			/////////////////////////////////////////
			$pdf->SetY(16.2);
			$pdf->Cell(18.6, 5, "", 1, 0, 'L');
			
			$pdf->SetXY(1.2, 16.4);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "DEVEDOR:", 0, 0, 'L');
			$pdf->SetXY(4, 16.4);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, $data_devedor->nome, 0, 0, 'L');
			
			$pdf->SetXY(1.2, 16.9);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "CPF/CNPJ:", 0, 0, 'L');
			$pdf->SetXY(4, 16.9);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, $this->ajustaCPF_CNPJ($data_devedor->numeroidentificacao, $data_devedor->tipoidentificacao), 0, 0, 'L');
			
			$pdf->SetXY(1.2, 17.4);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "ENDEREÇO:", 0, 0, 'L');
			$pdf->SetXY(4, 17.4);
			$pdf->SetFont('arial', 'B', 8.5);
			$pdf->Cell(7, 0.5, $endereco_devedor, 0, 0, 'L');
			
			$pdf->SetXY(1.2, 18.4);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "AVALISTA:", 0, 0, 'L');
			$pdf->SetXY(4, 18.4);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, "", 0, 0, 'L');
			
			$pdf->SetXY(1.2, 19.4);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "OUTROS DEV.:", 0, 0, 'L');
			$pdf->SetXY(4, 19.4);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(7, 0.5, "", 0, 0, 'L');
			
			////////////////////////////
			$pdf->SetY(21.3);
			$pdf->Cell(18.6, 4, "", 1, 0, 'L');
			
			$pdf->SetFont('arial','',9);
			$texto = "CERTIFICO QUE O DEVEDOR FOI NOTIFICADO A VIR PAGAR O REFERIDO TÍTULO, CONFORME RECIBO E NÃO COMPARECEU. CERTIFICO, AINDA, QUE A CÓPPIA DO TÍTULO DIFITALIZADA NO VERSO DESTE INSTRUMENTO CONFERE COM O ORIGINAL APRESENTADO NESTA SERVENTIA, PARA OS FINS DO DISPOSTO NO ART. 39 DA LEI Nº 9.492/97.";		
			$pdf->SetXY(1.2, 21.5);
			$pdf->MultiCell(18, 0.5, $texto, 0, 'J');
			
			$pdf->SetFont('arial','',9);
			$texto = "Eu, " . $data_autoridade->nome. ", " . $data_autoridade->cargo .", o digitei, subscrevi, conferi, dou fé e assino em público e raso. Palmas-TO, 13/03/2012. (Lavrado à hora legal) em test. ________________________ da verdade.";		
			$pdf->SetXY(1.2, 24);
			$pdf->MultiCell(18, 0.5, $texto, 0, 'J');
			
			//////////////////////////////////////////////////////		
			$pdf->SetY(25.4);
			$pdf->Cell(18.6, 2.2, "", 1, 0, 'L');
			
			$pdf->SetXY(1.2, 26.8);
			$pdf->SetFont('arial','',11);
			$pdf->Cell(8, 0.5, $data_cartorio->cidade . "-". $data_cartorio->uf . ", " . date('d/m/Y'), 0, 0, 'L');
			
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
			$y = 25.5;
			$cont = 1;		
			$total = 0;
			$pdf->SetFont('arial','',7);
			for($i=0; $i<count($data_custas); $i++){						
				$pdf->SetXY($x, $y);		
				$pdf->Cell(1, 0.5, strtoupper($data_custas[$i]->nome) . ": " . $this->converte($data_custas[$i]->valor), 0, 0, 'R');
				//se atigiu o limite da coluna, passa para a proxima coluna		
				if($cont == 4){				
					$cont = 0;
					$x += 3;
					$y = 25;				
				}			
				$y += 0.5;
				$cont++;
				$total += $data_custas[$i]->valor;
			}
			
			$total += $data_custas[0]->emolumento;
			
			$pdf->SetXY($x, $y);		
			$pdf->Cell(1, 0.5, "CUSTAS: " . $this->converte($data_custas[0]->emolumento), 0, 0, 'R');
			$pdf->SetXY(18.5, 27);
			$pdf->SetFont('arial','B',8);
			$pdf->Cell(1, 0.5, "TOTAL: R$ " . $this->converte($total) , 0, 0, 'R');
			
			//////////////////////////////////////////////////////////////////////////////////////////
			/////////////////////////////////// PRÓXIMA PÁGINA ///////////////////////////////////////
			//////////////////////////////////////////////////////////////////////////////////////////
			
			$pdf->AddPage();	
			
			$pdf->SetFont('arial', 'B', 18);
			$pdf->Cell(15, 2.7, "", 1, 0, 'C');
			$pdf->SetY(1.5);
			$pdf->Cell(16, 1, "ORDEM DE PROTESTO",0,1,'C');
			$pdf->SetFont('arial', '', 7);		
			$texto = "Nos termos do parágrafo único do art. 8º da Lei 9.492/97, trata-se o presente da INSTRUMENTALIZAÇÃO da indicação da duplicata abaixo caracterizada, recepcionada por meio magnético, sendo os dados de inteira responsabilidade do apresentante."; 		
			$pdf->SetXY(1, 2.5);
			$pdf->MultiCell(15, 0.5, $texto, 0, 'C');
			
			$pdf->SetXY(16.1, 1);
			$pdf->SetFont('arial', '', 10);
			$pdf->Cell(3.5, 0.9, "PROTOCOLO", 1, 0, 'C');		
			$pdf->SetXY(16.1, 1.9);
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(3.5, 0.9, "Nº: " . $data_devedor->protocolo, 1, 0, 'C');		
			$pdf->SetXY(16.1, 2.8);
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(3.5, 0.9, "Em: " . $this->getDataTimestamp($data_devedor->data_protocolo), 1, 0, 'C');
	
			////////////////////////////////////////////////////////
			$pdf->SetXY(1, 4);
			$pdf->SetFont('arial', 'B', 10);
			$pdf->Cell(5, 0.5, "codigo - ".$data_devedor->nomeapresentante, 0, 0, 'L');
			
			/////////////////////////////////////////////////////////
			$pdf->SetY(4.5);
			$pdf->Cell(18.6, 1.5, "", 1, 0, 'L');
			
			$pdf->SetXY(1, 4.46);
			$pdf->SetFont('arial', '', 7.6);
			$pdf->Cell(3, 0.5, "ESPÉCIE: " . strtoupper($data_devedor->especie), 0, 0, 'L');		
			
			$pdf->SetXY(1, 4.8);
			$pdf->Cell(2.3, 0.5, "VENCIMENTO: " . $this->getDataTimestamp($data_devedor->vencimento), 0, 0, 'L');		
					
			$pdf->SetXY(1, 5.2);
			$pdf->Cell(1.8, 0.5, "VALOR: " . $this->converte($data_devedor->valortitulo), 0, 0, 'L');		
					
			$pdf->SetXY(1, 5.6);
			$pdf->Cell(2, 0.5, "SALDO: ", 0, 0, 'L');		
			
			
			
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
			$y = 4.4;
			$cont = 1;		
			$total = 0;
			$pdf->SetFont('arial', '', 6);
			for($i=0; $i<count($data_custas); $i++){						
				$pdf->SetXY($x, $y);		
				$pdf->Cell(1, 0.5, strtoupper($data_custas[$i]->nome) . ": " . $this->converte($data_custas[$i]->valor), 0, 0, 'R');
				//se atigiu o limite da coluna, passa para a proxima coluna		
				if($cont == 4){				
					$cont = 0;
					$x += 3;
					$y = 4;				
				}			
				$y += 0.4;
				$cont++;
				$total += $data_custas[$i]->valor;
			}
			
			$total += $data_custas[0]->emolumento;
			
			$pdf->SetXY($x, $y);		
			$pdf->Cell(1, 0.5, "CUSTAS: " . $this->converte($data_custas[0]->emolumento), 0, 0, 'R');
			$pdf->SetXY($x, 5.55);
			$pdf->SetFont('arial','B',8);
			$pdf->Cell(1, 0.5, "TOTAL: R$ " . $this->converte($total) , 0, 0, 'R');
			
			
			
			///////////////////////////////////////////////////////////////////
			$pdf->SetY(6.1);
			$pdf->Cell(18.6, 2, "", 1, 0, 'L');
			
			$pdf->SetXY(1.2, 6.1);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "DOCUMENTO: " . $this->ajustaCPF_CNPJ($data_devedor->numeroidentificacao, $data_devedor->tipoidentificacao), 0, 0, 'L');
					
			$pdf->SetXY(1.2, 6.6);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "DEVEDOR: " . $data_devedor->nome, 0, 0, 'L');
					
			$pdf->SetXY(1.2, 7.1);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "ENDEREÇO: " . trim($data_devedor->endereco) . ", " . $data_devedor->bairro, 0, 0, 'L');
					
			$pdf->SetXY(1.2, 7.6);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "CEP: " . $this->ajustaCEP($data_devedor->cep), 0, 0, 'L');
			
			$pdf->SetXY(6, 7.6);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "CIDADE: " . trim($data_devedor->cidade) . '-' . $data_devedor->estado, 0, 0, 'L');
			
			$pdf->SetXY(12, 7.6);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "PÇA PAGTO: " . $data_devedor->pracaprotesto, 0, 0, 'L');
			
			///////////////////////////////////////////////////////////////////
			$pdf->SetY(8.2);
			$pdf->Cell(18.6, 2, "", 1, 0, 'L');
			
			$pdf->SetXY(1.2, 8.2);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "CREDOR: " . $data_devedor->nomesacador, 0, 0, 'L');
					
			$pdf->SetXY(1.2, 8.7);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "FAVORECIDO: " . $data_devedor->nomecedente, 0, 0, 'L');
					
			$pdf->SetXY(1.2, 9.2);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "APRESENTANTE: " . $data_devedor->nomeapresentante, 0, 0, 'L');
					
			$pdf->SetXY(1.2, 9.7);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "AG./COD. CED: " . $data_devedor->codigocedente_agencia, 0, 0, 'L');
			
			$pdf->SetXY(7, 9.7);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "ENDOSSO: " . $tipoendosso, 0, 0, 'L');
			
			$pdf->SetXY(12, 9.7);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(3, 0.5, "Nº TÍTULO NO BANCO: " . $data_devedor->nossonumero, 0, 0, 'L');
		}
		
		$data['arquivo'] = "IP" . date('dmY_his') . ".pdf";
    	
        $user = new Zend_Session_Namespace('user_data');
        $data['idUsuario'] = $user->user->idUsuario;
        $data['tipo'] = 11; //tipo Instrumento
            
        $arquivo = new Arquivo();
        $arquivo->insert($data);
        $lastId = $arquivo->getAdapter()->lastInsertId();
		
		
		$path = APPLICATION_PATH . '/arquivos/instrumentos';		
		if(!file_exists($path))mkdir($path);
		$path .= "/" . $lastId;
		
		$pdf->Output($path. ".pdf", "F"); 
		$pdf->Output("IP" .date('dmY_his') . ".pdf", "D"); exit;
		
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

}