<?php

namespace JVUser\Service;

use Zend\Session\Container;

use Zend\Cache\StorageFactory;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class Acl implements ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        
        return $this;
    }

    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Constroi a ACL
     * @return Acl 
     */
    public function build()
    {
        $cache = $this->getServiceManager()->get('application-cache');
        $cacheSuccess = false;
        
        // Cacheando a ACL pra não fazer tantas requisições no Banco
        if ($cache['status']) {
        	$aclCache = $cache['cache']->getItem('acl', $cacheSuccess);
        	//$cache['cache']->removeItem('acl');
        	$aclCache = json_decode($aclCache, true);
        }

    	if (!$cacheSuccess) {
	    	$usuarioService = $this->getServiceManager()->get('user_service_usuarios');
	    	$config = $usuarioService->getAcl();
	    	
	    	$configCache = json_encode($config);
	    	$cache['cache']->addItem('acl', $configCache);
    	} else {
    		$config = $aclCache;
    	}
    	
    	
    	$authService = $this->getServiceManager()->get('user_service_auth');
    	$role = $authService->getRole();
    	
    	$arrPermissoes = array('allow' => array(), 'deny' => array());
    	
    	// Gerendo os resources liberados para acesso nivel 1
    	foreach ($config['acl']['roles'] as $role1 => $parent1) {
    	    if ($role1 == $role) {
            	foreach ($config['acl']['privilege'][$role1] as $access => $resource)
            	{
            	    $arrPermissoes[$access] += $resource;
            	}
            	
            	// Gerendo os resources liberados para acesso nivel 2
            	if (!empty($parent1)) {
            	    foreach ($config['acl']['roles'] as $role2 => $parent2) {
            	        if ($role2 == $parent1) {
                	        foreach ($config['acl']['privilege'][$role2] as $access => $resource)
                	        {
                	            foreach ($resource as $item) {
                    	            $arrPermissoes[$access][] = $item;
                	            }
                	        }
                    	    // Gerendo os resources liberados para acesso nivel 3
                    	    if (!empty($parent2)) {
                    	        foreach ($config['acl']['roles'] as $role3 => $parent3) {
                    	            if ($role3 == $parent2) {
                        	            foreach ($config['acl']['privilege'][$role3] as $access => $resource)
                        	            {
                        	                foreach ($resource as $item) {
                        	                    $arrPermissoes[$access][] = $item;
                        	                }
                        	            }
                    	            }
                    	        }
                    	    }
            	        }
            	    }
            	     
            	}
    	    }
    	}
    	
    	$container = new Container('sessao');
    	$container->offsetSet('permissoes', $arrPermissoes);
    	
        $acl = new ZendAcl();
        foreach ($config['acl']['roles'] as $role => $parent) {
            $acl->addRole(new Role($role), $parent);
        }
        foreach ($config['acl']['resources'] as $r) {
            $acl->addResource(new Resource($r));
        }
        
        foreach ($config['acl']['privilege'] as $role => $privilege) {
            if (isset($privilege['allow'])) {
                foreach ($privilege['allow'] as $p) {
                    $acl->allow($role, $p);
                }
            }
            if (isset($privilege['deny'])) {
                foreach ($privilege['deny'] as $p) {
                    $acl->deny($role, $p);
                }
            }
        }
        
        return $acl;
    }
}