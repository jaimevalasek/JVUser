<?php

namespace JVUser\Service;

use JVBase\Service\AbstractService;

class Usuario extends AbstractService
{
	protected $entityMapper = 'user_mapper_usuarios';
	
	public function insert($data) 
	{
		$token = $this->getServiceLocator()->get('jvbase_filter_token');
		$basedate = $this->getServiceLocator()->get('jvbase_filter_basedate');
		
		$data['status_usuario'] = false;
		$data['dta_inc_usuario'] = $basedate->dbNow();
		$data['token_usuario'] = md5($token->microtimeToken());
		$data['papel_usuario'] = 'visitante';
		$data['bloqueado_usuario'] = 0;
		$data['senha_usuario'] = md5($data['senha_usuario']);
		
		if (parent::insert($data)) {
			return true;
		}
	}
	
	public function activate($data, $where)
	{
		$modelUsuario = $this->findOneBy($where);
		if (!$modelUsuario) {
			return false;
		}
		
		$token = $this->getServiceLocator()->get('jvbase_filter_token');
		$data['token_usuario'] = md5($token->microtimeToken());
		
		$data['status_usuario'] = true;
		
		if (parent::update($data, array('pk_usuario' => $modelUsuario['pk_usuario']), null, null, false)) {
			return true;
		}
	}
	
	public function getEndereco(array $where)
	{
		return $this->getEntityMapper()->getEndereco($where);
	}
	
	public function getAcl()
	{
	    return $this->getEntityMapper()->getAcl();
	}
}