<?php

namespace MaxnufSmarty\Service;

use MaxnufSmarty\View\Renderer\Renderer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Resolver\TemplatePathStack;

class ViewRendererFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $config = $config['maxnufsmarty'];

        $pathResolver = $serviceLocator->get('ViewTemplatePathStack');
        $pathResolver->setDefaultSuffix($config['suffix']);
        $resolver = $serviceLocator->get('ViewResolver');

        $renderer = new Renderer();
        $renderer->setEngine($serviceLocator->get('MaxnufSmarty'));
        $renderer->setResolver($resolver);
        $renderer->setHelperPluginManager($serviceLocator->get('ViewHelperManager'));

        return $renderer;
    }
}