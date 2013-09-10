<?php

namespace JVUser;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Admin\Form\Categorias as CategoriasForm;
use Admin\Form\Produtos as ProdutosForm;

class Module
{
	public function onBootstrap(MvcEvent $e) 
	{
		$eventManager = $e->getApplication()->getEventManager();
		$moduleRouteListenet = new ModuleRouteListener();
		$moduleRouteListenet->attach($eventManager);
		
		$moduleManager = $e->getApplication()->getServiceManager()->get('modulemanager');
		$sharedEvents = $moduleManager->getEventManager()->getSharedManager();
		$sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', MvcEvent::EVENT_DISPATCH, array($this, 'mvcPreDispatch'), 100);
	}
	
	public function getConfig() 
	{
		return include __DIR__ . '/config/module.config.php';
	}
	
	public function getAutoloaderConfig() 
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}
	
    public function mvcPreDispatch($event)
    {
    	$di = $event->getTarget()->getServiceLocator();
    	$routeMatch = $event->getRouteMatch();
    	
    	$moduleName = $routeMatch->getParam('module');
    	$controllerName = $routeMatch->getParam('controller');
    	$actionName = $routeMatch->getParam('action');
    	
    	// Pega as configurações para verificar se pode usar acl e manda para o método authirize
    	$config = include __DIR__ . '/config/module.config.php';
    
    	$authService = $di->get('jvuser_service_auth');
    	if (!$authService->authorize($moduleName, $controllerName, $actionName, $config['useAcl'])) {
    		
    		$flashMessenger = $event->getTarget()->flashMessenger();
    		$flashMessenger->addMessage(array('error' => 'Você não tem permissão de acesso a essa área!'));
    		$response = $event->getResponse();
    		$response->getHeaders()->addHeaderLine('Location', '/auth');
    		$response->setStatusCode(302);
    	}
    	
    	return true;
    }
}