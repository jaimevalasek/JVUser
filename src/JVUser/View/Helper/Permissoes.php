<?php

namespace JVUser\View\Helper;

use Zend\Session\Container;
use Zend\View\Helper\AbstractHelper;

class Permissoes extends AbstractHelper
{
    public function __invoke()
    {
        $container = new Container('sessao');
        
        return array(
            'permissoes' => $container->permissoes,
        );
    }
}