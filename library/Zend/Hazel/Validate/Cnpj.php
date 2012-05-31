<?php 

/**
 * Validador para Cadastro de Pessoa Jur�dica
 *
 * @author   Wanderson Henrique Camargo Rosa
 * @category Hazel
 * @package  Hazel_Validator
 */
class Hazel_Validate_Cnpj extends Hazel_Validate_CpAbstract
{
    /**
     * Tamanho do Campo
     * @var int
     */
    protected $_size = 14;
 
    /**
     * Modificadores de D�gitos
     * @var array
     */
    protected $_modifiers = array(
        array(5,4,3,2,9,8,7,6,5,4,3,2),
        array(6,5,4,3,2,9,8,7,6,5,4,3,2)
    );
}

?>