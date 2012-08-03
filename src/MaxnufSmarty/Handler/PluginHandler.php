<?php

namespace MaxnufSmarty\Handler;

use Zend\View\HelperPluginManager;
use Smarty;
use Smarty_Internal_Template;

class PluginHandler
{
    public static $manager;
    
    public function __construct(HelperPluginManager $manager)
    {
        self::setManager($manager);
    }
    
    /**
     * @param \Zend\View\HelperPluginManager $manager
     * @return Environment
     */
    public static function setManager(HelperPluginManager $manager)
    {
        self::$manager = $manager;
    }

    /**
     * @return \Zend\View\HelperPluginManager
     */
    public static function getManager()
    {
        return self::$manager;
    }    

    /**
     * Plugin Handler to get Zend View Helpers
     * It returns a callback to self and the view helper name as method to call using magic __callStatic method
     *
     * called when Smarty encounters an undefined tag during compilation
     * 
     * @param string                     $name        name of the undefined tag
     * @param string                     $type        tag type (e.g. Smarty::PLUGIN_FUNCTION, Smarty::PLUGIN_BLOCK, 
                                                      Smarty::PLUGIN_COMPILER, Smarty::PLUGIN_MODIFIER, Smarty::PLUGIN_MODIFIERCOMPILER)
     * @param Smarty_Internal_Template   $template    template object
     * @param string                     &$callback   returned function name 
     * @param string                     &$script     optional returned script filepath if function is external
     * @param bool                       &$cacheable  true by default, set to false if plugin is not cachable (Smarty >= 3.1.8)
     * @return bool                      true if successfull
     */
    public static function getHelper($name, $type, $template, &$callback, &$script, &$cacheable)
    {
        // special case for form->openTag
        if ($type == Smarty::PLUGIN_MODIFIER && $name == 'openTag') {
            self::getManager()->get('form');
            $callback = array(__CLASS__, 'openTag');
            return true;
        }
        
        if (self::getManager()->get($name)) {
            $callback = array(__CLASS__, $name);
            return true;
        }
        return false;
    }
    
    /**
     * Call a Zend View helper
     */
    public static function __callStatic($name, $args)
    {
        // special case for form->openTag
        if ($name == 'openTag') {
            $helper = self::getManager()->get('form');
            $output = call_user_func_array(array($helper, 'openTag'), $args);
            return $output;
        }
        
        $helper = self::getManager()->get($name);
        
        if (!$helper) {
            throw new \BadFunctionCallException(sprintf('could not find Zend View Helper ""%s"', $name));
        }
        
        // If second argument is a template, then we are dealing with a smarty function
        // the first argument then is an array with parameters
        if (isset($args[1]) && $args[1] instanceof Smarty_Internal_Template) {
            $params = $args[0];
            $smarty = $args[1];
        } else {
            $params = $args; 
        }
        
        $output = call_user_func_array(array($helper, '__invoke'), $params);

        // If an assign parameter is provided, then assign the output to that variable
        if (isset($params['assign']) && isset($smarty)) {
            $smarty->assign($params['assign'], $output);
            return;
        }

        // We expect a string as output
        // otherwise an assign variable should be provided
        if (!is_string($output)) {
            throw new \RuntimeException(sprintf('Expecting string when calling ""%s", but received "%s".',
                $name, 
                is_object($output) ? get_class($output) : gettype($output)
            ));
        }
        return $output;
    }
}