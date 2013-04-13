<?php

namespace MaxnufSmarty\Smarty\SysPlugins;

use Smarty_Internal_Resource_File;

/**
 * MaxnufSmarty Resource File
 *
 * Extends the implementation of the file system as resource for Smarty templates
 * Adds ZF2 view helpers as modifiers and functions
 *
 * @package MaxnufSmarty
 * @subpackage TemplateResources
 */
class MaxnufFileResource extends Smarty_Internal_Resource_File
{
    public $compiler_class = 'Smarty_Maxnuf_Smarty_Template_Compiler';
}
