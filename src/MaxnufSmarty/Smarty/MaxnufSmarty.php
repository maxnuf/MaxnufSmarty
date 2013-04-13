<?php

namespace MaxnufSmarty\Smarty;

use Smarty;
use Zend\View\HelperPluginManager;

class MaxnufSmarty extends Smarty
{
    /**
     * @var \Zend\View\HelperPluginManager
     */
    protected $manager;

    public function __construct($options = array())
    {
        parent::__construct();

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
}
