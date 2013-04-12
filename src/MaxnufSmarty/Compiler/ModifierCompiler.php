<?php

namespace MaxnufSmarty\Compiler;

use Zend\View\HelperPluginManager;

class ModifierCompiler
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
            $single_modifier = $argv[0];
            $params = implode(',', $single_modifier);
            return '$_smarty_tpl->smarty->registered_objects[\'zf\'][0]->' . $method . '(' . $params . ')';
        }
        return '';
    }
    
    public function openTag($single_modifier, $compiler)
    {
        $params = implode(',', $single_modifier);
        return '$_smarty_tpl->smarty->registered_objects[\'zf\'][0]->form()->openTag(' . $params . ')';
    }
}
