<?php

namespace MaxnufSmarty\Compiler;

use Zend\View\HelperPluginManager;

class FunctionCompiler
{
    protected $manager;
    protected $pluginCache;
    protected $callableMethods = array(
        'formCloseTag' => 'formCloseTag',
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
            return '<?php echo $_smarty_tpl->smarty->registered_objects[\'zf\'][0]->' . $name . '(' . $params . '); ?>';
        }
        return '';
    }
    
    public function formCloseTag($single_modifier, $compiler)
    {
        return $this->manager->get('form')->closeTag();
    }
}
