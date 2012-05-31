<?php
/**
 * Formatação de Documento CPF 2 ou CNPJ 1
 * Auxiliar da Camada de Visualização
 * @author Wanderson Henrique Camargo Rosa
 * @see APPLICATION_PATH/views/helpers/Date.php
 */
class Zend_View_Helper_Documento extends Zend_View_Helper_Abstract
{
 
	    protected static $_date = null;
	 
	    /**
	     * Método Principal
	     * @param string $value Valor para Formatação
	     * @param string $format Formato de Saída
	     * @return string Valor Formatado
	     */
	    public function documento($data, $tipo=1){
			$dados = str_split($data);
			$aux = '';
			$tipo = (int)$tipo;		
			if($tipo == 1){//CNPJ
				for($i = 0; $i < count($dados); $i++){
					
					if($i==2 || $i==5  ){
						$aux = $aux . ".";
					}
					if($i==8){
						$aux = $aux . "/";
					}
					if($i==12){
						$aux = $aux . "-";
					}
		
					$aux = $aux . $dados[$i];
				}
			}
			
			if($tipo == 2){//CPF
				for($i = 0; $i < count($dados); $i++){
					
					if($i==3 || $i==6  ){
						$aux = $aux . ".";
					}				
					if($i==9){
						$aux = $aux . "-";
					}
		
					$aux = $aux . $dados[$i];
				}
			}
			
			return $aux;
		}
}