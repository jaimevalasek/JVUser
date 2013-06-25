<?php

namespace JVUser\Service;

use Zend\Authentication\AuthenticationService,
	Zend\Authentication\Adapter\DbTable as AuthAdapter,
	Zend\Authentication\Storage as AuthSession;;

class Auth extends Usuario
{
	public function authenticate($params) 
	{
		if (!isset($params['login']) || !isset($params['senha'])) 
		{
			throw new \Exception('Não foram passados o login ou senha');
		}
		
		$login = $params['login'];
		$senha = md5($params['senha']);
		$auth = new AuthenticationService();
		
		$authAdapter = new AuthAdapter($this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
		$authAdapter->setTableName('tbl_usuarios')
			->setIdentityColumn('email_usuario')
			->setCredentialColumn('senha_usuario')
			->setIdentity($login)
			->setCredential($senha);
		
		$select = $authAdapter->getDbSelect();
		$select->where('status_usuario = 1');
		
		$auth->setAdapter($authAdapter);
		$result = $auth->authenticate();
		
		if (!$result->isValid()) 
		{
			return 'login_invalido';
		}
		
		$resultRow = $authAdapter->getResultRowObject();
		
		if ($resultRow->bloqueado_usuario == 1) {
			return 'usuario_bloqueado';
		}
		
		$sessao = $this->getServiceLocator()->get('user_session_usuarios');
		$sessao->offsetSet('usuario', $resultRow);
		
		return 'logado';
	}
	
	public function authorize($moduleName, $controllerName, $actionName, $useAcl)
	{
	    // Se a variável useAcl for falsa então não está sendo usada a acl
	    if (!$useAcl && $this->hasIdentity()) {
	        return true;
	    }
	    
	    $auth = new AuthenticationService();
	    $role = 'visitante';
	    
	    if ($auth->hasIdentity()) {
	        $role = $this->getRole();
	    }
	     
	    $resource = $controllerName . '.' . $actionName;
	    $acl = $this->getServiceLocator()->get('user_service_acl')->build();
	     
	    if ($acl->isAllowed($role, $resource)) {
	        return true;
	    }
	     
	    return false;
	}
	
	public function logout()
	{
		$auth = new AuthenticationService();
		$session = $this->getServiceLocator()->get('user_session_usuarios');
		$session->offsetUnset('usuario');
		$auth->clearIdentity();
		
		return true;
	}
	
	public function hasIdentity()
	{
		$auth = new AuthenticationService();
		
		if ($auth->hasIdentity()) {
			return true;
		}
		
		return false;
	}
	
	public function UserIdentity()
	{
		$auth = new AuthenticationService();
	
		if ($auth->hasIdentity()) {
			return $auth->getIdentity();
		}
	
		return false;
	}
	
	public function UserId() 
	{
		return isset($this->UserAuthentication()->pk_usuario) ? $this->UserAuthentication()->pk_usuario : 0;
	}
	
	public function isAdmin()
	{
		if ($this->UserAuthentication()->papel_usuario == 'admin')
		{
			return true;
		}
		
		return false;
	}
	
	public function getRole()
	{
	    if ($this->hasIdentity()) {
	        return $this->UserAuthentication()->papel_usuario;
	    }
	}
	
	public function UserAuthentication()
	{
		$sessao = $this->getServiceLocator()->get('user_session_usuarios');
		return $sessao->offSetGet('usuario');
	}
	
}