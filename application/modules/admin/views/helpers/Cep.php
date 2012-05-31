<?php
/**
 * Formatação de Datas
 * Auxiliar da Camada de Visualização
 * @author Wanderson Henrique Camargo Rosa
 * @see APPLICATION_PATH/views/helpers/Date.php
 */
class Zend_View_Helper_Cep extends Zend_View_Helper_Abstract
{
 	 
	    function cep($data){
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
}