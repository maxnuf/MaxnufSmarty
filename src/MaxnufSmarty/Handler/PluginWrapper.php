<?php

namespace MaxnufSmarty\Handler;

use Zend\View\HelperPluginManager;

class PluginWrapper
{
    protected $manager;
    protected $pluginCache;
    
    public function __construct(HelperPluginManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function getManager()
    {
        return $this->manager;
    }
    
    public function __call($method, $argv)
    {
        if (!isset($this->pluginCache[$method])) {
            $this->pluginCache[$method] = $this->manager->get($method);
        }
        if (is_callable($this->pluginCache[$method])) {
            return call_user_func_array($this->pluginCache[$method], $argv);
        }
        return $this->pluginCache[$method];
    }
}
