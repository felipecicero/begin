<?php
/**
 * Formatação de valores monetários no formato do real
 * Auxiliar da Camada de Visualização
 * @author Wanderson Henrique Camargo Rosa
 * @see APPLICATION_PATH/views/helpers/Date.php
 */
class Zend_View_Helper_Pvar extends Zend_View_Helper_Abstract
{
	
	public function pvar($var){
		echo("<pre>");
		print_r($var);
		echo("</pre>");
    }
}