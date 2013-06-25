<?php

namespace JVUser\Filter;

use Zend\InputFilter\InputFilter;

class Auth extends InputFilter
{
	public function __construct()
	{
		$this->add(array(
			'name' => 'login',
			'allow_empty' => false
		));
		
		$this->add(array(
			'name' => 'senha',
			'allow_empty' => false
		));
	}
}