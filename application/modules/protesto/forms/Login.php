<?php
 //Protesto_Form_Login
class Protesto_Form_Login extends Zend_Form
{
    public function init()
    {
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
		
        $this->setName('login');
 
        $login = new Zend_Form_Element_Text('login');
		$login -> clearDecorators();
		$login -> addDecorators($decorator_default);
        $login->setLabel('Login:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty');
 
        $senha = new Zend_Form_Element_Password('senha');
		$senha -> clearDecorators();
		$senha -> addDecorators($decorator_default);
        $senha->setLabel('Senha:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty');
 
        $submit = new Zend_Form_Element_Submit('Entrar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($login, $senha, $submit));
    }
}

?>