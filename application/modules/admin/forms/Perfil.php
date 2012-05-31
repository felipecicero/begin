<?php

class Admin_Form_Perfil extends Zend_Form
{

	public function init(){
		$this->setDecorators(array( 'FormElements', 'Form')); 
		$decorator_default = array('ViewHelper','Errors','Description','HtmlTag','Label',array(array('row' => 'HtmlTag'),array('tag' => 'div', 'class' => 'field')));
		
        //$this->setName('login');
        $idPerfil = new Zend_Form_Element_Hidden('idPapel');
		$idPerfil -> clearDecorators();
		$idPerfil -> addDecorators($decorator_default);
 
        $nome = new Zend_Form_Element_Text('papel');
		$nome -> clearDecorators();
		$nome -> addDecorators($decorator_default);
        $nome -> setLabel('Nome do Perfil:')
                	-> setRequired(true);
              
        $descricao = new Zend_Form_Element_Text('descricao');
		$descricao -> clearDecorators();
		$descricao -> addDecorators($decorator_default);
        $descricao -> setLabel('Descrição:');
               	
 		$model_recurso = new Recurso();
 		$pais = $model_recurso->getRecursosPai();
 		
 		foreach($pais as $pai){
 			$recursos[$pai->idRecurso] = new Zend_Form_Element_MultiCheckbox($pai->idRecurso);
 			$recursos[$pai->idRecurso] -> setLabel($pai->descricao);
 			
 			$filhos = $model_recurso->getRecursosFilho($pai->idRecurso);
	 		foreach ($filhos as $filho) {
		    	$recursos[$pai->idRecurso]->addMultiOption($filho->idRecurso, $filho->descricao);
		    	//$recursos[$pai->idRecurso]->setAttrib('checked', 'checked');
			}
 		}
 		
        $submit = new Zend_Form_Element_Submit('Salvar');
        $submit -> setAttrib('id', 'submitbutton');
		$submit -> clearDecorators();
		$submit -> setDecorators(array('ViewHelper'));
 
        $this->addElements(array($idPerfil, $nome, $descricao));        
        foreach($recursos as $t){
        	$this->addElements(array($t));
        }        
        $this->addElements(array($submit));
                
        /**Agrupando as permissões*/
        foreach($pais as $pai){
        	$array_recursos[] = $pai->idRecurso;
        }          
        $this->addDisplayGroup($array_recursos, 'resources',array('legend' => 'Permissões de Acesso'));
        $recursos = $this->getDisplayGroup('resources');
		$recursos->setDecorators(array('FormElements','Fieldset',array('HtmlTag',array('tag'=>'div'))));
		
		$this->addDisplayGroup(array('Salvar'),'submit');
        $submit = $this->getDisplayGroup('submit');
		$submit->setDecorators(array('FormElements','Fieldset',array('HtmlTag',array('tag'=>'div'))));
        
        
        
    }
    
	public function setAsEditForm(Zend_Db_Table_Row $row, $permissoes){
        $this->populate($row->toArray());
        $this->setAction(sprintf('perfil/perfis/idPapel/%d', $row->idPapel));

        $recursos = array();
        foreach($permissoes as $permissao){
        	$recursos[] = $permissao->idRecurso;
        }
        
        $model_recurso = new Recurso();
 		$pais = $model_recurso->getRecursosPai();        
		foreach($pais as $pai){
        	$this->populate(array($pai->idRecurso => $recursos));
        }
        
		$this->getElement('idPapel');
        $this->getElement('papel');
        $this->getElement('descricao');
        $this->getElement('recursos');
            

        return $this;
    }	


}

