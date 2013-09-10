<?php

namespace JVUser\Filter;

use Zend\InputFilter\InputFilter;

class Usuario extends InputFilter
{
	public function __construct()
	{
		$this->add(array(
			'name' => 'nome_usuario',
			'allow_empty' => false
		));
		
		$this->add(array(
			'name' => 'email_usuario',
			'allow_empty' => false
		));
		
		$this->add(array(
			'name' => 'confirme_email_usuario',
			'allow_empty' => false,
		    'validators' => array(
		        array(
		            'name' => 'JVBase\Filter\Identical',
		            'options' => array(
		                'field' => 'email_usuario',
		            ),
		        ),
		    ),
		));
		
		$this->add(array(
			'name' => 'senha_usuario',
			'allow_empty' => false
		));
		
		$this->add(array(
			'name' => 'confirme_senha',
			'allow_empty' => false,
		    'validators' => array(
		        array(
		            'name' => 'JVBase\Filter\Identical',
		            'options' => array(
		                'field' => 'senha_usuario',
		            ),
		        ),
		    ),
		));
		
	}
}