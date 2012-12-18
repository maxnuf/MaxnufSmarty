<?php

namespace MaxnufSmarty\Service;

use MaxnufSmarty\View\Renderer\Renderer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Resolver\TemplatePathStack;
use Zend\View\Resolver\TemplateMapResolver;
use Zend\View\Resolver\AggregateResolver;

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
        
        $zfTemplateMap = $serviceLocator->get('ViewTemplateMapResolver');
        $smartyMapResolver = new TemplateMapResolver;

        foreach($zfTemplateMap as $name => $path) {
            if ($config['suffix'] == pathinfo($path, PATHINFO_EXTENSION)) {
                $smartyMapResolver->add($name, $path);
            }
        }
        
        $resolver = new AggregateResolver;
        $resolver->attach($smartyPathResolver);
        $resolver->attach($smartyMapResolver);

        $renderer = new Renderer();
        $renderer->setEngine($serviceLocator->get('MaxnufSmarty'));
        $renderer->setResolver($resolver);
        $renderer->setHelperPluginManager($serviceLocator->get('ViewHelperManager'));

        return $renderer;
    }
}