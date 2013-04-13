<?php

/**
 * MaxnufSmarty Template Compiler
 *
 * Extends Smarty Template Compiler Base
 * It loads Zend View Helpers if called as function or modifier and registers a callback
 * to a wrapper class as function or modifier compiler.
 * The wrapper class compiles to code that can call the actual View Helper
 *
 * @package MaxnufSmarty
 * @subpackage Compiler
 */
class Smarty_Maxnuf_Smarty_Template_Compiler extends \Smarty_Internal_SmartyTemplateCompiler
{
    public function compileTag($tag, $args, $parameter = array())
    {
        // Lazy-Load plugin compilers
        if ($functionWrapper = $this->smarty->getFunctionCompiler()) {
            if (!isset($this->smarty->registered_plugins[Smarty::PLUGIN_COMPILER][$tag])) {
                if ($functionWrapper->has($tag)) {
                    $this->smarty->registerPlugin(Smarty::PLUGIN_COMPILER, $tag, array($functionWrapper, $tag));
                }
            }
        }
        // Lazy-load modifier compilers
        if ($tag == 'private_modifier') {
            if ($modifierWrapper = $this->smarty->getModifierCompiler()) {
                foreach ($parameter['modifierlist'] as $single_modifier) {
                    $modifier = $single_modifier[0];
                    if (!isset($this->smarty->registered_plugins[Smarty::PLUGIN_MODIFIERCOMPILER][$modifier][0])) {
                        if ($modifierWrapper->has($modifier)) {
                            $this->smarty->registerPlugin(Smarty::PLUGIN_MODIFIERCOMPILER, $modifier, array($modifierWrapper, $modifier));
                        }
                    }
                }
            }
        }
        
        return parent::compileTag($tag, $args, $parameter);
    }
}
