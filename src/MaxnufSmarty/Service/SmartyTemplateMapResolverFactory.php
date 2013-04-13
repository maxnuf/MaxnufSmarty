<?php

namespace MaxnufSmarty\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Resolver\TemplateMapResolver;

class SmartyTemplateMapResolverFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * 
     * @return \Zend\View\Resolver\TemplateMapResolver
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $suffix = $config['maxnufsmarty']['suffix'];
        
        $map = array();
        if (isset($config['view_manager']['template_map'])) {
            foreach($config['view_manager']['template_map'] as $name => $path) {
                if ($suffix == pathinfo($path, PATHINFO_EXTENSION)) {
                    $map[$name] = $path;
                }
            }
            
        }
        
        return new TemplateMapResolver($map);
    }
}