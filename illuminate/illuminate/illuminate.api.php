<?php
/**
 * @file
 * Hooks provided by the illuminate.
 *
 */

/**
 *
 * This hook allow other module can add/register class and folder for
 * auto loading. Support psr-0 and psr-4.
 *
 * @param $loader
 */
function hook_illuminate_load_alter(&$loader) {
  /** @var $loader \Composer\Autoload\ClassLoader */

  // Override a function defined by the plugin.
  // Add psr-0
  $loader->add("MyNameSpace", drupal_get_path('module', 'mymodule') . "/src");

  // Add psr-4
  $loader->addPsr4("mynamespace", drupal_get_path("module", "mymodule" . "/lib"));

}