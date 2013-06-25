<?php

namespace JVUser\Model;

use BASEDefault\Model\AbstractModel;

class Usuario extends AbstractModel
{
	protected $pkUsuario;
	protected $nomeUsuario;
	protected $emailUsuario;
	protected $senhaUsuario;
	protected $statusUsuario;
	protected $papelUsuario;
	protected $tokenUsuario;
	protected $dtaIncUsuario;
	protected $bloqueadoUsuario;
	
	public function getPkUsuario()
	{
	    return $this->pkUsuario;
	}

	public function setPkUsuario($pkUsuario)
	{
	    $this->pkUsuario = $pkUsuario;
	    return $this;
	}

	public function getNomeUsuario()
	{
	    return $this->nomeUsuario;
	}

	public function setNomeUsuario($nomeUsuario)
	{
	    $this->nomeUsuario = $nomeUsuario;
	    return $this;
	}

	public function getEmailUsuario()
	{
	    return $this->emailUsuario;
	}

	public function setEmailUsuario($emailUsuario)
	{
	    $this->emailUsuario = $emailUsuario;
	    return $this;
	}

	public function getSenhaUsuario()
	{
	    return $this->senhaUsuario;
	}

	public function setSenhaUsuario($senhaUsuario)
	{
	    $this->senhaUsuario = md5($senhaUsuario);
	    return $this;
	}

	public function getStatusUsuario()
	{
	    return $this->statusUsuario;
	}

	public function setStatusUsuario($statusUsuario)
	{
	    $this->statusUsuario = $statusUsuario;
	    return $this;
	}

	public function getPapelUsuario()
	{
	    return $this->papelUsuario;
	}

	public function setPapelUsuario($papelUsuario)
	{
	    $this->papelUsuario = $papelUsuario;
	    return $this;
	}

	public function getTokenUsuario()
	{
	    return $this->tokenUsuario;
	}

	public function setTokenUsuario()
	{
		$codigo = \date('YmdHisu');
	    $this->tokenUsuario = md5($codigo);
	    return $this;
	}

	public function getDtaIncUsuario()
	{
	    return $this->dtaIncUsuario;
	}

	public function setDtaIncUsuario()
	{
	    $this->dtaIncUsuario = \date('Y-m-d H:i:s');
	    return $this;
	}

	public function getBloqueadoUsuario()
	{
	    return $this->bloqueadoUsuario;
	}

	public function setBloqueadoUsuario($bloqueadoUsuario)
	{
	    $this->bloqueadoUsuario = $bloqueadoUsuario;
	}
}