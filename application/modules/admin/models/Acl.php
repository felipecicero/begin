<?php

class Acl //extends Zend_Acl
{
	private $model_permissao = null;
	private $model_recurso = null;
	private $model_papel = null;	
	private $model_usuario = null;
	private $idPapel = null;
	
 	public function __construct() {
 		$this->model_usuario = new Usuario();
 		$session_usuario = new Zend_Session_Namespace('user_data');
 		
 		$user = $this->model_usuario->getUsuario($session_usuario->user->idUsuario); 		
 		$this->idPapel = $user->idPapel;	
	
 		$this->model_permissao = new Permissao();
 		$this->model_recurso = new Recurso();
		$this->model_papel = new Papel();				
	}
		
	public function isAllowed($recurso){
		
		if(null === $this->idPapel || null === $recurso){
			return false;
		}
		
		//Verifica se há o papel/recurso(view), se nao houver libera o acesso
		if(!$role = $this->searchRole($this->idPapel))
			return false;
		
		if($role->papel == 'admin')// caso seja administrador
			return true;
		
		if(!$idRecurso = $this->searchResources($recurso))	
			return false;

		
		$permissoes = $this->model_permissao->getPermissoes($this->idPapel, $idRecurso );
			
		if(count($permissoes)>0){
			if($permissoes[0]->permissao == 'allow'){
				return true;
			}
		}
		
		return false;
		
	}

	public function searchRole($idPapel){		
		$role = $this->model_papel->getPapeis($idPapel)->Current();
		
		if(count($role) > 0){			
			return $role;
		}
		
		return false;
	}
	
	public function searchResources($recurso){
		$resource = $this->model_recurso->getRecursos($recurso)->Current();
		
		if(count($resource) > 0){
			return $resource->idRecurso;
		}
		
		return false;
	}
		

}