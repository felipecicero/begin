<?php 

class Hazel_Validate_Or extends Zend_Validate
{
    // Validaчуo Sobrescrita
    public function isValid($value)
    {
        // Mensagens de Erro
        $this->_messages = array();
        // Constantes de Erro
        $this->_errors = array();
        // Resultado Inicial
        $result = false;
        // Todos os Validadores
        foreach ($this->_validators as $element) {            
        	$validator = $element['instance'];
            // Verificaчуo
            $valid = $validator->isValid($value);
            $result = $result || $valid;
            // Mensagens Informadas pelo Validador
            $messages = $validator->getMessages();
            // Mesclagem de Erros
            $this->_messages = array_merge($this->_messages, $messages);
            $this->_errors   = array_merge($this->_errors,   array_keys($messages));
            // Parar quando Validaчуo Invсlida
            if (!$result && $element['breakChainOnFailure']) {
                break;
            }            
        }
        return $result;
    }
}

?>