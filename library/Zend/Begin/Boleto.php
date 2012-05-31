<?php 
require('fpdf/fpdf.php');

class Begin_Boleto extends FPDF{
	
	public function gerarBoleto($data_devedor, $data_cartorio, $data_custas){

		$endereco_devedor = trim($data_devedor->endereco) . ", " . trim($data_devedor->bairro) . " " . trim($data_devedor->cidade) . "-" . trim($data_devedor->estado) . " - " . $this->ajustaCEP(trim($data_devedor->cep));
		$valor_custas = ($data_custas[0]->emolumento + $data_custas[2]->valor + $data_custas[2]->valor + $data_custas[4]->valor +$data_custas[0]->valor + $data_custas[3]->valor);
		
		$codigobanco = "237";
		$nummoeda = "9";
		$fator_vencimento = $this->fator_vencimento($this->getDataTimestamp($data_devedor->vencimento));
		$valor = $this->fator_valor_digitavel($data_devedor->valortitulo + $valor_custas);
		$agencia = 2397;
		$nnum = '09' . $this->_numero($data_devedor->protocolo, 11) ;
		$conta_cedente = $this->_numero(4500, 7);
		$dv = $this->digitoVerificador_barra("$codigobanco$nummoeda$fator_vencimento$valor$agencia$nnum$conta_cedente".'0', 9, 0);;
		$linha_digitavel = "$codigobanco$nummoeda$dv$fator_vencimento$valor$agencia$nnum$conta_cedente"."0";
		
		$pdf= new FPDF("P","cm","A4");
	
		$pdf->AddPage();	
		$pdf->Image('images/brasao.gif', 1, 1.3, 2.3);	
		
		$pdf->SetFont('arial', 'B', 10);
		$pdf->SetXY(3.5,1);
		$pdf->Cell(10, 1, "República Federativa do Brasil",0,1,'C');
		$pdf->SetXY(3.5,1.5);
		$pdf->Cell(10, 1, "TABELIONATO DE PROTESTO DE TÍTULOS DE PALMAS",0,1,'C');
		$pdf->SetXY(1,2);
		$pdf->Cell(14.8, 1, $data_cartorio->endereco . " " . $data_cartorio->complemento . " - CEP: " . $data_cartorio->cep . " - " . $data_cartorio->cidade . " - ". $data_cartorio->uf,0,0,'C');
		$pdf->SetXY(3,2.5);
		$pdf->Cell(11, 1,$this->ajustaCPF_CNPJ($data_cartorio->cnpj, 1) ,0,1,'C');
		$pdf->SetXY(3	,3);
		$pdf->Cell(11, 1,"Fone/Fax: " . $this->ajustaTelefone($data_cartorio->telefone),0,1,'C');
		$pdf->SetXY(3,3.5);
		$pdf->Cell(11, 1,"Oficial/Tabelião: " . $data_cartorio->tabeliao,0,1,'C');
		//
		$pdf->SetXY(1.5, 4.5);
		$pdf->SetFont('arial', 'B', 8);
		$pdf->Cell(7, 1,"Destinatário: ",0,0,'L');
		$pdf->SetX(3.3);
		$pdf->SetFont('arial', '', 8);
		$pdf->Cell(5, 1, $data_devedor->nome,0,0,'L');
		$pdf->SetX(11.7);
		$pdf->SetFont('arial', 'B', 8);
		$pdf->Cell(5, 1,"CPF/CNPJ: ",0,0,'L');
		$pdf->SetX(13.5);
		$pdf->SetFont('arial', '', 8);
		$pdf->Cell(5, 1,$this->ajustaCPF_CNPJ($data_devedor->numeroidentificacao, $data_devedor->tipoidentificacao ),0,0,'L');
		
		$pdf->SetFont('arial', 'B', 8);
		$pdf->SetXY(1.5, 5);
		$pdf->Cell(4.7, 1,"Endereço: ",0,0,'L');
		$pdf->SetX(3);
		$pdf->SetFont('arial', '', 8);
		$pdf->Cell(5, 1,$endereco_devedor,0,0,'L');
		//
		$pdf->SetFont('arial', '', 8);
		$pdf->SetXY(0, 6);
		$pdf->Cell(9, 1,"Recebi a notificação protocolada sob número ",0,0,'C');
		$pdf->SetX(7.4);
		$pdf->SetFont('arial', '', 8);
		$pdf->Cell(8.5, 1,$data_devedor->protocolo,0,0,'L');
		
		$pdf->SetXY(0, 6.5);
		$pdf->Cell(17.3, 1,"Nome por extenso Legível: ____________________________________________________________________",0,0,'C');
		$pdf->SetXY(0, 7);
		$pdf->Cell(14.6, 1,"Data: ____ /____ /______        Horário:____:____          CPF: _______________________",0,1,'C');
		
		//
		$pdf->SetXY(1.6, 7.9);		
		$pdf->Cell(6,2,"",1,0,'C');
		$pdf->SetFont('arial','B',6.5);
		
		$pdf->SetXY(1.8, 8);
		$pdf->Cell(0.5,0.5,"",1,0,'L');
		$pdf->SetXY(2.3, 8);
		$pdf->Cell(0.5,0.5,"Mudou-se",0,0,'L');
		
		$pdf->SetXY(1.8, 8.6);		
		$pdf->Cell(0.5,0.5,"",1,0,'L');
		$pdf->SetXY(2.3, 8.6);
		$pdf->Cell(0.5,0.5,"Desconhecido",0,0,'L');
		
		$pdf->SetXY(1.8, 9.2);		
		$pdf->Cell(0.5,0.5,"",1,0,'L');
		$pdf->SetXY(2.3, 9.2);
		$pdf->Cell(0.5,0.5,"Recusado",0,0,'L');
		
		$pdf->SetXY(4.3, 8);		
		$pdf->Cell(0.5,0.5,"",1,0,'L');
		$pdf->SetXY(4.8, 8);		
		$pdf->Cell(0.5,0.5,"Número Inexistente",0,0,'L');
		
		$pdf->SetXY(4.3, 8.6);		
		$pdf->Cell(0.5,0.5,"",1,0,'L');
		$pdf->SetXY(4.8, 8.6);		
		$pdf->Cell(0.5,0.5,"Endereço Incompleto",0,0,'L');
		
		$pdf->SetXY(4.3, 9.2);		
		$pdf->Cell(0.5,0.5,"",1,0,'L');
		$pdf->SetXY(4.8, 9.2);		
		$pdf->Cell(0.5,0.5,"Outros",0,0,'L');
		//
		$pdf->SetXY(9.7, 7.9);		
		$pdf->Cell(6,2,"",1,0,'C');
		$pdf->SetFont('arial','B',8);
		$pdf->SetXY(12, 8);
		$pdf->Cell(1,0.5,"Tentativas de Entrega",0,0,'C');
		$pdf->SetXY(12, 8.4);
		$pdf->Cell(1,0.5,"____ /____ /______  ____:____ Horas",0,0,'C');
		$pdf->SetXY(12, 8.9);
		$pdf->Cell(1,0.5,"____ /____ /______  ____:____ Horas",0,0,'C');
		$pdf->SetXY(12, 9.4);
		$pdf->Cell(1,0.5,"____ /____ /______  ____:____ Horas",0,0,'C');
		
		
		//
		$pdf->SetXY(13.55, 1);		
		$pdf->Cell(6.5,3,"",1,0,'C');
		$pdf->SetFont('arial', 'B', 20);
		$pdf->SetXY(13.3, 1);		
		$pdf->Cell(7, 1, "AR", 0, 1, 'C');
		$pdf->SetXY(13.3, 2);
		$pdf->SetFont('arial', 'B', 12);
		$pdf->Cell(7,1,"Protocolo: " . $data_devedor->protocolo,0,1,'C');
		$pdf->SetXY(13.3, 3);
		$pdf->Cell(7,1,"Data do protocolo: " . $this->getDataTimestamp($data_devedor->data_protocolo),0,1,'C');
		
		//$pdf->Ln(5);
		
		////////////
		$pdf->SetY(11);
		$pdf->SetFont('arial', 'B', 8);		
		$pdf->SetXY(1.5, 14.5);
		$pdf->Cell(4.5, 1,"Destinatário: ",0,0,'L');
		$pdf->SetX(3.3);
		$pdf->SetFont('arial', '', 8);
		$pdf->Cell(5, 1, $data_devedor->nome,0,0,'L');
				
		$pdf->SetFont('arial', 'B', 8);
		$pdf->SetXY(1.5, 15);
		$pdf->Cell(4.5, 1,"Endereço: ",0,0,'L');
		$pdf->SetX(3);
		$pdf->SetFont('arial', '', 8);
		$pdf->Cell(5, 1, $endereco_devedor ,0,0,'L');
		
		//
		$pdf->SetXY(13.5, 11);		
		$pdf->Cell(6.5, 3, "", 1, 1, 'C');
		$pdf->SetFont('arial', 'B', 20);
		$pdf->SetXY(13.3, 11);		
		$pdf->Cell(7, 1, "AR", 0, 1, 'C');
		$pdf->SetXY(13.3, 12);
		$pdf->SetFont('arial', 'B', 12);	
		$pdf->Cell(7, 1, "Protocolo: " . $data_devedor->protocolo,0,1,'C');
		$pdf->SetXY(13.3, 13);
		$pdf->Cell(7, 1, "Data do protocolo: " .$this->getDataTimestamp($data_devedor->data_protocolo),0,1,'C');
		
		/////////////
		$pdf->SetY(18.5);
		$pdf->SetFont('arial', 'B', 18);
		$pdf->Cell(19, 9, "", 1, 0, 'C');
		$pdf->SetY(22);
		$pdf->Cell(19, 1, "Horário de Atendimento",0,1,'C');
		$pdf->SetY(22.8);
		$pdf->Cell(19, 1, "ao Público",0,1,'C');
		$pdf->SetY(23.6);
		$pdf->Cell(19, 1, "08:00 às 17:00",0,1,'C');
		
		
		
		
		
		$pdf->AddPage();
		
		$pdf->SetFont('arial', 'B', 20);
		$pdf->SetXY(1,1);	
		$pdf->Cell(19, 7.5, "", 1, 0, 'R');
		$pdf->SetXY(13.5,1);
		$pdf->Cell(6.5, 3, "", 1, 1, 'C');
		$pdf->SetXY(13.3,1);
		$pdf->Cell(7, 1, "AR", 0, 1, 'C');
		$pdf->SetFont('arial', 'B', 12);
		$pdf->SetXY(13.3,2);
		$pdf->Cell(7, 1, "Protocolo: " . $data_devedor->protocolo,0,1,'C');
		$pdf->SetXY(13.3,3);
		$pdf->Cell(7, 1, "Data do protocolo: " .$this->getDataTimestamp($data_devedor->data_protocolo),0,1,'C');
		
		$pdf->SetFont('arial', '', 7);
		$pdf->SetY(9);
		$pdf->Cell(0, 0.5, "------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0, 'C');

		$pdf->Image('images/brasao.gif', 1, 10.3, 2.3);
		$pdf->SetFont('arial', 'B', 20);
		$pdf->SetXY(13.5,10);
		$pdf->Cell(6.5, 3, "", 1, 1, 'C');
		$pdf->SetXY(10.3,10);
		$pdf->Cell(13, 1, "INTIMAÇÃO", 0, 1, 'C');
		$pdf->SetFont('arial', 'B', 12);
		$pdf->SetXY(13.3,11.1);
		$pdf->Cell(7, 1, "Protocolo: " . $data_devedor->protocolo,0,1,'C');
		$pdf->SetXY(13.3,12);
		$pdf->Cell(7, 1, "Data do protocolo: " .$this->getDataTimestamp($data_devedor->data_protocolo),0,1,'C');
		
		$pdf->SetXY(3.5,9.7);
		$pdf->SetFont('arial', 'B', 10);
		$pdf->Cell(10, 1, "República Federativa do Brasil",0,1,'C');
		$pdf->SetXY(3.5,10.15);
		$pdf->Cell(10, 1, "TABELIONATO DE PROTESTO DE TÍTULOS DE PALMAS",0,1,'C');
		$pdf->SetXY(1,10.6);
		$pdf->Cell(14.8, 1, $data_cartorio->endereco . " " . $data_cartorio->complemento . " - CEP: " . $data_cartorio->cep . " - " . $data_cartorio->cidade . " - ". $data_cartorio->uf,0,0,'C');
		$pdf->SetXY(3,11.1);
		$pdf->Cell(11, 1, "CNPJ - ".$this->ajustaCPF_CNPJ($data_cartorio->cnpj,1),0,0,'C');
		$pdf->SetXY(3,11.6);
		$pdf->Cell(11, 1, "Fone/Fax: " . $this->ajustaTelefone($data_cartorio->telefone),0,0,'C');
		$pdf->SetXY(3,12.1);
		$pdf->Cell(11, 1, "Oficial/Tabelião: " . $data_cartorio->tabeliao,0,0,'C');
		
		$pdf->SetXY(1,13);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(12, 1, "Destinatário: " ,0,1,'L');
		$pdf->SetXY(3,13);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(15, 1, trim($data_devedor->nome) ,0,1,'L');
		
		$pdf->SetXY(1,13.5);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(10, 1, "Endereço: " ,0,1,'L');
		$pdf->SetXY(2.6,13.5);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(15, 1, trim($data_devedor->endereco) ,0,1,'L');
		
		$pdf->SetXY(1,14);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(12, 1, "Apresentante: " ,0,1,'L');
		$pdf->SetXY(3.2,14);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(15, 1, trim($data_devedor->nomesacador) ,0,1,'L');
		
		$pdf->SetXY(1,14.5);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(12, 1, "Cedente/Credor: " ,0,1,'L');
		$pdf->SetXY(3.6,14.5);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(15, 1, trim($data_devedor->nomecedente) ,0,1,'L');
		
		$pdf->SetXY(1,15);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(14, 1, "Sacador/Favorecido: " ,0,1,'L');
		$pdf->SetXY(4.2,15);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(15, 1, trim($data_devedor->nomesacador) ,0,1,'L');
		
		$pdf->SetXY(1,15.5);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(14, 1, "Número do Título: " ,0,1,'L');
		$pdf->SetXY(3.8,15.5);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(15, 1, $data_devedor->numerotitulo ,0,1,'L');
		
		$pdf->SetXY(1,16);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(12, 1, "Vencimento: " ,0,1,'L');
		$pdf->SetXY(3,16);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(15, 1, $this->getDataTimestamp($data_devedor->vencimento) ,0,1,'L');
		
		$pdf->SetXY(8,15.5);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(8, 1, "Espécie: " ,0,1,'L');
		$pdf->SetXY(9.4,15.5);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(15, 1, $data_devedor->especie ,0,1,'L');
		
		$pdf->SetXY(8,16);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(12, 1, "Valor Saldo Título: " ,0,1,'L');
		$pdf->SetXY(10.9,16);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(15, 1, "R$ " . $this->converte($data_devedor->valortitulo) ,0,1,'L');
		
		$pdf->SetXY(9.2,13);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(8, 1, "CPF/CNPJ: " ,0,1,'R');
		$pdf->SetXY(14.1,13);
		$pdf->SetFont('arial', '', 9);
		if(isset($data_devedor->tipoidentificacaosacador))
			$tipoidsacador = $data_devedor->tipoidentificacaosacador;
		else
			$tipoidsacador = 1;
		$pdf->Cell(6, 1, $this->ajustaCPF_CNPJ($data_devedor->documentosacador, $tipoidsacador) ,0,1,'R');
		
		$pdf->SetXY(12.4,15.5);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(6, 1, "Emissão: " ,0,1,'R');
		$pdf->SetXY(14,15.5);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(6, 1, date("d/m/Y") ,0,1,'R');
		
		$pdf->SetXY(10.5,16);
		$pdf->SetFont('arial', 'B', 9);
		$pdf->Cell(8, 1, "Valor das Custas: ",0,1,'R');
		$pdf->SetXY(14,16);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(6, 1, 'R$ '. $this->converte($valor_custas) ,0,1,'R');
		
		$pdf->SetXY(1,16.5);
		$pdf->SetFont('arial',  '', 7.6);
		$pdf->Cell(20, 1, "Encontra-se nesta Serventia o título ou documento de dívida acima caracterizado. Pelo presente intimo Vossa senhoria a efetuar o pagamento por este boleto" ,0,1,'L');
		$pdf->SetY(16.9);
		$pdf->Cell(20, 1, "ou dar as razões porque não o faz, sob pena de o mesmo ser protestado na forma da legislação em vigor." ,0,1,'L');
		
		$pdf->SetXY(1,17.3);
		$pdf->SetFont('arial',  '', 7.6);
		$pdf->Cell(5, 1, "Emolumentos: R$". 	  $this->converte($data_custas[0]->emolumento).
						' - FUNCIVIL: R$'. 		  $this->converte($data_custas[2]->valor).  
						' - Taxa Judiciária: R$'. $this->converte($data_custas[4]->valor). 
						' - Processamento: R$' .  $this->converte($data_custas[0]->valor). 
						' - Intimação: R$'.		  $this->converte($data_custas[3]->valor),0,1,'L');
		
		$pdf->SetXY(1,18);
		$pdf->SetFont('arial',  'B', 10);
		$pdf->Cell(4, 0.6, "BRADESCO" ,1,1,'L');
		
		$pdf->SetXY(5,18);
		$pdf->Cell(2, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(5,17.9);
		$pdf->Cell(2, 0.6, "Espécie Doc." ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(5,18.15);
		$pdf->Cell(2, 0.6, "OU" ,0,1,'C');
		
		$pdf->SetXY(7,18);
		$pdf->Cell(5, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(7,17.9);
		$pdf->Cell(5, 0.6, "Número do Título" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(7,18.15);
		$pdf->Cell(5, 0.6, $data_devedor->numerotitulo ,0,1,'L');
		
		$pdf->SetXY(12,18);
		$pdf->Cell(2.6, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(12,17.9);
		$pdf->Cell(2.6, 0.6, "Vencimento" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(12,18.15);
		$pdf->Cell(2.6, 0.6, $this->getDataTimestamp($data_devedor->vencimento) ,0,1,'R');
		
		$pdf->SetXY(14.6,18);
		$pdf->Cell(5.1, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(14.6,17.9);
		$pdf->Cell(5.1, 0.6, "Valor a Pagar" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(14.6,18.15);
		$pdf->Cell(5.1, 0.6, $this->converte($data_devedor->valortitulo + $valor_custas) ,0,1,'R');
		
		$pdf->SetXY(1,18.6);
		$pdf->Cell(11, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(1,18.45);
		$pdf->Cell(11, 0.6, "Sacado" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(1,18.75);
		$pdf->Cell(11	, 0.6, trim($data_devedor->nomesacador) ,0,1,'L');
		
		$pdf->SetXY(12,18.6);
		$pdf->Cell(7.7, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(12,18.45);
		$pdf->Cell(7.7, 0.6, "Valor Pago - R$" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(12,18.75);
		$pdf->Cell(7.7, 0.6, $this->converte($data_devedor->valortitulo + $valor_custas) ,0,1,'R');
		
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(2,19.1);
		$pdf->Cell(7.7, 0.6, 'Via do Cliente' ,0,1,'l');
		
		$pdf->SetFont('arial', '', 7);
		$pdf->SetY(19.5);
		$pdf->Cell(0, 0.5, "------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0, 'C');
		
		//Boleto
		
		$pdf->SetXY(1,20.2);
		$pdf->SetFont('arial',  'B', 10);
		$pdf->Cell(4, 0.7, "BRADESCO" ,1,1,'L');
		
		$pdf->SetXY(5,20.2);
		$pdf->SetFont('arial','B',12)	;
		$pdf->Cell(1.8, 0.7, "237-2" ,1,1,'C');
		
		$pdf->SetXY(6.8,20.2);
		$pdf->SetFont('arial','B',11)	;
		$pdf->Cell(12.9, 0.7, $this->monta_linha_digitavel($linha_digitavel) .' ' .  $this->fator_vencimento($this->getDataTimestamp($data_devedor->vencimento)) . $this->fator_valor($data_devedor->valortitulo + $valor_custas) ,1,1,'C');
		
		$pdf->SetXY(1,20.9);
		$pdf->Cell(13, 0.7, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(1,20.7);
		$pdf->Cell(13, 0.7, "Local" ,0,1,'L');
		$pdf->SetFont('arial',  '', 9)	;
		$pdf->SetXY(1,21);
		$pdf->Cell(13, 0.7, "Pagável preferencialmente nas agências do Bradesco até a data do vencimento." ,0,1,'L');
		
		$pdf->SetXY(14,20.9);
		$pdf->SetFillColor(255);
		$pdf->Cell(5.7, 0.7, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(14,20.7);
		$pdf->Cell(5.7, 0.7, "Vencimento " ,0,1,'L');
		$pdf->SetFont('arial',  '', 9)	;
		$pdf->SetXY(14,21);
		$pdf->Cell(5.7, 0.7, $this->getDataTimestamp($data_devedor->vencimento) ,0,1,'R');
		
		$pdf->SetXY(1,21.6);
		$pdf->Cell(13, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(1,21.45);
		$pdf->Cell(13, 0.6, "Cedente" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8)	;
		$pdf->SetXY(1,21.75);
		$pdf->Cell(13, 0.6, $data_cartorio->nome ,0,1,'L');
		
		$pdf->SetXY(14,21.6);
		$pdf->SetFillColor(255);
		$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(14,21.45);
		$pdf->Cell(5.7, 0.6, "Agência / Código do Cedente " ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(14,21.75);
		$pdf->Cell(5.7, 0.6, $this->agencia_cedente(23973, 45004),0,1,'R');
		
		$pdf->SetXY(1,22.2);
		$pdf->Cell(3.5, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(1,22.05);
		$pdf->Cell(3.5, 0.6, "Data de Emissão" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(1,22.35);
		$pdf->Cell(3.5, 0.6, date('d/m/Y') ,0,1,'R');
		
		$pdf->SetXY(4.5,22.2);
		$pdf->Cell(3.5, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(4.5,22.05);
		$pdf->Cell(3.5, 0.6, "Número do Documento" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(4.5,22.35);
		$pdf->Cell(3.5, 0.6, $data_devedor->numerotitulo ,0,1,'R');
		
		$pdf->SetXY(8,22.2);
		$pdf->Cell(2, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(8,22.05);
		$pdf->Cell(2, 0.6, "Espécie Doc." ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(8,22.35);
		$pdf->Cell(2, 0.6, "OU" ,0,1,'C');
		
		$pdf->SetXY(10,22.2);
		$pdf->Cell(1, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(10,22.05);
		$pdf->Cell(1, 0.6, "Aceite" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(10,22.35);
		$pdf->Cell(1, 0.6, "N" ,0,1,'C');
		
		$pdf->SetXY(11,22.2);
		$pdf->Cell(3, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(11,22.05);
		$pdf->Cell(3, 0.6, "Data do Processamento" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(11,22.35);
		$pdf->Cell(3, 0.6, $this->getDataTimestamp($data_devedor->data_protocolo) ,0,1,'R');
		
		$pdf->SetXY(14,22.2);
		$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(14,22.05);
		$pdf->Cell(5.7, 0.6, "Carteira / Nosso Número" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(14,22.35);
		$pdf->Cell(5.7, 0.6, '09 / ' . $this->nossonumero($data_devedor->protocolo) ,0,1,'R');
		
		$pdf->SetXY(1,22.8);
		$pdf->Cell(2.5, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(1,22.65);
		$pdf->Cell(2.5, 0.6, "Uso do Banco" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(1,22.9);
		$pdf->Cell(2.5, 0.6, "" ,0,1,'R');
		
		$pdf->SetXY(3.5,22.8);
		$pdf->Cell(1, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(3.5,22.65);
		$pdf->Cell(1, 0.7, "CIP" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(3.5,22.9);
		$pdf->Cell(1, 0.7, "000" ,0,1,'R');
		
		$pdf->SetXY(4.5,22.8);
		$pdf->Cell(1.75, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(4.5,22.7);
		$pdf->Cell(1.75, 0.6, "Carteira" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(4.5,22.97);
		$pdf->Cell(1.75, 0.6, "09" ,0,1,'C');
		
		$pdf->SetXY(6.25,22.8);
		$pdf->Cell(1.75, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(6.25,22.65);
		$pdf->Cell(1.75, 0.6, "Moeda" ,0,1,'L');
		$pdf->SetFont('arial',  '', 8.5)	;
		$pdf->SetXY(6.25,22.9);
		$pdf->Cell(1.75, 0.6, "R$" ,0,1,'C');
		
		$pdf->SetXY(8,22.8);
		$pdf->Cell(3, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(8,22.65);
		$pdf->Cell(3, 0.6, "Quantidade" ,0,1,'L');
		$pdf->SetFont('arial',  '', 9)	;
		$pdf->SetXY(8,22.9);
		$pdf->Cell(3, 0.6, "" ,0,1,'C');
		
		$pdf->SetXY(11,22.8);
		$pdf->Cell(3, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(11,22.65);
		$pdf->Cell(3, 0.6, "Valor" ,0,1,'L');
		$pdf->SetFont('arial',  '', 9)	;
		$pdf->SetXY(11,22.9);
		$pdf->Cell(3, 0.6, "" ,0,1,'C');
		
		$pdf->SetXY(14,22.8);
		$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(14,22.65);
		$pdf->Cell(5.7, 0.6, "Valor do Documento" ,0,1,'L');
		$pdf->SetFont('arial',  '', 9)	;
		$pdf->SetXY(14,22.9);
		$pdf->Cell(5.7, 0.6, $this->converte($data_devedor->valortitulo + $valor_custas) ,0,1,'R');
		
		$pdf->SetXY(1,23.4);
		$pdf->Cell(13, 3, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(1,23.3);
		$pdf->Cell(2, 0.6, "Instruções" ,0,1,'L');
		$pdf->SetXY(1,23.6);
		$pdf->Cell(2, 0.6, "Texto de responsabilidade do Cliente" ,0,1,'L');
		$pdf->SetXY(1,24);
		$pdf->SetFont('arial',  '', 8);
		$pdf->Cell(2, 0.6, "SENHOR CAIXA: " ,0,1,'L');
		$pdf->SetXY(3.3,24);
		$pdf->SetFont('arial',  'BU', 8);
		$pdf->Cell(2, 0.6, "NÃO RECEBER PAGAMENTO EM CHEQUE " ,0,1,'L');
		$pdf->SetXY(3.3,24.4);
		$pdf->SetFont('arial',  'BU', 8);
		$pdf->Cell(2, 0.6, "NÃO RECEBER APÓS O VENCIMENTO " ,0,1,'L');
		$pdf->SetFont('arial',  '', 7.5);
		$pdf->SetXY(1,24.8);
		$pdf->Cell(2, 0.6, "Este boleto, devidamentee autenticado pelo banco, possui prova da quitação do título do documento de" ,0,1,'L');
		$pdf->SetXY(1,25.1);
		$pdf->Cell(2, 0.6, "dívida a que se refere. Pagável em qualquer banco até a data do vencimento, após isso perderá a validade." ,0,1,'L');
		$pdf->SetXY(1,25.6);
		$pdf->Cell(2, 0.6, 'Vl. Saldo título: R$'. 	$this->converte($data_devedor->valortitulo)
							.' - Emolumentos: R$'. 		$this->converte($data_custas[0]->emolumento)
							.' - FUNCIVIL: R$'. 		$this->converte($data_custas[2]->valor)
							.' - Taxa Judiciária: R$'. 	$this->converte($data_custas[4]->valor) .' -' ,0,1,'L');
		$pdf->SetXY(1,25.9);
		$pdf->Cell(2, 0.6, 'Processamento: R$'. 	$this->converte($data_custas[0]->valor)
						.' - Intimação: R$' .		$this->converte($data_custas[3]->valor) ,0,1,'L');
		
		$pdf->SetXY(14,23.4);
		$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(14,23.25);
		$pdf->Cell(5.7, 0.7, "( - ) Desconto" ,0,1,'L');
		
		$pdf->SetXY(14,24);
		$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(14,23.9);
		$pdf->Cell(5.7, 0.6, "( - ) Outras Deduções" ,0,1,'L');
		
		$pdf->SetXY(14,24.6);
		$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(14,24.5);
		$pdf->Cell(5.7, 0.6, "( + ) Mora/Multa" ,0,1,'L');
		
		$pdf->SetXY(14,25.2);
		$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(14,25.1);
		$pdf->Cell(5.7, 0.6, "( + ) Outros Acréscimos" ,0,1,'L');
		
		$pdf->SetXY(14,25.8);
		$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(14,25.7);
		$pdf->Cell(5.7, 0.6, "( = ) Valor Cobrado" ,0,1,'L');
		
		$pdf->SetXY(1,26.4);
		$pdf->Cell(18.7, 1.2, "" ,1,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(1,26.3);
		$pdf->Cell(5.7, 0.6, "Sacado" ,0,1,'L');
		$pdf->SetFont('arial',  'B', 8)	;
		$pdf->SetXY(2,26.3);
		$pdf->Cell(5.7, 0.6, trim($data_devedor->nome) ,0,1,'L');
		$pdf->SetFont('arial',  '', 8)	;
		$pdf->SetXY(2,26.7);
		$pdf->Cell(5.7, 0.6, trim($data_devedor->endereco) ,0,1,'L');
		$pdf->SetFont('arial',  '', 7)	;
		$pdf->SetXY(1,27.05);
		$pdf->Cell(5.7, 0.6, "Sacado/Avalista" ,0,1,'L');
		
		$barcode = $this->remove_char($this->monta_linha_digitavel($linha_digitavel).$this->fator_vencimento($this->getDataTimestamp($data_devedor->vencimento)).$this->fator_valor($data_devedor->valortitulo + $valor_custas));
		
		$path = APPLICATION_PATH . '/arquivos/barcode';
		
		if(!file_exists($path))mkdir($path);
		
		$path .= "/barcode";
		
		$config = new Zend_Config(array(
								'barcode'        => 'Code25interleaved',
								'barcodeParams'  => array(	'text' 		=> $barcode, 'drawText'	=> FALSE, 'barThickWidth' => 2),
								'renderer'       => 'image',
								'rendererParams' => array('imageType' => 'jpg'),
		));
		
		$renderer = Zend_Barcode::factory($config)->draw();
		
		imagejpeg($renderer, $path . $data_devedor->idProtesto.'.jpg', 100); 

		$image = Zend_Pdf_Image::imageWithPath($path . $data_devedor->idProtesto.'.jpg');
		
		imagedestroy($renderer); 
		
		$pdf->Image($path . $data_devedor->idProtesto . '.jpg', 0.75, 27.8, 10);
		
		$pdf->Output("Notificação_" . $data_devedor->idProtesto . ".pdf","I"); exit;
		
	}

	public function gerarBoletos($data_devedores, $data_cartorio){

		$model_custas = new Custa();
		
		$pdf= new FPDF("P","cm","A4");
		
		foreach($data_devedores as $data_devedor){
			
			$data_custas = $model_custas->getCustas($data_devedor->idProtesto, $data_devedor->valortitulo);
			
			$endereco_devedor = trim($data_devedor->endereco) . ", " . trim($data_devedor->bairro) . ", " . trim($data_devedor->cidade) . "-" . trim($data_devedor->estado) . " - " . $this->ajustaCEP(trim($data_devedor->cep));
			$valor_custas = ($data_custas[0]->emolumento + $data_custas[2]->valor + $data_custas[2]->valor + $data_custas[4]->valor +$data_custas[0]->valor + $data_custas[3]->valor);
			
			$codigobanco = "237";
			$nummoeda = "9";
			$fator_vencimento = $this->fator_vencimento($this->getDataTimestamp($data_devedor->vencimento));
			$valor = $this->fator_valor_digitavel($data_devedor->valortitulo + $valor_custas);
			$agencia = 2397;
			$nnum = '09' . $this->_numero($data_devedor->protocolo, 11) ;
			$conta_cedente = $this->_numero(4500, 7);
			$dv = $this->digitoVerificador_barra("$codigobanco$nummoeda$fator_vencimento$valor$agencia$nnum$conta_cedente".'0', 9, 0);;
			$linha_digitavel = "$codigobanco$nummoeda$dv$fator_vencimento$valor$agencia$nnum$conta_cedente"."0";
		
			$pdf->AddPage();	
			$pdf->Image('images/brasao.gif', 1, 1.3, 2.3);	
			
			$pdf->SetFont('arial', 'B', 10);
			$pdf->SetXY(3.5,1);
			$pdf->Cell(10, 1, "República Federativa do Brasil",0,1,'C');
			$pdf->SetXY(3.5,1.5);
			$pdf->Cell(10, 1, "TABELIONATO DE PROTESTO DE TÍTULOS DE PALMAS",0,1,'C');
			$pdf->SetXY(1,2);
			$pdf->Cell(14.8, 1, $data_cartorio->endereco . " " . $data_cartorio->complemento . " - CEP: " . $data_cartorio->cep . " - " . $data_cartorio->cidade . " - ". $data_cartorio->uf,0,0,'C');
			$pdf->SetXY(3,2.5);
			$pdf->Cell(11, 1,$this->ajustaCPF_CNPJ($data_cartorio->cnpj, 1) ,0,1,'C');
			$pdf->SetXY(3	,3);
			$pdf->Cell(11, 1,"Fone/Fax: " . $this->ajustaTelefone($data_cartorio->telefone),0,1,'C');
			$pdf->SetXY(3,3.5);
			$pdf->Cell(11, 1,"Oficial/Tabelião: " . $data_cartorio->tabeliao,0,1,'C');
			//
			$pdf->SetXY(1.5, 4.5);
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(7, 1,"Destinatário: ",0,0,'L');
			$pdf->SetX(3.3);
			$pdf->SetFont('arial', '', 8);
			$pdf->Cell(5, 1,$data_devedor->nome,0,0,'L');
			$pdf->SetX(11.7);
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(5, 1,"CPF/CNPJ: ",0,0,'L');
			$pdf->SetX(13.5);
			$pdf->SetFont('arial', '', 8);
			$pdf->Cell(5, 1,$this->ajustaCPF_CNPJ($data_devedor->numeroidentificacao, $data_devedor->tipoidentificacao ),0,0,'L');
			
			$pdf->SetFont('arial', 'B', 8);
			$pdf->SetXY(1.5, 5);
			$pdf->Cell(4.7, 1,"Endereço: ",0,0,'L');
			$pdf->SetX(3);
			$pdf->SetFont('arial', '', 8);
			$pdf->Cell(5, 1,$endereco_devedor,0,0,'L');
			//
			$pdf->SetFont('arial', '', 8);
			$pdf->SetXY(0, 6);
			$pdf->Cell(9, 1,"Recebi a notificação protocolada sob número ",0,0,'C');
			$pdf->SetX(7.4);
			$pdf->SetFont('arial', '', 8);
			$pdf->Cell(8.5, 1,$data_devedor->protocolo,0,0,'L');
			
			$pdf->SetXY(0, 6.5);
			$pdf->Cell(17.3, 1,"Nome por extenso Legível: ____________________________________________________________________",0,0,'C');
			$pdf->SetXY(0, 7);
			$pdf->Cell(14.6, 1,"Data: ____ /____ /______        Horário:____:____          CPF: _______________________",0,1,'C');
			
			//
			$pdf->SetXY(1.6, 7.9);		
			$pdf->Cell(6,2,"",1,0,'C');
			$pdf->SetFont('arial','B',6.5);
			
			$pdf->SetXY(1.8, 8);
			$pdf->Cell(0.5,0.5,"",1,0,'L');
			$pdf->SetXY(2.3, 8);
			$pdf->Cell(0.5,0.5,"Mudou-se",0,0,'L');
			
			$pdf->SetXY(1.8, 8.6);		
			$pdf->Cell(0.5,0.5,"",1,0,'L');
			$pdf->SetXY(2.3, 8.6);
			$pdf->Cell(0.5,0.5,"Desconhecido",0,0,'L');
			
			$pdf->SetXY(1.8, 9.2);		
			$pdf->Cell(0.5,0.5,"",1,0,'L');
			$pdf->SetXY(2.3, 9.2);
			$pdf->Cell(0.5,0.5,"Recusado",0,0,'L');
			
			$pdf->SetXY(4.3, 8);		
			$pdf->Cell(0.5,0.5,"",1,0,'L');
			$pdf->SetXY(4.8, 8);		
			$pdf->Cell(0.5,0.5,"Número Inexistente",0,0,'L');
			
			$pdf->SetXY(4.3, 8.6);		
			$pdf->Cell(0.5,0.5,"",1,0,'L');
			$pdf->SetXY(4.8, 8.6);		
			$pdf->Cell(0.5,0.5,"Endereço Incompleto",0,0,'L');
			
			$pdf->SetXY(4.3, 9.2);		
			$pdf->Cell(0.5,0.5,"",1,0,'L');
			$pdf->SetXY(4.8, 9.2);		
			$pdf->Cell(0.5,0.5,"Outros",0,0,'L');
			//
			$pdf->SetXY(9.7, 7.9);		
			$pdf->Cell(6,2,"",1,0,'C');
			$pdf->SetFont('arial','B',8);
			$pdf->SetXY(12, 8);
			$pdf->Cell(1,0.5,"Tentativas de Entrega",0,0,'C');
			$pdf->SetXY(12, 8.4);
			$pdf->Cell(1,0.5,"____ /____ /______  ____:____ Horas",0,0,'C');
			$pdf->SetXY(12, 8.9);
			$pdf->Cell(1,0.5,"____ /____ /______  ____:____ Horas",0,0,'C');
			$pdf->SetXY(12, 9.4);
			$pdf->Cell(1,0.5,"____ /____ /______  ____:____ Horas",0,0,'C');
			
			
			//
			$pdf->SetXY(13.55, 1);		
			$pdf->Cell(6.5,3,"",1,0,'C');
			$pdf->SetFont('arial', 'B', 20);
			$pdf->SetXY(13.3, 1);		
			$pdf->Cell(7, 1, "AR", 0, 1, 'C');
			$pdf->SetXY(13.3, 2);
			$pdf->SetFont('arial', 'B', 12);
			$pdf->Cell(7,1,"Protocolo: " . $data_devedor->protocolo,0,1,'C');
			$pdf->SetXY(13.3, 3);
			$pdf->Cell(7,1,"Data do protocolo: " . $this->getDataTimestamp($data_devedor->data_protocolo),0,1,'C');
			
			//$pdf->Ln(5);
			
			////////////
			$pdf->SetY(11);
			$pdf->SetFont('arial', 'B', 8);		
			$pdf->SetXY(1.5, 14.5);
			$pdf->Cell(4.5, 1,"Destinatário: ",0,0,'L');
			$pdf->SetX(3.3);
			$pdf->SetFont('arial', '', 8);
			$pdf->Cell(5, 1, $data_devedor->nome,0,0,'L');
					
			$pdf->SetFont('arial', 'B', 8);
			$pdf->SetXY(1.5, 15);
			$pdf->Cell(4.5, 1,"Endereço: ",0,0,'L');
			$pdf->SetX(3);
			$pdf->SetFont('arial', '', 8);
			$pdf->Cell(5, 1, $endereco_devedor ,0,0,'L');
			
			//
			$pdf->SetXY(13.5, 11);		
			$pdf->Cell(6.5, 3, "", 1, 1, 'C');
			$pdf->SetFont('arial', 'B', 20);
			$pdf->SetXY(13.3, 11);		
			$pdf->Cell(7, 1, "AR", 0, 1, 'C');
			$pdf->SetXY(13.3, 12);
			$pdf->SetFont('arial', 'B', 12);	
			$pdf->Cell(7, 1, "Protocolo: " . $data_devedor->protocolo,0,1,'C');
			$pdf->SetXY(13.3, 13);
			$pdf->Cell(7, 1, "Data do protocolo: " .$this->getDataTimestamp($data_devedor->data_protocolo),0,1,'C');
			
			/////////////
			$pdf->SetY(18.5);
			$pdf->SetFont('arial', 'B', 18);
			$pdf->Cell(19, 9, "", 1, 0, 'C');
			$pdf->SetY(22);
			$pdf->Cell(19, 1, "Horário de Atendimento",0,1,'C');
			$pdf->SetY(22.8);
			$pdf->Cell(19, 1, "ao Público",0,1,'C');
			$pdf->SetY(23.6);
			$pdf->Cell(19, 1, "08:00 às 17:00",0,1,'C');
			
			
			
			
			
			$pdf->AddPage();
			
			$pdf->SetFont('arial', 'B', 20);
			$pdf->SetXY(1,1);	
			$pdf->Cell(19, 7.5, "", 1, 0, 'R');
			$pdf->SetXY(13.5,1);
			$pdf->Cell(6.5, 3, "", 1, 1, 'C');
			$pdf->SetXY(13.3,1);
			$pdf->Cell(7, 1, "AR", 0, 1, 'C');
			$pdf->SetFont('arial', 'B', 12);
			$pdf->SetXY(13.3,2);
			$pdf->Cell(7, 1, "Protocolo: " . $data_devedor->protocolo,0,1,'C');
			$pdf->SetXY(13.3,3);
			$pdf->Cell(7, 1, "Data do protocolo: " .$this->getDataTimestamp($data_devedor->data_protocolo),0,1,'C');
			
			$pdf->SetFont('arial', '', 7);
			$pdf->SetY(9);
			$pdf->Cell(0, 0.5, "------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0, 'C');
	
			$pdf->Image('images/brasao.gif', 1, 10.3, 2.3);
			$pdf->SetFont('arial', 'B', 20);
			$pdf->SetXY(13.5,10);
			$pdf->Cell(6.5, 3, "", 1, 1, 'C');
			$pdf->SetXY(10.3,10);
			$pdf->Cell(13, 1, "INTIMAÇÃO", 0, 1, 'C');
			$pdf->SetFont('arial', 'B', 12);
			$pdf->SetXY(13.3,11.1);
			$pdf->Cell(7, 1, "Protocolo: " . $data_devedor->protocolo,0,1,'C');
			$pdf->SetXY(13.3,12);
			$pdf->Cell(7, 1, "Data do protocolo: " .$this->getDataTimestamp($data_devedor->data_protocolo),0,1,'C');
			
			$pdf->SetXY(3.5,9.7);
			$pdf->SetFont('arial', 'B', 10);
			$pdf->Cell(10, 1, "República Federativa do Brasil",0,1,'C');
			$pdf->SetXY(3.5,10.15);
			$pdf->Cell(10, 1, "TABELIONATO DE PROTESTO DE TÍTULOS DE PALMAS",0,1,'C');
			$pdf->SetXY(1,10.6);
			$pdf->Cell(14.8, 1, $data_cartorio->endereco . " " . $data_cartorio->complemento . " - CEP: " . $data_cartorio->cep . " - " . $data_cartorio->cidade . " - ". $data_cartorio->uf,0,0,'C');
			$pdf->SetXY(3,11.1);
			$pdf->Cell(11, 1, "CNPJ - ".$this->ajustaCPF_CNPJ($data_cartorio->cnpj,1),0,0,'C');
			$pdf->SetXY(3,11.6);
			$pdf->Cell(11, 1, "Fone/Fax: " . $this->ajustaTelefone($data_cartorio->telefone),0,0,'C');
			$pdf->SetXY(3,12.1);
			$pdf->Cell(11, 1, "Oficial/Tabelião: " . $data_cartorio->tabeliao,0,0,'C');
			
			$pdf->SetXY(1,13);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(12, 1, "Destinatário: " ,0,1,'L');
			$pdf->SetXY(3,13);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(15, 1, trim($data_devedor->nome) ,0,1,'L');
			
			$pdf->SetXY(1,13.5);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(10, 1, "Endereço: " ,0,1,'L');
			$pdf->SetXY(2.6,13.5);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(15, 1, trim($data_devedor->endereco) ,0,1,'L');
			
			$pdf->SetXY(1,14);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(12, 1, "Apresentante: " ,0,1,'L');
			$pdf->SetXY(3.2,14);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(15, 1, trim($data_devedor->nomesacador) ,0,1,'L');
			
			$pdf->SetXY(1,14.5);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(12, 1, "Cedente/Credor: " ,0,1,'L');
			$pdf->SetXY(3.6,14.5);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(15, 1, trim($data_devedor->nomecedente) ,0,1,'L');
			
			$pdf->SetXY(1,15);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(14, 1, "Sacador/Favorecido: " ,0,1,'L');
			$pdf->SetXY(4.2,15);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(15, 1, trim($data_devedor->nomesacador) ,0,1,'L');
			
			$pdf->SetXY(1,15.5);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(14, 1, "Número do Título: " ,0,1,'L');
			$pdf->SetXY(3.8,15.5);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(15, 1, $data_devedor->numerotitulo ,0,1,'L');
			
			$pdf->SetXY(1,16);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(12, 1, "Vencimento: " ,0,1,'L');
			$pdf->SetXY(3,16);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(15, 1, $this->getDataTimestamp($data_devedor->vencimento) ,0,1,'L');
			
			$pdf->SetXY(8,15.5);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(8, 1, "Espécie: " ,0,1,'L');
			$pdf->SetXY(9.4,15.5);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(15, 1, $data_devedor->especie ,0,1,'L');
			
			$pdf->SetXY(8,16);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(12, 1, "Valor Saldo Título: " ,0,1,'L');
			$pdf->SetXY(10.9,16);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(15, 1, "R$ " . $this->converte($data_devedor->valortitulo) ,0,1,'L');
			
			$pdf->SetXY(9.2,13);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(8, 1, "CPF/CNPJ: " ,0,1,'R');
			$pdf->SetXY(14.1,13);
			$pdf->SetFont('arial', '', 9);
			if(isset($data_devedor->tipoidentificacaosacador))
				$tipoidsacador = $data_devedor->tipoidentificacaosacador;
			else
				$tipoidsacador = 1;
			$pdf->Cell(6, 1, $this->ajustaCPF_CNPJ($data_devedor->documentosacador, $tipoidsacador) ,0,1,'R');
			
			$pdf->SetXY(12.4,15.5);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(6, 1, "Emissão: " ,0,1,'R');
			$pdf->SetXY(14,15.5);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(6, 1, date("d/m/Y") ,0,1,'R');
			
			$pdf->SetXY(10.5,16);
			$pdf->SetFont('arial', 'B', 9);
			$pdf->Cell(8, 1, "Valor das Custas: ",0,1,'R');
			$pdf->SetXY(14,16);
			$pdf->SetFont('arial', '', 9);
			$pdf->Cell(6, 1, 'R$ '.$this->converte($valor_custas) ,0,1,'R');
			
			$pdf->SetXY(1,16.5);
			$pdf->SetFont('arial',  '', 7.6);
			$pdf->Cell(20, 1, "Encontra-se nesta Serventia o título ou documento de dívida acima caracterizado. Pelo presente intimo Vossa senhoria a efetuar o pagamento por este boleto" ,0,1,'L');
			$pdf->SetY(16.9);
			$pdf->Cell(20, 1, "ou dar as razões porque não o faz, sob pena de o mesmo ser protestado na forma da legislação em vigor." ,0,1,'L');
			
			$pdf->SetXY(1,17.3);
			$pdf->SetFont('arial',  '', 7.6);
			$pdf->Cell(5, 1, "Emolumentos: R$". 	  $this->converte($data_custas[0]->emolumento).
							' - FUNCIVIL: R$'. 		  $this->converte($data_custas[2]->valor).  
							' - Taxa Judiciária: R$'. $this->converte($data_custas[4]->valor). 
							' - Processamento: R$' .  $this->converte($data_custas[0]->valor). 
							' - Intimação: R$'.		  $this->converte($data_custas[3]->valor),0,1,'L');
			
			$pdf->SetXY(1,18);
			$pdf->SetFont('arial',  'B', 10);
			$pdf->Cell(4, 0.6, "BRADESCO" ,1,1,'L');
			
			$pdf->SetXY(5,18);
			$pdf->Cell(2, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(5,17.9);
			$pdf->Cell(2, 0.6, "Espécie Doc." ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(5,18.15);
			$pdf->Cell(2, 0.6, "OU" ,0,1,'C');
			
			$pdf->SetXY(7,18);
			$pdf->Cell(5, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(7,17.9);
			$pdf->Cell(5, 0.6, "Número do Título" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(7,18.15);
			$pdf->Cell(5, 0.6, $data_devedor->numerotitulo ,0,1,'L');
			
			$pdf->SetXY(12,18);
			$pdf->Cell(2.6, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(12,17.9);
			$pdf->Cell(2.6, 0.6, "Vencimento" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(12,18.15);
			$pdf->Cell(2.6, 0.6, $this->getDataTimestamp($data_devedor->vencimento) ,0,1,'R');
			
			$pdf->SetXY(14.6,18);
			$pdf->Cell(5.1, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(14.6,17.9);
			$pdf->Cell(5.1, 0.6, "Valor a Pagar" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(14.6,18.15);
			$pdf->Cell(5.1, 0.6, $this->converte($data_devedor->valortitulo + $valor_custas) ,0,1,'R');
			
			$pdf->SetXY(1,18.6);
			$pdf->Cell(11, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(1,18.45);
			$pdf->Cell(11, 0.6, "Sacado" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(1,18.75);
			$pdf->Cell(11	, 0.6, trim($data_devedor->nomesacador) ,0,1,'L');
			
			$pdf->SetXY(12,18.6);
			$pdf->Cell(7.7, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(12,18.45);
			$pdf->Cell(7.7, 0.6, "Valor Pago - R$" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(12,18.75);
			$pdf->Cell(7.7, 0.6, $this->converte($data_devedor->valortitulo + $valor_custas) ,0,1,'R');
			
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(2,19.1);
			$pdf->Cell(7.7, 0.6, 'Via do Cliente' ,0,1,'l');
			
			$pdf->SetFont('arial', '', 7);
			$pdf->SetY(19.5);
			$pdf->Cell(0, 0.5, "------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------", 0, 0, 'C');
			
			//Boleto
			
			$pdf->SetXY(1,20.2);
			$pdf->SetFont('arial',  'B', 10);
			$pdf->Cell(4, 0.7, "BRADESCO" ,1,1,'L');
			
			$pdf->SetXY(5,20.2);
			$pdf->SetFont('arial','B',12)	;
			$pdf->Cell(1.8, 0.7, "237-2" ,1,1,'C');
			
			$pdf->SetXY(6.8,20.2);
			$pdf->SetFont('arial','B',11)	;
			$pdf->Cell(12.9, 0.7, $this->monta_linha_digitavel($linha_digitavel) .' ' .  $this->fator_vencimento($this->getDataTimestamp($data_devedor->vencimento)) . $this->fator_valor($data_devedor->valortitulo + $valor_custas) ,1,1,'C');
			
			$pdf->SetXY(1,20.9);
			$pdf->Cell(13, 0.7, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(1,20.7);
			$pdf->Cell(13, 0.7, "Local" ,0,1,'L');
			$pdf->SetFont('arial',  '', 9)	;
			$pdf->SetXY(1,21);
			$pdf->Cell(13, 0.7, "Pagável preferencialmente nas agências do Bradesco até a data do vencimento." ,0,1,'L');
			
			$pdf->SetXY(14,20.9);
			$pdf->SetFillColor(255);
			$pdf->Cell(5.7, 0.7, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(14,20.7);
			$pdf->Cell(5.7, 0.7, "Vencimento " ,0,1,'L');
			$pdf->SetFont('arial',  '', 9)	;
			$pdf->SetXY(14,21);
			$pdf->Cell(5.7, 0.7, $this->getDataTimestamp($data_devedor->vencimento) ,0,1,'R');
			
			$pdf->SetXY(1,21.6);
			$pdf->Cell(13, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(1,21.45);
			$pdf->Cell(13, 0.6, "Cedente" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8)	;
			$pdf->SetXY(1,21.75);
			$pdf->Cell(13, 0.6, $data_cartorio->nome ,0,1,'L');
			
			$pdf->SetXY(14,21.6);
			$pdf->SetFillColor(255);
			$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(14,21.45);
			$pdf->Cell(5.7, 0.6, "Agência / Código do Cedente " ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(14,21.75);
			$pdf->Cell(5.7, 0.6, $this->agencia_cedente(23973, 45004),0,1,'R');
			
			$pdf->SetXY(1,22.2);
			$pdf->Cell(3.5, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(1,22.05);
			$pdf->Cell(3.5, 0.6, "Data de Emissão" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(1,22.35);
			$pdf->Cell(3.5, 0.6, date('d/m/Y') ,0,1,'R');
			
			$pdf->SetXY(4.5,22.2);
			$pdf->Cell(3.5, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(4.5,22.05);
			$pdf->Cell(3.5, 0.6, "Número do Documento" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(4.5,22.35);
			$pdf->Cell(3.5, 0.6, $data_devedor->numerotitulo ,0,1,'R');
			
			$pdf->SetXY(8,22.2);
			$pdf->Cell(2, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(8,22.05);
			$pdf->Cell(2, 0.6, "Espécie Doc." ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(8,22.35);
			$pdf->Cell(2, 0.6, "OU" ,0,1,'C');
			
			$pdf->SetXY(10,22.2);
			$pdf->Cell(1, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(10,22.05);
			$pdf->Cell(1, 0.6, "Aceite" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(10,22.35);
			$pdf->Cell(1, 0.6, "N" ,0,1,'C');
			
			$pdf->SetXY(11,22.2);
			$pdf->Cell(3, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(11,22.05);
			$pdf->Cell(3, 0.6, "Data do Processamento" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(11,22.35);
			$pdf->Cell(3, 0.6, $this->getDataTimestamp($data_devedor->data_protocolo) ,0,1,'R');
			
			$pdf->SetXY(14,22.2);
			$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(14,22.05);
			$pdf->Cell(5.7, 0.6, "Carteira / Nosso Número" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(14,22.35);
			$pdf->Cell(5.7, 0.6, '09 / ' . $this->nossonumero($data_devedor->protocolo) ,0,1,'R');
			
			$pdf->SetXY(1,22.8);
			$pdf->Cell(2.5, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(1,22.65);
			$pdf->Cell(2.5, 0.6, "Uso do Banco" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(1,22.9);
			$pdf->Cell(2.5, 0.6, "" ,0,1,'R');
			
			$pdf->SetXY(3.5,22.8);
			$pdf->Cell(1, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(3.5,22.65);
			$pdf->Cell(1, 0.7, "CIP" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(3.5,22.9);
			$pdf->Cell(1, 0.7, "000" ,0,1,'R');
			
			$pdf->SetXY(4.5,22.8);
			$pdf->Cell(1.75, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(4.5,22.7);
			$pdf->Cell(1.75, 0.6, "Carteira" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(4.5,22.97);
			$pdf->Cell(1.75, 0.6, "09" ,0,1,'C');
			
			$pdf->SetXY(6.25,22.8);
			$pdf->Cell(1.75, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(6.25,22.65);
			$pdf->Cell(1.75, 0.6, "Moeda" ,0,1,'L');
			$pdf->SetFont('arial',  '', 8.5)	;
			$pdf->SetXY(6.25,22.9);
			$pdf->Cell(1.75, 0.6, "R$" ,0,1,'C');
			
			$pdf->SetXY(8,22.8);
			$pdf->Cell(3, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(8,22.65);
			$pdf->Cell(3, 0.6, "Quantidade" ,0,1,'L');
			$pdf->SetFont('arial',  '', 9)	;
			$pdf->SetXY(8,22.9);
			$pdf->Cell(3, 0.6, "" ,0,1,'C');
			
			$pdf->SetXY(11,22.8);
			$pdf->Cell(3, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(11,22.65);
			$pdf->Cell(3, 0.6, "Valor" ,0,1,'L');
			$pdf->SetFont('arial',  '', 9)	;
			$pdf->SetXY(11,22.9);
			$pdf->Cell(3, 0.6, "" ,0,1,'C');
			
			$pdf->SetXY(14,22.8);
			$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(14,22.65);
			$pdf->Cell(5.7, 0.6, "Valor do Documento" ,0,1,'L');
			$pdf->SetFont('arial',  '', 9)	;
			$pdf->SetXY(14,22.9);
			$pdf->Cell(5.7, 0.6, $this->converte($data_devedor->valortitulo + $valor_custas) ,0,1,'R');
			
			$pdf->SetXY(1,23.4);
			$pdf->Cell(13, 3, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(1,23.3);
			$pdf->Cell(2, 0.6, "Instruções" ,0,1,'L');
			$pdf->SetXY(1,23.6);
			$pdf->Cell(2, 0.6, "Texto de responsabilidade do Cliente" ,0,1,'L');
			$pdf->SetXY(1,24);
			$pdf->SetFont('arial',  '', 8);
			$pdf->Cell(2, 0.6, "SENHOR CAIXA: " ,0,1,'L');
			$pdf->SetXY(3.3,24);
			$pdf->SetFont('arial',  'BU', 8);
			$pdf->Cell(2, 0.6, "NÃO RECEBER PAGAMENTO EM CHEQUE " ,0,1,'L');
			$pdf->SetXY(3.3,24.4);
			$pdf->SetFont('arial',  'BU', 8);
			$pdf->Cell(2, 0.6, "NÃO RECEBER APÓS O VENCIMENTO " ,0,1,'L');
			$pdf->SetFont('arial',  '', 7.5);
			$pdf->SetXY(1,24.8);
			$pdf->Cell(2, 0.6, "Este boleto, devidamentee autenticado pelo banco, possui prova da quitação do título do documento de" ,0,1,'L');
			$pdf->SetXY(1,25.1);
			$pdf->Cell(2, 0.6, "dívida a que se refere. Pagável em qualquer banco até a data do vencimento, após isso perderá a validade." ,0,1,'L');
			$pdf->SetXY(1,25.6);
			$pdf->Cell(2, 0.6, 'Vl. Saldo título: R$'. 	$this->converte($data_devedor->valortitulo)
								.' - Emolumentos: R$'. 		$this->converte($data_custas[0]->emolumento)
								.' - FUNCIVIL: R$'. 		$this->converte($data_custas[2]->valor)
								.' - Taxa Judiciária: R$'. 	$this->converte($data_custas[4]->valor) .' -' ,0,1,'L');
			$pdf->SetXY(1,25.9);
			$pdf->Cell(2, 0.6, 'Processamento: R$'. 	$this->converte($data_custas[0]->valor)
							.' - Intimação: R$' .		$this->converte($data_custas[3]->valor) ,0,1,'L');
			
			$pdf->SetXY(14,23.4);
			$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(14,23.25);
			$pdf->Cell(5.7, 0.7, "( - ) Desconto" ,0,1,'L');
			
			$pdf->SetXY(14,24);
			$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(14,23.9);
			$pdf->Cell(5.7, 0.6, "( - ) Outras Deduções" ,0,1,'L');
			
			$pdf->SetXY(14,24.6);
			$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(14,24.5);
			$pdf->Cell(5.7, 0.6, "( + ) Mora/Multa" ,0,1,'L');
			
			$pdf->SetXY(14,25.2);
			$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(14,25.1);
			$pdf->Cell(5.7, 0.6, "( + ) Outros Acréscimos" ,0,1,'L');
			
			$pdf->SetXY(14,25.8);
			$pdf->Cell(5.7, 0.6, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(14,25.7);
			$pdf->Cell(5.7, 0.6, "( = ) Valor Cobrado" ,0,1,'L');
			
			$pdf->SetXY(1,26.4);
			$pdf->Cell(18.7, 1.2, "" ,1,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(1,26.3);
			$pdf->Cell(5.7, 0.6, "Sacado" ,0,1,'L');
			$pdf->SetFont('arial',  'B', 8)	;
			$pdf->SetXY(2,26.3);
			$pdf->Cell(5.7, 0.6, trim($data_devedor->nome) ,0,1,'L');
			$pdf->SetFont('arial',  '', 8)	;
			$pdf->SetXY(2,26.7);
			$pdf->Cell(5.7, 0.6, trim($data_devedor->endereco) ,0,1,'L');
			$pdf->SetFont('arial',  '', 7)	;
			$pdf->SetXY(1,27.05);
			$pdf->Cell(5.7, 0.6, "Sacado/Avalista" ,0,1,'L');
			
			$barcode = $this->remove_char($this->monta_linha_digitavel($linha_digitavel).$this->fator_vencimento($this->getDataTimestamp($data_devedor->vencimento)).$this->fator_valor($data_devedor->valortitulo + $valor_custas));
			
			$path = APPLICATION_PATH . '/arquivos/barcode';
			
			if(!file_exists($path))mkdir($path);
			
			$path .= "/barcode";
			
			$config = new Zend_Config(array(
									'barcode'        => 'Code25interleaved',
									'barcodeParams'  => array(	'text' 		=> $barcode, 'drawText'	=> FALSE, 'barThickWidth' => 2),
									'renderer'       => 'image',
									'rendererParams' => array('imageType' => 'jpg'),
			));
			
			$renderer = Zend_Barcode::factory($config)->draw();
			
			imagejpeg($renderer, $path . $data_devedor->idProtesto.'.jpg', 100); 
	
			$image = Zend_Pdf_Image::imageWithPath($path . $data_devedor->idProtesto.'.jpg');
			
			imagedestroy($renderer); 
			
			$pdf->Image($path . $data_devedor->idProtesto . '.jpg', 0.75, 27.8, 10);
		}

		$data['arquivo'] = "B" . date('dmY_his') . ".pdf";
    	
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
		$pdf->Output("B" .date('dmY_his') . ".pdf", "D"); exit;
		
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
	
	function ajustaCPF_CNPJ($data, $tipo){
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
		
	//Funções do boleto
	public function remove_char($dado){
		$bar_code_aux = str_split($dado);
		for($i = 0; $i <= count($bar_code_aux); $i++){
			if(array_search('.',$bar_code_aux) == TRUE) unset($bar_code_aux[array_search('.',$bar_code_aux)]);
			if(array_search(' ',$bar_code_aux) == TRUE) unset($bar_code_aux[array_search(' ',$bar_code_aux)]);
		}
		return implode($bar_code_aux);
	}
	
	public function nossonumero($data){
		$j = str_split($data);	
		$k = 12 - count($j);
		for($i = 0; $i <= $k; $i++) $nosso[] = 0;
		for($i = 0; $i < count($j); $i++) $nosso[] = $j[$i];
		$digito = array_pop($nosso);
		$nosso[] = '-';
		array_push($nosso, $digito);
		return(implode($nosso));
	}
	
	public function _numero($data, $tamanho){
		$j = str_split($data);	
		$k = $tamanho - count($j);
		for($i = 0; $i <= $k; $i++) $nosso[] = 0;
		return(implode($nosso));
	}
	
	public function agencia_cedente($conta, $agencia){
		//conta
		$temp_conta = str_split($conta);
		$digito = array_pop($temp_conta);
		$temp_conta[] = '-';
		array_push($temp_conta, $digito);
		//agencia
		$j = str_split($agencia);	
		$k = 8 - count($j);
		for($i = 0; $i <= $k; $i++) $temp_agencia[] = 0;
		for($i = 0; $i < count($j); $i++) $temp_agencia[] = $j[$i];
		$digito = array_pop($temp_agencia);
		$temp_agencia[] = '-';
		array_push($temp_agencia, $digito);
		return ( implode($temp_conta) . ' / ' . implode($temp_agencia) );
	}
	
	public function fator_valor($valor){
		$temp_valor = str_split($valor);
		if(array_search('.',$temp_valor) == TRUE) unset($temp_valor[array_search('.',$temp_valor)]);
		if(array_search(',',$temp_valor) == TRUE) unset($temp_valor[array_search(',',$temp_valor)]);
		$k = 10 - count($temp_valor);
		for($i = 0; $i <= $k; $i++) $temp[] = 0;
		return (implode($temp) . implode($temp_valor));	
	}
	
	public function fator_valor_digitavel($valor){
		$temp_valor = str_split($valor);
		if(array_search('.',$temp_valor) == TRUE) unset($temp_valor[array_search('.',$temp_valor)]);
		if(array_search(',',$temp_valor) == TRUE) unset($temp_valor[array_search(',',$temp_valor)]);
		return (implode($temp_valor));	
	}
	
	public function fator_vencimento($data) {
		$data = explode("/",$data);
		$ano = $data[2];
		$mes = $data[1];
		$dia = $data[0];
		return(abs(($this->_dateToDays("1997","10","07")) - ($this->_dateToDays($ano, $mes, $dia))));
	}

	public 	function _dateToDays($year,$month,$day) {
		$century = substr($year, 0, 2);
		$year = substr($year, 2, 2);
		if ($month > 2) {
			$month -= 3;
		} else {
			$month += 9;
			if ($year) {
				$year--;
			} else {
				$year = 99;
				$century --;
			}
		}
		return ( floor((  146097 * $century)    /  4 ) + floor(( 1461 * $year)        /  4 ) + floor(( 153 * $month +  2) /  5 ) + $day +  1721119);
	}
	
	public function monta_linha_digitavel($codigo) {

	// 01-03    -> Código do banco sem o digito
	// 04-04    -> Código da Moeda (9-Real)
	// 05-05    -> Dígito verificador do código de barras
	// 06-09    -> Fator de vencimento
	// 10-19    -> Valor Nominal do Título
	// 20-44    -> Campo Livre (Abaixo)
	
	// 20-23    -> Código da Agencia (sem dígito)
	// 24-05    -> Número da Carteira
	// 26-36    -> Nosso Número (sem dígito)
	// 37-43    -> Conta do Cedente (sem dígito)
	// 44-44    -> Zero (Fixo)
        

        // 1. Campo - composto pelo código do banco, código da moéda, as cinco primeiras posições
        // do campo livre e DV (modulo10) deste campo
        
        $p1 = substr($codigo, 0, 4);							// Numero do banco + Carteira
        $p2 = substr($codigo, 19, 5);						// 5 primeiras posições do campo livre
        $p3 = $this->modulo_10("$p1$p2");						// Digito do campo 1
        $p4 = "$p1$p2$p3";								// União
        $campo1 = substr($p4, 0, 5).'.'.substr($p4, 5);

        // 2. Campo - composto pelas posiçoes 6 a 15 do campo livre
        // e livre e DV (modulo10) deste campo
        $p1 = substr($codigo, 24, 10);						//Posições de 6 a 15 do campo livre
        $p2 = $this->modulo_10($p1);								//Digito do campo 2	
        $p3 = "$p1$p2";
        $campo2 = substr($p3, 0, 5).'.'.substr($p3, 5);

        // 3. Campo composto pelas posicoes 16 a 25 do campo livre
        // e livre e DV (modulo10) deste campo
        $p1 = substr($codigo, 34, 10);						//Posições de 16 a 25 do campo livre
        $p2 = $this->modulo_10($p1);								//Digito do Campo 3
        $p3 = "$p1$p2";
        $campo3 = substr($p3, 0, 5).'.'.substr($p3, 5);

        // 4. Campo - digito verificador do codigo de barras
        $campo4 = substr($codigo, 4, 1);

        // 5. Campo composto pelo fator vencimento e valor nominal do documento, sem
        // indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
        // tratar de valor zerado, a representacao deve ser 000 (tres zeros).
		$p1 = substr($codigo, 5, 4);
		$p2 = substr($codigo, 9, 10);
		$campo5 = "$p1$p2";

        return "$campo1 $campo2 $campo3 $campo4 $campo5"; 
	}
	
	public function digitoVerificador_barra($numero){
		$resto2 = $this->modulo_11($numero, 9, 1);
		if ($resto2 == 0 || $resto2 == 1 || $resto2 == 10){
			$dv = 1;
		}else{
			$dv = 11 - $resto2;
		}
		return $dv;
	}
	
	public function modulo_10($num) { 
		$numtotal10 = 0;
        $fator = 2;

        // Separacao dos numeros
        for ($i = strlen($num); $i > 0; $i--) {
            // pega cada numero isoladamente
            $numeros[$i] = substr($num,$i-1,1);
            // Efetua multiplicacao do numero pelo (falor 10)
            // 2002-07-07 01:33:34 Macete para adequar ao Mod10 do Itaú
            $temp = $numeros[$i] * $fator; 
            $temp0=0;
            foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v){ $temp0+=$v; }
            $parcial10[$i] = $temp0; //$numeros[$i] * $fator;
            // monta sequencia para soma dos digitos no (modulo 10)
            $numtotal10 += $parcial10[$i];
            if ($fator == 2) {
                $fator = 1;
            } else {
                $fator = 2; // intercala fator de multiplicacao (modulo 10)
            }
        }
		
        // várias linhas removidas, vide função original
        // Calculo do modulo 10
        $resto = $numtotal10 % 10;
        $digito = 10 - $resto;
        if ($resto == 0) {
            $digito = 0;
        }
		
        return $digito;
		
	}

	public function modulo_11($num, $base=9, $r=0)  {
		/**
		 *   Autor:
		 *           Pablo Costa <pablo@users.sourceforge.net>
		 *
		 *   Função:
		 *    Calculo do Modulo 11 para geracao do digito verificador 
		 *    de boletos bancarios conforme documentos obtidos 
		 *    da Febraban - www.febraban.org.br 
		 *
		 *   Entrada:
		 *     $num: string numérica para a qual se deseja calcularo digito verificador;
		 *     $base: valor maximo de multiplicacao [2-$base]
		 *     $r: quando especificado um devolve somente o resto
		 *
		 *   Saída:
		 *     Retorna o Digito verificador.
		 *
		 *   Observações:
		 *     - Script desenvolvido sem nenhum reaproveitamento de código pré existente.
		 *     - Assume-se que a verificação do formato das variáveis de entrada é feita antes da execução deste script.
		 */                                        

		$soma = 0;
		$fator = 2;

		/* Separacao dos numeros */
		for ($i = strlen($num); $i > 0; $i--) {
			// pega cada numero isoladamente
			$numeros[$i] = substr($num,$i-1,1);
			// Efetua multiplicacao do numero pelo falor
			$parcial[$i] = $numeros[$i] * $fator;
			// Soma dos digitos
			$soma += $parcial[$i];
			if ($fator == $base) {
				// restaura fator de multiplicacao para 2 
				$fator = 1;
			}
			$fator++;
		}

		/* Calculo do modulo 11 */
		if ($r == 0) {
			$soma *= 10;
			$digito = $soma % 11;
			if ($digito == 10) {
				$digito = 0;
			}
			return $digito;
		} elseif ($r == 1){
			$resto = $soma % 11;
			return $resto;
		}
	}
}