<?php

use Zend\Hydrator\HydratorPluginManager;
use Zend\Stdlib\ArrayUtils;

$config = [
    // Retrieve list of modules used in this application.
    'modules'                  => require __DIR__ . '/modules.config.php',

    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options'  => [
        // This should be an array of paths in which modules reside.
        // If a string key is provided, the listener will consider that a module
        // namespace, the value of that key the specific path to that module's
        // Module class.
        'module_paths'             => [
            './module',
            './vendor',
        ],

        // An array of paths from which to glob configuration files after
        // modules are loaded. These effectively override configuration
        // provided by modules themselves. Paths may use GLOB_BRACE notation.
        'config_glob_paths'        => [
            realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php',
        ],

        // Whether or not to enable a configuration cache.
        // If enabled, the merged configuration will be cached and used in
        // subsequent requests.
        'config_cache_enabled'     => true,

        // The key used to create the configuration cache file name.
        'config_cache_key'         => 'application.config.cache',

        // Whether or not to enable a module class map cache.
        // If enabled, creates a module class map cache which will be used
        // by in future requests, to reduce the autoloading process.
        'module_map_cache_enabled' => true,

        // The key used to create the class map cache file name.
        'module_map_cache_key'     => 'application.module.cache',

        // The path in which to cache merged configuration.
        'cache_dir'                => 'data/cache/',

        // Whether or not to enable modules dependency checking.
        // Enabled by default, prevents usage of modules that depend on other modules
        // that weren't loaded.
        // 'check_dependencies' => true,
    ],
];

$localConfigFile = __DIR__ . '/application.config.local.php';
if (is_readable($localConfigFile)) {
    $config = ArrayUtils::merge($config, include $localConfigFile);
}

return $config;