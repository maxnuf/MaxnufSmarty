<?php

namespace MaxnufSmarty\Service;

use MaxnufSmarty\View\Strategy\Strategy;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ViewStrategyFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $renderer = $serviceLocator->get('SmartyViewRenderer');
        $strategy = new Strategy($renderer);

        return $strategy;
    }
}