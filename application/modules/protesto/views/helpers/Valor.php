<?php
/**
 * Formata��o de valores monet�rios no formato do real
 * Auxiliar da Camada de Visualiza��o
 * @author Wanderson Henrique Camargo Rosa
 * @see APPLICATION_PATH/views/helpers/Date.php
 */
class Zend_View_Helper_Valor extends Zend_View_Helper_Abstract
{
	    public function valor($dado){			
			if ($dado != ""){	
				return number_format($dado, 2, ",", ".");	
			} 
			else {	
				return "0,00";	
			}	
		}
}