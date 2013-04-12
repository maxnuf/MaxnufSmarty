<?php

namespace MaxnufSmarty\Smarty;

use Smarty;
use Zend\View\HelperPluginManager;
use MaxnufSmarty\Smarty\SysPlugins\MaxnufFileResource;
use MaxnufSmarty\Compiler\FunctionCompiler;
use MaxnufSmarty\Compiler\ModifierCompiler;

class MaxnufSmarty extends Smarty
{
    /**
     * @var \MaxnufSmarty\Compiler\FunctionCompiler
     */
    protected $functionCompiler;
    
    /**
     * @var \MaxnufSmarty\Compiler\ModifierCompiler
     */
    protected $modifierCompiler;

    function __construct($options = array(), FunctionCompiler $functionCompiler, ModifierCompiler $modifierCompiler)
    {
        $this->functionCompiler = $functionCompiler;
        $this->modifierCompiler = $modifierCompiler;
        
        parent::__construct();
        
        $this->registerResource('maxnufFile', new MaxnufFileResource());
        $this->default_resource_type = 'maxnufFile';

        $this->setOptions($options);
    }
    
    public function setOptions($options)
    {
    	if (isset($options['caching'])) {
            $this->caching = $options['caching'];
    	}
    	if (isset($options['cache_lifetime'])) {
            $this->cache_lifetime = $options['cache_lifetime'];
    	}
        if (isset($options['template_dir'])) {
            $this->setTemplateDir($options['template_dir']);
        }
        if (isset($options['compile_dir'])) {
            $this->setCompileDir($options['compile_dir']);
        }
        if (isset($options['config_dir'])) {
            $this->setConfigDir($options['config_dir']);
        }
        if (isset($options['cache_dir'])) {
            $this->setCacheDir($options['cache_dir']);
        }
        if (isset($options['left_delimiter'])) {
            $this->left_delimiter = $options['left_delimiter'];
        }
        if (isset($options['right_delimiter'])) {
            $this->right_delimiter = $options['right_delimiter'];
        }
    }

    public function getFunctionCompiler()
    {
        return $this->functionCompiler;
    }
        
    public function getModifierCompiler()
    {
        return $this->modifierCompiler;
    }
}
