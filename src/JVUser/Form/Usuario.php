<?php

namespace JVUser\Form;

use Zend\Form\Element\Email;

use Zend\Captcha\Image;

use Zend\Form\Form;
use Zend\Form\Element\Submit,
    Zend\Form\Element\Password,
    Zend\Form\Element\Text,
    Zend\Form\Element\Hidden;

class Usuario extends Form
{
	public function __construct($urlcaptcha = null)
	{
		parent::__construct();
		
		$id = new Hidden('pk_usuario');
		
		$nome = new Text('nome_usuario');
		$nome->setLabel('Nome:')
		    ->setAttributes(array(
		        'required' => true,
		        'placeholder' => 'Digite o seu nome'));;
		
		$email = new Email('email_usuario');
		$email->setLabel('Email:')
		    ->setAttributes(array(
		        'required' => true,
		        'placeholder' => 'Digite o seu email'));
		
		$reemail = new Email('confirme_email_usuario');
		$reemail->setLabel('Confirme o seu email:')
		    ->setAttributes(array(
		        'autocomplete' => false,
		        'required' => true,
		        'placeholder' => 'Confirme o seu email',
		        'id' => 'confirme_email_usuario'));
		
		$senha = new Password('senha_usuario');
		$senha->setLabel('Senha:')
		    ->setAttributes(array(
		        'autocomplete' => false,
		        'required' => true,
		        'placeholder' => 'Digite a sua senha',
		        'id' => 'senha_usuario'));
		
		$reSenha = new Password('confirme_senha');
		$reSenha->setLabel('Confirme a senha:')
		    ->setAttributes(array(
		        'autocomplete' => false,
		        'required' => true,
		        'placeholder' => 'Confirme a sua senha',
		        'id' => 'confirme_senha'));
		
		$submit = new Submit('submit');
		$submit->setValue('Salvar');
		
		$dirdata = './data';
		
		$captchaImage = new Image(array(
                'font' => $dirdata . '/fonts/stocky.ttf',
                'width' => 250,
                'height' => 100,
                'dotNoiseLevel' => 40,
                'lineNoiseLevel' => 3)
        );
        $captchaImage->setImgDir($dirdata.'/captcha');
        $captchaImage->setImgUrl($urlcaptcha);
		
        //add captcha element...
        $this->add(array(
            'type' => 'Zend\Form\Element\Captcha',
            'name' => 'captcha',
            'attributes' => array('placeholder' => 'Digite os caracteres da imagem'),
            'options' => array(
                'label' => 'Por favor, prove que você é humano',
                'captcha' => $captchaImage,
            ),
        ));
        
		// Adicionando os campos
		$this->add($id);
		$this->add($nome);
		$this->add($email);
		$this->add($reemail);
		$this->add($senha);
		$this->add($reSenha);
		$this->add($submit);
	}
}