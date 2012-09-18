# MaxnufSmarty

MaxnufSmarty is a module that integrates the [Smarty](http://www.smarty.net) templating engine with
[Zend Framework 2](http://framework.zend.com).

## Installation

 1. Add `"maxnuf/maxnuf-smarty": "dev-master"` to your `composer.json` file and run `php composer.phar update`.
 2. Add `MaxnufSmarty` to your `config/application.config.php` file under the `modules` key.

## Configuration

configuration can be set via the `maxnufsmarty` configuration key.

   'maxnufsmarty' => array(
        'config' => array(
            'compile_dir' => __DIR__ . '/../../data/Smarty/smarty_compile/',
            'cache_dir' => __DIR__ . '/../../data/Smarty/smarty_cache/',
        ),
    ),
 
    
MaxnufSmarty integrates with the View Manager service and uses the same resolvers defined within that service. 
This allows you to define the template path stacks and maps within the view manager without having to set them again
when installing the module:

    'view_manager' => array(
        'template_path_stack'   => array(
            'application'              => __DIR__ . '/../templates',
        ),
        'template_map' => array(
            'layouts/layout'    => __DIR__ . '/../templates/layouts/layout.tpl',
            'index/index'       => __DIR__ . '/../templates/application/index/index.tpl',
        ),
    ), 

## Documentation


### View Helpers

Support is added for Zend View helpers as functions and modifiers

Where appropriate Zend View helpers can be invoked as function or modifiers

    {$form->get('title')|formLabel}
    {$form->get('title')|formInput}

Zend View helpers can also be invoked using `$this`

    {$this->formInput($form->get('title'))}

	{$this->headTitle()->setSeperator(' - ')}
