<?php

namespace MaxnufSmarty\Service;

use MaxnufSmarty\View\Renderer\Renderer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ViewRendererFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return \MaxnufSmarty\View\Renderer\Renderer
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $renderer = new Renderer(
            $serviceLocator->get('MaxnufSmarty'),
            $serviceLocator->get('SmartyViewResolver')
        );
        $renderer->setHelperPluginManager($serviceLocator->get('ViewHelperManager'));

        return $renderer;
    }
}
