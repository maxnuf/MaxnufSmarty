<?php

namespace MaxnufSmarty\Compiler;

use Zend\View\HelperPluginManager;

class ModifierCompiler
{
    protected $manager;
    protected $pluginCache;
    protected $callableMethods = array(
        'formOpenTag' => 'formOpenTag',
    );
    
    // some view helpers have same name as smarty plugins
    protected $ignoreMethods = array(
        'date_format' => false, // note: dateformat still works
    );

    public function __construct(HelperPluginManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function has($name)
    {
        if (isset($this->callableMethods[$name])) {
            return true;
        }
        if (isset($this->ignoreMethods[$name])) {
            return false;
        }
        return $this->manager->has($name);
    }
    
    public function __call($name, $arguments)
    {
        if (!isset($this->pluginCache[$name])) {
            $this->pluginCache[$name] = $this->manager->get($name);
        }
        if (is_callable($this->pluginCache[$name])) {
            $single_modifier = $arguments[0];
            $params = implode(',', $single_modifier);
            return '$_smarty_tpl->smarty->registered_objects[\'zf\'][0]->' . $name . '(' . $params . ')';
        }
        return '';
    }
    
    public function formOpenTag($single_modifier, $compiler)
    {
        $params = implode(',', $single_modifier);
        return '$_smarty_tpl->smarty->registered_objects[\'zf\'][0]->form()->openTag(' . $params . ')';
    }
}
