<?php 
require('fpdf/fpdf.php');

class Begin_Certidao extends FPDF{
	
	public function positiva($data){
		
		$model_custas = new Custa();		
		$model_cartorio = new Cartorio();
		
		$data_custas = $model_custas->getCustas_certidaop();		
	    $data_cartorio = $model_cartorio->getCartorio();
		
		$tipo = 'CPF';
		if((int)$data[0]->tipoidentificacao == 1){
			$tipo = 'CNPJ';
		}
		
		$pdf= new FPDF("P","cm","A4");
		$pdf->AddPage();	
		$pdf->Image('images/brasao.gif', 1.2, 1.2, 3);
				
		
		$pdf->SetFont('times','B', 15);		
		$pdf->SetXY(5, 1);
		$pdf->MultiCell(12, 0.75, $this->upper($data_cartorio->nome), 0, 'C');
		
		$pdf->SetFont('times','IB', 10);
		$pdf->SetXY(5, 3);
		$pdf->Cell(5, 1, $data_cartorio->tabeliao,0,1,'C');
		$pdf->SetFont('times','B', 10);
		$pdf->SetXY(5, 3.3);
		$pdf->Cell(5, 1, "Oficial/Tabelião",0,1,'C');

		$pdf->SetFont('times','IB', 10);
		$pdf->SetXY(12, 3);
		$pdf->Cell(5, 1, $data_cartorio->substituto,0,1,'C');
		$pdf->SetXY(12, 3.3);
		$pdf->Cell(5, 1, $data_cartorio->escrevente,0,1,'C');
		$pdf->SetFont('times','B', 10);
		$pdf->SetXY(12, 3.6);
		$pdf->Cell(5, 1, "Substitutos",0,1,'C');
		
		
		$pdf->SetFont('times', 'IB', 25);
		$pdf->SetY(5);
		$pdf->Cell(18.6, 1, "CERTIDÃO POSITIVA DE PROTESTO", 0, 1,'C');		
		
		$pdf->SetFont('arial','', 10);
		$texto = "O OFICIAL/TABELIÃO DO CARTÓRIO DE REGISTRO CIVIL DE PESSOAS JÚRIDICAS, TÍTULOS E DOCUMENTOS E TABELIONATO DE PROTESTOS DA COMARCA DE PALMAS-TO.";		
		$pdf->SetXY(8.5, 7);
		$pdf->MultiCell(10, 0.65, $texto, 0, 'J');
		
		$pdf->SetFont('arial','', 11);
		$texto = '          Certifica e da fé, em virtude de requerimento de ' . trim($data[0]->nome) . ', inscrito(a) no ' . $tipo .' nº ' . $this->ajustaCPF_CNPJ($data[0]->numeroidentificacao, (int)$data[0]->tipoidentificacao) . ' que, revendo em cartório os livros de "Registro de Instrumento de Protesto", ENCONTROU '. count($data) . ' (' . $this->extenso_n(count($data)).' ) protesto(s) de títulos:';		
		$pdf->SetXY(1.5, 10.5);
		$pdf->MultiCell(17, 0.75, $texto, 0, 'J');
		
		$pdf->SetFont('arial','B', 7.5);
		$pdf->SetXY(1.5, 13);
		
		$altura = 0.3;
		
		$pdf->Cell(18, $altura,'', 1, 1, 'C');
		$pdf->SetXY(1.5, 13);		
		$pdf->Cell(1.8, $altura,'Apont.', 0, 0, 'C');		
		$pdf->Cell(1.8, $altura,'Vencimento', 0, 0, 'C');		
		$pdf->Cell(2.5, $altura,'Nº Título', 0, 0, 'C');
		$pdf->Cell(1.5, $altura,'Valor', 0, 0, 'C');		
		$pdf->Cell(5, $altura,'Credor', 0, 0, 'C');		
		$pdf->Cell(4.9, $altura,'Apresentante', 0, 1, 'C');
						
		$pdf->Ln($altura);
		
		$pdf->SetFillColor(224,235,255);
	    $pdf->SetTextColor(0);
	    $pdf->SetFont('arial','B', 6);
	    
	    $fill = true;
	    
	    $y = 13.5;
	    $pdf->SetXY(1.5, $y);
	    foreach ($data as $titulo){
	    	$pdf->Cell(1.8, $altura, $titulo->protocolo, 0, 0, 'L', $fill);
	    	$pdf->Cell(1.8, $altura, $titulo->vencimento, 0, 0, 'C', $fill);
	    	$pdf->Cell(2.5, $altura, trim($titulo->numerotitulo), 0, 0, 'C', $fill);
	    	$pdf->Cell(1.5, $altura, $titulo->valortitulo, 0, 0, 'C', $fill);
	    	$pdf->Cell(5.5, $altura, trim($titulo->nomesacador), 0, 0, 'C', $fill);
	    	$pdf->Cell(4.9, $altura, trim($titulo->nomeapresentante), 0, 0, 'C', $fill);
	    	$pdf->Ln($altura);
	    	$fill = !$fill;
	    	$y += $altura;
	    }
	       
	    $y += 1;
		
		$pdf->SetXY(3, $y);
		$pdf->SetFont('arial', '', 11);
		$pdf->Cell(17, 0.9, "Esta certidão se refere ao período de ", 0, 0, 'L');		
		$pdf->SetXY(9.5, $y);	
		$pdf->SetFont('arial', 'B', 11);
		$d= date('d/'); $m = date('m/'); $Y = date('Y') - 5;	
		$pdf->Cell(17, 0.9, $d.$m.$Y . ' à ' . date('d/m/Y') . '.', 0, 0, 'L');
		
		$y += 0.5;
		$pdf->SetXY(3, $y);
		$pdf->SetFont('arial', '', 11);
		$pdf->Cell(17, 0.9, "Nada mais quanto ao pedido feito. O referido é verdade e dou fé.", 0, 0, 'L');
		
		$y += 1;		
		$pdf->SetXY(2, $y);
		$pdf->SetFont('arial', 'B', 11);
		$pdf->Cell(17, 0.9, 'Palmas, ' . $this->_data() .', às ' . date('h') . 'h' . date('i') . 'min.', 0, 1, 'C');

		$y += 2;
		$pdf->SetXY(4, $y);
		$pdf->Cell(0.75, 0.4, '', 1, 0, 'L');
		$pdf->SetXY(5, $y-0.2);
		$pdf->SetFont('arial', 'IB', 7);
		$pdf->Cell(17, 0.5, $data_cartorio->tabeliao, 0, 0, 'L');
		$pdf->SetXY(5, $y+0.1);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(17, 0.5, 'Oficial/Tabelião', 0, 0, 'L');

		$y+=0.6;
		$pdf->SetXY(4, $y);
		$pdf->Cell(0.75, 0.4, '', 1, 0, 'L');
		$pdf->SetXY(5, $y-0.2);
		$pdf->SetFont('arial', 'IB', 7);
		$pdf->Cell(17, 0.5, $data_cartorio->substituto, 0, 0, 'L');
		$pdf->SetXY(5, $y+0.1);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(17, 0.5, 'Suboficial/Tabelião Substituto', 0, 0, 'L');
		
		$y+=0.6;
		$pdf->SetXY(4, $y);
		$pdf->Cell(0.75, 0.4, '', 1, 0, 'L');
		$pdf->SetXY(5, $y-0.2);
		$pdf->SetFont('arial', 'IB', 7);
		$pdf->Cell(17, 0.5,  $data_cartorio->escrevente, 0, 0, 'L');
		$pdf->SetXY(5, $y+0.1);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(17, 0.5, 'Escrevente Autorizado', 0, 0, 'L');	
		
		
		/******CALCULO DAS TAXAS DA CERTIDÃO******/
		$y += 2;
		$cont = 1;		
		$total = 0;
		
		foreach($data_custas as $custa){
			$pdf->SetFont('arial','B',7);
			$pdf->SetXY(1.5, $y);		
			$pdf->Cell(1, 0.5, strtoupper($custa->nome) . ": ", 0, 0, 'L');
			
			$pdf->SetFont('arial','',7);
			$pdf->SetXY(5, $y);		
			$pdf->Cell(1, 0.5, $this->converte($custa->valor), 0, 0, 'R');
			
			
			$y+=0.4;
			$total += $custa->valor;
		}
		
		$pdf->SetXY(1.5, $y);
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(1, 0.5, "TOTAL: "  , 0, 0, 'L');
		$pdf->SetXY(5, $y);
		$pdf->SetFont('arial','',8);
		$pdf->Cell(1, 0.5,$this->converte($total) , 0, 0, 'R');
		
		
		$pdf->Output("CERTIDÃO POSITIVA" . ".pdf", "I");
		exit;
	}
	
	public function negativa($data){
		
		$model_custas = new Custa();
		$model_cartorio = new Cartorio();
		$data_custas = $model_custas->getCustas_certidao();
		$data_cartorio = $model_cartorio->getCartorio();
		
		$tipo = 'CPF';
		if($data['tipo']==1){
			$tipo = 'CNPJ';
		}
		
		$pdf= new FPDF("P","cm","A4");
	
		$pdf->AddPage();	
				
		$pdf->SetFont('times', 'IB', 25);
		$pdf->SetY(6);
		$pdf->Cell(18.6, 1, "CERTIDÃO NEGATIVA DE PROTESTO", 0, 1,'C');		
		
		$pdf->SetFont('arial','', 11.5);
		$texto = "O OFICIAL/TABELIÃO DO CARTÓRIO DE REGISTRO CIVIL DE PESSOAS JÚRIDICAS, TÍTULOS E DOCUMENTOS E TABELIONATO DE PROTESTOS DA COMARCA DE PALMAS-TO.";		
		$pdf->SetXY(9, 8);
		$pdf->MultiCell(9, 0.75, $texto, 0, 'J');
		
		$pdf->SetFont('arial','', 11);
		$texto = '              Certifica e dá fé, em virtude de requerimento de pessoa interessada que, revendo em cartório os livros de "Registro de Instrumento de Protesto", NÃO ENCONTROU protesto de títulos contra';		
		$pdf->SetXY(1.5, 12.5);
		$pdf->MultiCell(17, 0.75, $texto, 0, 'J');
		
		$pdf->SetXY(2, 15);
		$pdf->SetFont('arial', 'B', 11);
		$pdf->Cell(17, 0.9, $data['nome'], 0, 1, 'C');
		$pdf->SetXY(7.4, 15.5);
		$pdf->SetFont('arial', '', 11);
		$pdf->Cell(17, 0.9, 'inscrito  no', 0, 0, 'L');		
		$pdf->SetXY(9.5, 15.5);
		$pdf->SetFont('arial', 'B', 11);
		$pdf->Cell(17, 0.9, $tipo . " " . $data['documento'] . '.', 0, 0, 'L');
		
		$pdf->SetXY(3, 18);
		$pdf->SetFont('arial', '', 11);
		$pdf->Cell(17, 0.9, "Esta certidão se refere ao período de ", 0, 0, 'L');		
		$pdf->SetXY(9.5, 18);	
		$pdf->SetFont('arial', 'B', 11);
		$d= date('d/'); $m = date('m/'); $Y = date('Y') - 5;	
		$pdf->Cell(17, 0.9, $d.$m.$Y . ' à ' . date('d/m/Y') . '.', 0, 0, 'L');
		$pdf->SetXY(3, 18.5);
		$pdf->SetFont('arial', '', 11);
		$pdf->Cell(17, 0.9, "Nada mais quanto ao pedido feito. O referido é verdade e dou fé.", 0, 0, 'L');
		
				
		$pdf->SetXY(2, 19.5);
		$pdf->SetFont('arial', 'B', 11);
		$pdf->Cell(17, 0.9, 'Palmas, ' . $this->_data() .', às ' . date('h') . 'h' . date('i') . 'min.', 0, 1, 'C');

		$pdf->SetXY(4, 22);
		$pdf->Cell(0.75, 0.4, '', 1, 0, 'L');
		$pdf->SetXY(5, 21.8);
		$pdf->SetFont('arial', 'IB', 7);
		$pdf->Cell(17, 0.5, $data_cartorio->tabeliao, 0, 0, 'L');
		$pdf->SetXY(5, 22.1);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(17, 0.5, 'Oficial/Tabelião', 0, 0, 'L');
				
		$pdf->SetXY(4, 22.7);
		$pdf->Cell(0.75, 0.4, '', 1, 0, 'L');
		$pdf->SetXY(5, 22.5);
		$pdf->SetFont('arial', 'IB', 7);
		$pdf->Cell(17, 0.5, $data_cartorio->substituto, 0, 0, 'L');
		$pdf->SetXY(5, 22.8);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(17, 0.5, 'Suboficial/Tabelião Substituto', 0, 0, 'L');
		
		$pdf->SetXY(4, 23.4);
		$pdf->Cell(0.75, 0.4, '', 1, 0, 'L');
		$pdf->SetXY(5, 23.2);
		$pdf->SetFont('arial', 'IB', 7);
		$pdf->Cell(17, 0.5, $data_cartorio->escrevente, 0, 0, 'L');
		$pdf->SetXY(5, 23.5);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(17, 0.5, 'Escrevente Autorizado', 0, 0, 'L');	
		
		
		/******CALCULO DAS TAXAS DA CERTIDÃO******/
		$y = 25.8;
		$cont = 1;		
		$total = 0;
		
		foreach($data_custas as $custa){
			$pdf->SetFont('arial','B',7);
			$pdf->SetXY(1.5, $y);		
			$pdf->Cell(1, 0.5, strtoupper($custa->nome) . ": ", 0, 0, 'L');
			
			$pdf->SetFont('arial','',7);
			$pdf->SetXY(5, $y);		
			$pdf->Cell(1, 0.5, $this->converte($custa->valor), 0, 0, 'R');
			
			
			$y+=0.4;
			$total += $custa->valor;
		}
		
		$pdf->SetXY(1.5, $y);
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(1, 0.5, "TOTAL: "  , 0, 0, 'L');
		$pdf->SetXY(5, $y);
		$pdf->SetFont('arial','',8);
		$pdf->Cell(1, 0.5,$this->converte($total) , 0, 0, 'R');
		
		
		$pdf->Output("CERTIDÃO NEGATIVA" . $data['nome'] . ".pdf", "I");
		exit;
	}
	
	public function inteiroTeor($data){
		
		$model_custas = new Custa();		
		$model_cartorio = new Cartorio();
		
		$data_custas = $model_custas->getCustas_certidao();		
	    $data_cartorio = $model_cartorio->getCartorio();
		
		$tipo = 'CPF';
		if((int)$data->tipoidentificacao == 1){
			$tipo = 'CNPJ';
		}
		
		$pdf= new FPDF("P","cm","A4");
		$pdf->AddPage();	
		$pdf->Image('images/brasao.gif', 1.2, 1.2, 3);
				
		
		$pdf->SetFont('times','B', 15);		
		$pdf->SetXY(5, 1);
		$pdf->MultiCell(12, 0.75, $this->upper($data_cartorio->nome), 0, 'C');
		
		$pdf->SetFont('times','IB', 10);
		$pdf->SetXY(5, 3);
		$pdf->Cell(5, 1, $data_cartorio->tabeliao,0,1,'C');
		$pdf->SetFont('times','B', 10);
		$pdf->SetXY(5, 3.3);
		$pdf->Cell(5, 1, "Oficial/Tabelião",0,1,'C');

		$pdf->SetFont('times','IB', 10);
		$pdf->SetXY(12, 3);
		$pdf->Cell(5, 1, $data_cartorio->substituto,0,1,'C');
		$pdf->SetXY(12, 3.3);
		$pdf->Cell(5, 1, $data_cartorio->escrevente,0,1,'C');
		$pdf->SetFont('times','B', 10);
		$pdf->SetXY(12, 3.6);
		$pdf->Cell(5, 1, "Substitutos",0,1,'C');
		
		
		$pdf->SetFont('times', 'IB', 25);
		$pdf->SetY(5);
		$pdf->Cell(18.6, 1, "CERTIDÃO DE INTEIRO TEOR", 0, 1,'C');		
		
		$pdf->SetFont('arial','', 10);
		$texto = "O OFICIAL/TABELIÃO DO CARTÓRIO DE REGISTRO CIVIL DE PESSOAS JÚRIDICAS, TÍTULOS E DOCUMENTOS E TABELIONATO DE PROTESTOS DA COMARCA DE PALMAS-TO.";		
		$pdf->SetXY(8.5, 7);
		$pdf->MultiCell(10, 0.65, $texto, 0, 'J');
		
		$pdf->SetFont('arial','', 11);
		$texto = '          Certifica e da fé, em virtude de requerimento de ' . trim($data->nome) . ', inscrito(a) no ' . $tipo .' nº ' . $this->ajustaCPF_CNPJ($data->numeroidentificacao, (int)$data->tipoidentificacao) . ', datado em '. date('d/m/Y') . ', que foi lavrado neste cartório abaixo discriminado:';		
		$pdf->SetXY(1.5, 10.5);
		$pdf->MultiCell(17, 0.75, $texto, 0, 'J');
		
		$pdf->SetFont('arial','', 10);
		$pdf->SetXY(1.5, 13);		
		$pdf->Cell(8.5, 0.5, 'Situação: ', 0, 0, 'L');
		$pdf->SetX(3.1);
		$pdf->SetFont('arial','B', 10);
		$pdf->Cell(8.5, 0.5, $this->upper($data->situacaoatual), 0, 0, 'L');
		$pdf->SetX(10);
		$pdf->SetFont('arial','', 10);
		$pdf->Cell(8.5, 0.5, 'Protestado em: ', 0, 0, 'L');		
		$pdf->SetX(12.5);
		$pdf->SetFont('arial','B', 10);
		$pdf->Cell(8.5, 0.5, $this->getDataTimestamp($data->data_protesto), 0, 1, 'L');
		
		$y = 13.5;	
		$pdf->SetXY(1.5, $y);
		$pdf->SetFont('arial','', 10);		
		$pdf->Cell(8.5, 0.5, 'Apontamento nº:', 0, 0, 'L');
		$pdf->SetX(4.3);
		$pdf->SetFont('arial','B', 10);
		$pdf->Cell(8.5, 0.5, $data->protocolo, 0, 0, 'L');
		$pdf->SetX(10);
		$pdf->SetFont('arial','', 10);
		$pdf->Cell(8.5, 0.5, 'Apresentado em cartório: ', 0, 0, 'L');
		$pdf->SetX(14.1);
		$pdf->SetFont('arial','B', 10);
		$pdf->Cell(8.5, 0.5, $this->getDataTimestamp($data->data_protocolo), 0, 1, 'L');
		
		$y += 0.5;	
		$pdf->SetXY(1.5, $y);
		$pdf->SetFont('arial','', 10);		
		$pdf->Cell(8.5, 0.5, 'Espécie:', 0, 0, 'L');
		$pdf->SetX(3.1);
		$pdf->SetFont('arial','B', 6.5);
		$pdf->Cell(8.5, 0.5, $this->upper($data->especie), 0, 0, 'L');
		$pdf->SetX(10);
		$pdf->SetFont('arial','', 10);
		$pdf->Cell(8.5, 0.5, 'Nº do Título: ', 0, 0, 'L');
		$pdf->SetX(12.1);
		$pdf->SetFont('arial','B', 10);
		$pdf->Cell(8.5, 0.5, $this->upper($data->numerotitulo), 0, 1, 'L');
		
		$y += 0.5;			
		$pdf->SetXY(1.5, $y);
		$pdf->SetFont('arial','', 10);
		$pdf->Cell(6, 0.5, 'Valor: R$ ', 0, 0, 'L');
		$pdf->SetX(3.1);
		$pdf->SetFont('arial','B', 10);
		$pdf->Cell(8.5, 0.5, $this->converte($data->valortitulo), 0, 0, 'L');
		$pdf->SetX(7.5);
		$pdf->SetFont('arial','', 10);
		$pdf->Cell(6, 0.5, 'Vencimento: ', 0, 0, 'L');
		$pdf->SetX(9.6);
		$pdf->SetFont('arial','B', 10);
		$pdf->Cell(8.5, 0.5, $this->getDataTimestamp($data->vencimento), 0, 0, 'L');
		$pdf->SetX(13.5);
		$pdf->SetFont('arial','', 10);
		$pdf->Cell(5, 0.5, 'Emissão: ', 0, 0, 'L');
		$pdf->SetX(15);
		$pdf->SetFont('arial','B', 10);
		$pdf->Cell(8.5, 0.5, $this->getDataTimestamp($data->dataemissao), 0, 1, 'L');
		
		$y += 0.5;			
		$pdf->SetXY(1.5, $y);
		$pdf->SetFont('arial','', 10);
		$pdf->Cell(10, 0.5, 'Sacado:', 0, 0, 'L');
		$pdf->SetX(3.1);
		$pdf->SetFont('arial','B', 10);
		$pdf->Cell(8.5, 0.5, $this->upper($data->nome), 0, 0, 'L');
		$pdf->SetX(11.5);
		$pdf->SetFont('arial','', 10);
		$pdf->Cell(7, 0.5, 'CPF/CNPJ: ', 0, 0, 'L');
		$pdf->SetX(13.5);
		$pdf->SetFont('arial','B', 10);
		$pdf->Cell(8.5, 0.5, $this->ajustaCPF_CNPJ($data->numeroidentificacao, (int)$data->tipoidentificacao), 0, 1, 'L');
		
		$y += 0.5;			
		$pdf->SetXY(1.5, $y);
		$pdf->SetFont('arial','', 10);
		$pdf->Cell(17, 0.5, 'Endereço:', 0, 0, 'L');
		$pdf->SetX(3.3);
		$pdf->SetFont('arial','B', 10);
		$pdf->Cell(8.5, 0.5, $data->endereco, 0, 1, 'L');
		
		$y += 0.5;			
		$pdf->SetXY(1.5, $y);
		$pdf->SetFont('arial','', 10);
		$pdf->Cell(10, 0.5, 'Credor:', 0, 0, 'L');
		$pdf->SetX(3.1);
		$pdf->SetFont('arial','B', 10);
		$pdf->Cell(8.5, 0.5, $this->upper($data->nomesacador), 0, 0, 'L');
		$pdf->SetX(11.5);
		$pdf->SetFont('arial','', 10);
		$pdf->Cell(7, 0.5, 'CPF/CNPJ: ', 0, 0, 'L');
		$pdf->SetX(13.5);
		$pdf->SetFont('arial','B', 10);
		$tipoidsacador = $data->tipoidentificacaosacador;
		if($tipoidsacador == null){
			$tipoidsacador = 1;
		}
		$pdf->Cell(8.5, 0.5, $this->ajustaCPF_CNPJ($data->documentosacador, $tipoidsacador), 0, 1, 'L');
		
		$y += 0.5;			
		$pdf->SetXY(1.5, $y);
		$pdf->SetFont('arial','', 10);
		$pdf->Cell(17, 0.5, 'Endereço:', 0, 0, 'L');
		
		$y += 0.5;
		$pdf->SetXY(1.5, $y);
		$pdf->SetFont('arial','', 10);
		$pdf->Cell(17, 1, 'Apresentante:', 0, 0, 'L');
		$pdf->SetXY(4, $y+0.25);
		$pdf->SetFont('arial','B', 10);
		$pdf->Cell(8.5, 0.5, $this->upper($data->nomeapresentante), 0, 1, 'L');
		
		
		
	       
	    $y += 2;		
		$pdf->SetY($y);
		$pdf->SetFont('arial', '', 11);
		$pdf->Cell(17, 0.9, "Certidão emitida em conformidade com o art. 27 da lei 9.492/97.", 0, 0, 'C');		
		
		$y += 0.5;
		$pdf->SetY($y);
		$pdf->SetFont('arial', '', 11);
		$pdf->Cell(17, 0.9, "Nada mais quanto ao pedido feito. O referido é verdade e dou fé.", 0, 0, 'C');
		
		$y += 1;		
		$pdf->SetY($y);
		$pdf->SetFont('arial', 'B', 11);
		$pdf->Cell(17, 0.9, 'Palmas, ' . $this->_data() .', às ' . date('h') . 'h' . date('i') . 'min.', 0, 1, 'C');

		$y += 2;
		$pdf->SetXY(4, $y);
		$pdf->Cell(0.75, 0.4, '', 1, 0, 'L');
		$pdf->SetXY(5, $y-0.2);
		$pdf->SetFont('arial', 'IB', 7);
		$pdf->Cell(17, 0.5, $data_cartorio->tabeliao, 0, 0, 'L');
		$pdf->SetXY(5, $y+0.1);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(17, 0.5, 'Oficial/Tabelião', 0, 0, 'L');

		$y+=0.6;
		$pdf->SetXY(4, $y);
		$pdf->Cell(0.75, 0.4, '', 1, 0, 'L');
		$pdf->SetXY(5, $y-0.2);
		$pdf->SetFont('arial', 'IB', 7);
		$pdf->Cell(17, 0.5, $data_cartorio->substituto, 0, 0, 'L');
		$pdf->SetXY(5, $y+0.1);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(17, 0.5, 'Suboficial/Tabelião Substituto', 0, 0, 'L');
		
		$y+=0.6;
		$pdf->SetXY(4, $y);
		$pdf->Cell(0.75, 0.4, '', 1, 0, 'L');
		$pdf->SetXY(5, $y-0.2);
		$pdf->SetFont('arial', 'IB', 7);
		$pdf->Cell(17, 0.5,  $data_cartorio->escrevente, 0, 0, 'L');
		$pdf->SetXY(5, $y+0.1);
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(17, 0.5, 'Escrevente Autorizado', 0, 0, 'L');	
		
		
		/******CALCULO DAS TAXAS DA CERTIDÃO******/
		$y += 2;
		$cont = 1;		
		$total = 0;
		
		foreach($data_custas as $custa){
			$pdf->SetFont('arial','B',7);
			$pdf->SetXY(1.5, $y);		
			$pdf->Cell(1, 0.5, strtoupper($custa->nome) . ": ", 0, 0, 'L');
			
			$pdf->SetFont('arial','',7);
			$pdf->SetXY(5, $y);		
			$pdf->Cell(1, 0.5, $this->converte($custa->valor), 0, 0, 'R');
			
			
			$y+=0.4;
			$total += $custa->valor;
		}
		
		$pdf->SetXY(1.5, $y);
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(1, 0.5, "TOTAL: "  , 0, 0, 'L');
		$pdf->SetXY(5, $y);
		$pdf->SetFont('arial','',8);
		$pdf->Cell(1, 0.5,$this->converte($total) , 0, 0, 'R');
		
		
		$pdf->Output("CERTIDÃO INTEIRO TEOR" . ".pdf", "I");
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
			
		return (date('d') . $mes . date('Y'));
	}
	
	function extenso_n($valor, $maiusculas=false){

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
			//$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
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

	function upper($_Str) {
		$_Str = strtoupper(trim($_Str));
		
		$Minusculo = array
		("á","à","ã","â","ä","é","è","ê","ë","í","ì","î", "ï","ó","ò","õ","ô","ö","ú","ù","û","ü","ç");
		
		$Maiusculo = array
		("Á","À","Ã","Â","Ä","É","È","Ê","Ë","Í","Ì","Î", "Ï","Ó","Ò","Õ","Ô","Ö","Ú","Ù","Û","Ü","Ç");
		
		for ( $X = 0; $X < count($Minusculo); $X++ ) { 
			$_Str = str_replace($Minusculo[$X], $Maiusculo[$X], $_Str); }
		return $_Str;
	}
}