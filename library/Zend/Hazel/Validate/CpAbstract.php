<?php 

/**
 * Validador para Cadastro de Pessoas
 *
 * Implementa��o de algoritmos para cadastro de pessoas f�sicas e jur�dicas
 * conforme Minist�rio da Fazenda do Governo Federal.
 *
 * @category Hazel
 * @package  Hazel_Validator
 * @author   Wanderson Henrique Camargo Rosa
 */
abstract class Hazel_Validate_CpAbstract extends Zend_Validate_Abstract
{
    /**
     * Tamanho Inv�lido
     * @var string
     */
    const SIZE = 'size';
 
    /**
     * N�meros Expandidos
     * @var string
     */
    const EXPANDED = 'expanded';
 
    /**
     * D�gito Verificador
     * @var string
     */
    const DIGIT = 'digit';
 
    /**
     * Tamanho do Campo
     * @var int
     */
    protected $_size = 0;
 
    /**
     * Modelos de Mensagens
     * @var string
     */
    protected $_messageTemplates = array(
        //self::SIZE     => "'%value%' n�o possui tamanho esperado",
        self::EXPANDED => "'%value%' n�o possui um formato aceit�vel",
        self::DIGIT    => "'%value%' n�o � um documento v�lido"
    );
 
    /**
     * Modificadores de D�gitos
     * @var array
     */
    protected $_modifiers = array();
 
    /**
    * Valida��o Interna do Documento
    * @param string $value Dados para Valida��o
    * @return boolean Confirma��o de Documento V�lido
    */
    protected function _check($value)
    {
        // Captura dos Modificadores 
        foreach ($this->_modifiers as $modifier) {
            $result = 0; // Resultado Inicial
            $size = count($modifier); // Tamanho dos Modificadores
            for ($i = 0; $i < $size; $i++) {
                $result += $value[$i] * $modifier[$i]; // Somat�rio
            }
            $result = $result % 11;
            $digit  = ($result < 2 ? 0 : 11 - $result); // D�gito
            // Verifica��o
            if ($value[$size] != $digit) {
                return false;
            }
        }
        
        return true;
    }
 
    public function isValid($value)
    {
        // Filtro de Dados
        $data = preg_replace('/[^0-9]/', '', $value);
        // Verifica��o de Tamanho
        if (strlen($data) != $this->_size) {
            $this->_error(self::SIZE, $value);
            return false;
        }
        // Verifica��o de D�gitos Expandidos
        if (str_repeat($data[0], $this->_size) == $data) {
            $this->_error(self::EXPANDED, $value);
            return false;
        }
        // Verifica��o de D�gitos
        if (!$this->_check($data)) {
            $this->_error(self::DIGIT, $value);
            return false;
        }
        // Compara��es Conclu�das
        return true; // Todas Verifica��es Executadas
    }
 
}

?>