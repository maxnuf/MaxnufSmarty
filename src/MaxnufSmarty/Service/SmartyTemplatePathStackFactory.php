<?php

namespace MaxnufSmarty\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Resolver\TemplatePathStack;

class SmartyTemplatePathStackFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return \Zend\View\Resolver\TemplatePathStack
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $suffix = $config['maxnufsmarty']['suffix'];

        $stack = array();
        if (is_array($config) && isset($config['view_manager'])) {
            $config = $config['view_manager'];
            if (is_array($config) && isset($config['template_path_stack'])) {
                $stack = $config['template_path_stack'];
            }
        }
        $templatePathStack = new TemplatePathStack();
        $templatePathStack->setDefaultSuffix($suffix);
        $templatePathStack->addPaths($stack);

        return $templatePathStack;
    }
}
