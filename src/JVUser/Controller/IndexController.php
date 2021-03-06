<?php

namespace JVUser\Controller;

use JVUser\Form\Usuario;

use Zend\Validator\AbstractValidator;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function generateAction()
    {
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', "image/png");
    
        $id = $this->params('id', false);
    
        if ($id) {
    
            $image = './data/captcha/' . $id;
    
            if (file_exists($image) !== false) {
                $imagegetcontent = @file_get_contents($image);
    
                $response->setStatusCode(200);
                $response->setContent($imagegetcontent);
    
                if (file_exists($image) == true) {
                    unlink($image);
                }
            }
    
        }
    
        return $response;
    }
    
	public function indexAction()
	{
		if ($this->flashMessenger()->hasMessages()) {
			$this->getServiceLocator()->get('jv_flashmessenger');
		}
		
		$authService = $this->getServiceLocator()->get('jvuser_service_auth');
		
		return new ViewModel(array(
			'usuario' => $authService->UserAuthentication()
		));
	}
	
	public function registerAction() 
	{
		$form = new Usuario($this->getRequest()->getBaseUrl().'/user/index/generate/');
		$request = $this->getRequest();

		if ($request->isPost()) {
			AbstractValidator::setDefaultTranslator($this->getServiceLocator()->get('MvcTranslator'));
			$form->setData($request->getPost());
			$form->setInputFilter($this->getServiceLocator()->get('jvuser_filter_usuarios'));
			
			if ($form->isValid()) {
				$usuarioService = $this->getServiceLocator()->get('jvuser_service_usuarios');
				if ($usuarioService->insert($form->getData())) {
					$this->flashMessenger()->addMessage(array('success' => 'O seu cadastro foi efetuado com sucesso.'));
					$this->redirect()->toUrl('/auth');
				}
			}
		}
		
		return new ViewModel(array(
			'form' => $form
		));
	}
	
	public function authAction() 
	{
		$popup = $this->params('id') ?: false;
		$h1 = $this->params('h1') ?: false;
		$redirect = $this->params()->fromQuery('redirect');
		
		if ($this->flashMessenger()->hasMessages()) {
			$this->getServiceLocator()->get('jv_flashmessenger');
		}

		$form = $this->getServiceLocator()->get('jvuser_form_auth');
		$request = $this->getRequest();
		
		if ($request->isPost())
		{
			AbstractValidator::setDefaultTranslator($this->getServiceLocator()->get('MvcTranslator'));
			$form->setData($request->getPost());
			$form->setInputFilter($this->getServiceLocator()->get('jvuser_filter_auth'));
			
			if ($form->isValid())
			{
				$authService = $this->getServiceLocator()->get('jvuser_service_auth');
				$result = $authService->authenticate($form->getData());
				if ($result == 'logado') {
					$this->flashMessenger()->addMessage(array('success' => 'Usuario logado com sucesso!'));
					if (strlen($redirect) > 2) {
						return $this->redirect()->toUrl($redirect);
					}
					$this->redirect()->toUrl('/');
				} else if($result == 'login_invalido') {
					$this->flashMessenger()->addMessage(array('error' => 'Erro ao tentar logar no sistema, dados inválidos. <br />Obs.: Lembre-se que para fazer a autenticação você deve confirmar o seu cadastro, caso seja esse o motivo, verifique o seu email!'));
					$this->redirect()->toUrl('/auth');
				} else {
					$this->flashMessenger()->addMessage(array('error' => 'Erro ao tentar logar no sistema, dados inválidos. <br />Por favor, entre em contato conosco!'));
					$this->redirect()->toUrl('/auth');
				}
			}
		}
		
		$view = new ViewModel(array(
			'form' => $form,
			'popup' => $popup,
			'h1' => $h1
		));
		
		if ($popup) {
			$view->setTerminal(true);
		}
		
		return $view;
	}
	
	public function activateAction()
	{
		$token = $this->params('token');
		$usuarioService = $this->getServiceLocator()->get('jvuser_service_usuarios');
		
		if ($usuarioService->findRow(array('token_usuario' => $token, 'status_usuario' => true))) {
			$this->flashMessenger()->addMessage(array('alert' => 'Usuário já ativado!'));
			return $this->redirect()->toUrl('/auth');
		}
		
		if ($usuarioService->activate(array('status_usuario' => 1), array('token_usuario' => $token))) {
			$this->flashMessenger()->addMessage(array('success' => 'Usuário ativado com sucesso!'));
			return $this->redirect()->toUrl('/auth');
		} else {
			$this->flashMessenger()->addMessage(array('alert' => 'Usuário não encontrado para ativação!'));
			return $this->redirect()->toUrl('/auth');
		}
		
		return new ViewModel(array());
	}
	
	public function logoutAction()
	{
		$authService = $this->getServiceLocator()->get('jvuser_service_auth');
		if ($authService->logout()) {
			$this->flashMessenger()->addMessage(array('success' => 'Você fez o logout do sistema com sucesso!'));
			return $this->redirect()->toUrl('/auth');
		}
	}
	
}
