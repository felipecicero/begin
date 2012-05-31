<?php

/**
 * @author Flavio
 * classe para imprimir as custas de um título.
 * Utilizada especialmente na impressão de tooltips
 */

class Zend_View_Helper_Custas extends Zend_View_Helper_Abstract
{
 
	    
	    public function Custas($idProtesto, $valor){
    	
	    	$model_custa = new Custa();
	    	
	    	$custas = $model_custa->getCustasPT($idProtesto, $valor);
	    	
	    	foreach($custas as $custa){ 
	    		echo $custa->nome . " R$ " . $this->valor($custa->valor) . "; "; 
	    	} 
	    	echo " EMOLUMENTO R$ " . $this->valor($custa->emolumento);
	    	
    		//return $model_custa->getCustasPT($idProtesto, $valor);
    		
	    }
	    
	public function valor($dado){			
			if ($dado != ""){	
				return number_format($dado, 2, ",", ".");	
			} 
			else {	
				return "0,00";	
			}	
		}
	 
}