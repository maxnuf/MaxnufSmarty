<?php

namespace MaxnufSmarty\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Resolver\AggregateResolver;

class SmartyViewResolverFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * 
     * @return \Zend\View\Resolver\AggregateResolver
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $resolver = new AggregateResolver;
        $resolver->attach($serviceLocator->get('SmartyTemplatePathStack'));
        $resolver->attach($serviceLocator->get('SmartyTemplateMapResolver'));
        
        return $resolver;
    }
}