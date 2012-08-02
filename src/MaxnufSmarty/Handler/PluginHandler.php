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
	 * It returns a callback to self and the view helper name as method to call using magic __call method
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
		if ($name == 'openTag') {
			$helper = self::getManager()->get('form');
			$output = call_user_func_array(array($helper, 'openTag'), $args);
		} else {
			$helper = self::getManager()->get($name);
			if (!$helper) {
				throw new \BadFunctionCallException(sprintf('could not find Zend View Helper `%s`', $name));
			}
			// if second argument is a template, then we are dealing with a smarty function
			// the first argument then is an array with parameters
			$params = (isset($args[1]) && $args[1] instanceof Smarty_Internal_Template) ? $args[0] : $args;
			$output = call_user_func_array(array($helper, '__invoke'), $params);
		}
		// TODO some view helpers return a class
		// At the moment we only expect a string
		if (!is_string($output)) {
			throw new \RuntimeException(sprintf('Expecting string when calling `%s`, but received `%s`',
				$name, 
				is_object($output) ? get_class($output) : gettype($output)
			));
		}
		return $output;
	}
}