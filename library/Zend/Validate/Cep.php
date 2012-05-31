<?php 

//require_once 'Zend/Validate/Abstract.php';

class Validate_Cep extends Zend_Validate_Abstract
{
	const CEP = 'Cep';
	
	//const FAIXA = 'Faixa';

	protected $_messageTemplates = array(
		self::CEP => "'%value%' não é um CEP válido"
		//self::FAIXA => "'%value%' este CEP não pertence a esta praça"
	);

	public function isValid($value)
	{
		$this->_setValue($value);

		if (!preg_match('/^[0-9]{2}.[0-9]{3}-[0-9]{3}$/', $value) || !preg_match('/^[0-9]{5}-[0-9]{3}$/', $value)) {
			$this->_error(self::CEP, $value);
			return false;
		}		

		return true;
	}
}

?>