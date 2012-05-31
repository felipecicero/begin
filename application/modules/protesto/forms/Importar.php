<?php
class Protesto_Form_Importar extends Zend_Form
{
    public function init(){
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
		
    	$tipo = new Zend_Form_Element_Radio('tipo');
		$tipo -> clearDecorators();
		$tipo -> addDecorators($decorator_default);
        $tipo -> setLabel('Tipo do Arquivo:')        	 
              ->addMultiOptions(
                array(
                    '1' => '(B) Remessa',
                    '2' => '(DP) Devoluчуo',
                	'3' => '(CP) Cancelamento',                	
                )
            )
            ->setValue('1');
            
        $arquivo = new Zend_Form_Element_File('arquivo');
		$arquivo->removeDecorator('HtmlTag');
        $arquivo->setRequired(true);
        //$arquivo->setLabel('Selecione o arquivo');
        
        $submit = new Zend_Form_Element_Submit('Enviar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($tipo, $arquivo, $submit));
    }
}
?>