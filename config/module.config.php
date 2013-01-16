<?php
return array(
    'maxnufsmarty' => array(
        'config' => array(
            'caching' => 0,
            'cache_lifetime' => 14400,
            'compile_dir' => './data/Smarty/smarty_compile/',
            'cache_dir' => './data/Smarty/smarty_cache/',
            'left_delimiter' => '{',
            'right_delimiter'=> '}',
        ),
        'suffix' => '.tpl',
        'plugins' => array(
        )
    ),
    'view_manager' => array(
        'strategies'   => array(
            'SmartyViewStrategy'
        ),
        'template_map' => array(
        ),
        'template_path_stack' => array(
            __DIR__ . '/../template',
        ),
    )
);
