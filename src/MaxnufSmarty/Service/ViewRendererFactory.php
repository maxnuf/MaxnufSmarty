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

        $zfPathResolver = $serviceLocator->get('ViewTemplatePathStack');
        $smartyPathResolver = new TemplatePathStack;
        $smartyPathResolver->setDefaultSuffix($config['suffix']);
        $smartyPathResolver->setPaths($zfPathResolver->getPaths());
        
        $resolver = $serviceLocator->get('ViewResolver');
        $resolver->attach($smartyPathResolver);

        $renderer = new Renderer();
        $renderer->setEngine($serviceLocator->get('MaxnufSmarty'));
        $renderer->setResolver($resolver);
        $renderer->setHelperPluginManager($serviceLocator->get('ViewHelperManager'));

        return $renderer;
    }
}