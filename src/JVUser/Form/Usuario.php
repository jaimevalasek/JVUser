<?php

namespace JVUser\Form;

use Zend\Form\Form;
use Zend\Form\Element\Submit,
    Zend\Form\Element\Password,
    Zend\Form\Element\Text,
    Zend\Form\Element\Hidden;

class Usuario extends Form
{
	public function __construct()
	{
		parent::__construct();
		
		$id = new Hidden('pk_usuario');
		
		$nome = new Text('nome_usuario');
		$nome->setLabel('Nome:');
		
		$email = new Text('email_usuario');
		$email->setLabel('Email:');
		
		$senha = new Password('senha_usuario');
		$senha->setLabel('Senha:');
		
		$reSenha = new Password('confirme_senha');
		$reSenha->setLabel('Confirme a senha:');
		
		$submit = new Submit('submit');
		$submit->setValue('Salvar');
		
		// Adicionando os campos
		$this->add($id);
		$this->add($nome);
		$this->add($email);
		$this->add($senha);
		$this->add($reSenha);
		$this->add($submit);
	}
}