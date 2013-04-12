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
        if ($wrapper = $this->smarty->getFunctionCompiler()) {
            if (!isset($this->smarty->registered_plugins[Smarty::PLUGIN_COMPILER][$tag])) {
                if ($tag != 'cycle') {
                    if ($wrapper->getManager()->has($tag)) {
                        $this->smarty->registerPlugin(Smarty::PLUGIN_COMPILER, $tag, array($wrapper, $tag));
                    }
                }
            }
        }
        // Lazy-load modifier compilers
        if ($tag == 'private_modifier') {
            if ($modifierWrapper = $this->smarty->getModifierCompiler()) {
                foreach ($parameter['modifierlist'] as $single_modifier) {
                    $modifier = $single_modifier[0];
                    if (isset($this->smarty->registered_plugins[Smarty::PLUGIN_MODIFIERCOMPILER][$modifier][0])) {
                        continue;
                    }
                    // zf2 intl has dateformat view helper, it should not be invoked by smarty date_format modifier
                    if ($modifier == 'date_format') {
                        continue;
                    }
                    if ($wrapper->getManager()->has($modifier)) {
                        $this->smarty->registerPlugin(Smarty::PLUGIN_MODIFIERCOMPILER, $modifier, array($modifierWrapper, $modifier));
                    }
                }
            }
        }
        
        return parent::compileTag($tag, $args, $parameter);
    }
}
