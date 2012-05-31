<?php
/**
 * Formata��o de Datas
 * Auxiliar da Camada de Visualiza��o
 * @author Wanderson Henrique Camargo Rosa
 * @see APPLICATION_PATH/views/helpers/Date.php
 */
class Zend_View_Helper_Date extends Zend_View_Helper_Abstract
{
 
	    protected static $_date = null;
	 
	    /**
	     * M�todo Principal
	     * @param string $value Valor para Formata��o
	     * @param string $format Formato de Sa�da
	     * @return string Valor Formatado
	     */
	    public function date($value, $format = Zend_Date::DATETIME_MEDIUM)
	    {
	    	//$locale = new Zend_Locale('de_AT');
			//$date = new Zend_Date(1234567890, false, $locale);
	        $date = $this->getDate();
	        return $date->set($value)->get($format);
	    }
	 
	    /**
	     * Acesso ao Manipulador de Datas
	     * @return Zend_Date
	     */
	    public function getDate()
	    {
	        if (self::$_date == null) {
	            self::$_date = new Zend_Date();
	        }
	        return self::$_date;
	    }
}