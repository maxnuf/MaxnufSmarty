<?php

namespace MaxnufSmarty\Service;

use InvalidArgumentException;
use MaxnufSmarty\Smarty\MaxnufSmarty;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use MaxnufSmarty\Handler\PluginHandler;

class SmartyFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
		$plugins = $config['maxnufsmarty']['plugins'];
        $smartyConfig = $config['maxnufsmarty']['config'];
        $manager = $serviceLocator->get('ViewHelperManager');

        $smarty = new MaxnufSmarty($smartyConfig);
        $smarty->setTemplateDir($config['view_manager']['template_path_stack']);
        
        $smarty->addPluginsDir($plugins);
        
		$smarty->registerPlugin(MaxnufSmarty::PLUGIN_COMPILER, 'formCloseTag', array('Zend\Form\View\Helper\Form', 'closeTag'));
		
        $handler = new PluginHandler($manager);
        $smarty->registerDefaultPluginHandler(array($handler, 'getHelper'));

        return $smarty;
    }
}