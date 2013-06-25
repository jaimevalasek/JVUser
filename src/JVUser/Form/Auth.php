<?php

namespace JVUser\Form;

use Zend\Form\Form;

use Zend\Form\Element\Submit,
    Zend\Form\Element\Password,
    Zend\Form\Element\Text;

class Auth extends Form
{
	public function __construct()
	{
		parent::__construct('formAuth');
		
		$this->setAttribute('action', '/auth');
		
		$login = new Text('login');
		$login->setLabel('Login');
		
		$senha = new Password('senha');
		$senha->setLabel('Senha');
		
		$submit = new Submit('submit');
		$submit->setValue('Entrar')->setAttributes(array('id' => 'auth-submit', 'class' => 'btn btn-primary'));
		
		// Adicionando os campos
		$this->add($login);
		$this->add($senha);
		$this->add($submit);
	}
}