<?php
/**
 * DIC configuration
 */

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $s = $c->get('settings')['renderer'];
    $smarty = new Smarty;
    $smarty->setTemplateDir($s['template_dir']);
    $smarty->setDebugging($s['debugging']);
    $smarty->setCompileDir($s['compile_dir']);
    $smarty->setConfigDir($s['config_dir']);
    $smarty->setCacheDir($s['cache_dir']);

    $smarty->caching = $s['caching'];
    $smarty->cache_lifetime = $s['cache_lifetime'];
    
    return $smarty;
};
