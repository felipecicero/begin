<?php

class Zend_Controller_Action_Helper_Util extends Zend_Controller_Action_Helper_Abstract
{
	public function valor($dado){			
		if ($dado != ""){	
			return number_format($dado, 2, ",", ".");	
		} 
		else {	
			return "0,00";	
		}	
	}
		
	public function _pvar($var)
    {
		echo("<pre>");
		print_r($var);
		echo("</pre>");
    }
    
	public function converteData($data, $separador)
    {
		
		$dia = substr($data, 0, 2);
		
		$mes = substr($data, 2, 2);
		
		$ano = substr($data, 4, 4);
		
		$date = $ano. $separador . $mes . $separador . $dia;
		
		return $date;
    }

    public function decimais($valor)
    {
		$dados = str_split(floatval($valor));
		$_1 = array_pop($dados);
		$_2 = array_pop($dados);
		$dados[] = '.'.$_2.$_1;
		return floatval(implode($dados));
    }
    
    //Completa com 0 na frente, ou qualquer outro caractere atras
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
	
	//Converte um formato de data YYYY-MM-DD para DDMMYYYY que é utilizado nos arquivo
	public function converteDataArquivo($data)
    {
    	$data = substr($data, 0, 10);
		
		$data = implode("", array_reverse(explode("-", $data)));
		
		return $data;
    }
    
	public function getDataTimestamp($date){
		$date = substr($date, 0, 10);
		
		$date =implode("/", array_reverse(explode("-", $date )));
		
		return $date;
	}

	public function ajustaCEP($data){
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
	
	public function ajustaCPF_CNPJ($data, $tipo){
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

	public function ajustaTelefone($data){
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
	
}