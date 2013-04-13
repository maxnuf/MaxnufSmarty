<?php

namespace MaxnufSmarty\Service;

use MaxnufSmarty\Smarty\MaxnufSmarty;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use MaxnufSmarty\Handler\PluginHandler;
use MaxnufSmarty\Handler\PluginWrapper;
use MaxnufSmarty\Compiler\FunctionCompiler;
use MaxnufSmarty\Compiler\ModifierCompiler;

class SmartyFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return \MaxnufSmarty\Smarty\MaxnufSmarty
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $plugins = $config['maxnufsmarty']['plugins'];
        $smartyConfig = $config['maxnufsmarty']['config'];
        $manager = $serviceLocator->get('ViewHelperManager');

        $functionCompiler = new FunctionCompiler($manager);
        $modifierCompiler = new ModifierCompiler($manager);

        $smarty = new MaxnufSmarty($smartyConfig, $functionCompiler, $modifierCompiler);
        $smarty->setTemplateDir($config['view_manager']['template_path_stack']);
        $smarty->addPluginsDir($plugins);
        $smarty->addPluginsDir(__DIR__ . '/../Smarty/SysPlugins');
        
        $wrapper = new PluginWrapper($manager);
        $smarty->registerObject('zf', $wrapper, array(), false);
        
        return $smarty;
    }
}
