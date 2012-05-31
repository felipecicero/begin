<?php 

//require_once 'Zend/Validate/Abstract.php';

class Validate_DateHJ extends Zend_Validate_Abstract
{
	const DATED = 'dated';

	protected $_messageTemplates = array(
		self::DATED => "Data Inválida."
	);

	public function isValid($value)
	{
		$date_hj = new Zend_Date(date('d/m/Y'));
    	$date_hj = $date_hj->get( Zend_Date::W3C );
    	
    	$_data = new Zend_Date( $value ); 
		$value = $_data->get( Zend_Date::W3C );
    	    	
		$this->_setValue($value);

		if(strtotime($date_hj) < strtotime($value)){		
			$this->_error(self::DATED);
			return false;
		}

		return true;
	}
}

?>