<?php

namespace JVUser\Mapper;

use JVBase\Mapper\AbstractMapper;

class Usuario extends AbstractMapper
{
	protected $model = '\User\Model\Usuario';
	protected $table = 'tbl_usuarios';
	protected $tableKeyFields = array('pk_usuario');
	protected $tableFields = array('pk_usuario', 'nome_usuario', 'email_usuario', 'senha_usuario', 'status_usuario', 'papel_usuario', 'token_usuario', 'dta_inc_usuario', 'bloqueado_usuario');
	
	public function getEndereco(array $where)
	{
		return $this->findOneBy($where, 'tbl_usuarios_enderecos');
	}
	
	public function getAcl()
	{
	    $result = $this->findAll('vw_acl');
	    $resources = $this->getResources();
	    
	    if (count($result)) {
    	    foreach ($result as $item) {
    	        $arrPermissoes[$item['nome_role']][$item['tipo_permissao']][] = $item['nome_resource'];
    	         
    	        $arrRole[$item['pk_role']][$item['nome_role']] = $item['nome_role_parent'];
    	        //$arrRole[$item['nome_role']] = empty($item['nome_role_parent']) ? $item['nome_role'] : $item['nome_role_parent'];
    	         
    	        $arrAcl['acl']['privilege'] = $arrPermissoes;
    	    }
    	    
    	    $arrAcl['acl']['roles'] = $this->getRoles();
    	    
    	    // Tratando a lista de roles
    	    /* ksort($arrAcl['acl']['roles_temp']);
    	    foreach ($arrAcl['acl']['roles_temp'] as $indice => $valor) {
    	        $arrAcl['acl']['roles'][key($valor)] = current($valor);
    	    }
    	    unset($arrAcl['acl']['roles_temp']); */
	    
	        $arrAcl['acl']['resources'] = $resources;
	        return $arrAcl;
	    }
	    
	    return false;
	}
	
	public function getRoles()
	{
	    $result = $this->findAll('vw_roles');
	    if ($result) {
	        foreach ($result as $r) {
	            $item[$r['nome_role']] = $r['nome_role_parent'];
	        }
	    }
	    
	    return $item;
	}
	
	public function getResources()
	{
	    $result = $this->findAll('tbl_resources');
	    if ($result) {
	        foreach ($result as $r) {
	            $item[] = $r['nome_resource'];
	        }
	    }
	    
	    return $item;
	}
}