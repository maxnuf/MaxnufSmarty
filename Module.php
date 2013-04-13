<?php

namespace MaxnufSmarty;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements
    AutoloaderProviderInterface,
    ServiceProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'MaxnufSmarty'  => 'MaxnufSmarty\Service\SmartyFactory',
                'SmartyViewRenderer' => 'MaxnufSmarty\Service\ViewRendererFactory',
                'SmartyViewStrategy' => 'MaxnufSmarty\Service\ViewStrategyFactory',
                'MaxnufSmartyResolver' => 'MaxnufSmarty\Service\MaxnufSmartyResolverFactory',
            )
        );
    }
}