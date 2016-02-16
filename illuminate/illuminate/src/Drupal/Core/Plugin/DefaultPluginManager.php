<?php
/**
 * Created by PhpStorm.
 * User: thaohuynh
 * Date: 3/21/15
 * Time: 10:57
 */

namespace Drupal\Core\Plugin;


class DefaultPluginManager implements PluginManagerInterface{
  protected $plugin_interface;
  protected $subdir;
  protected $definitions;

  /**
   * The cache key.
   *
   * @var string
   */
  protected $cacheKey;

  protected $base_module = "illuminate";

  /**
   * @param string $cacheKey
   */
  public function setCacheKey($cacheKey) {
    $this->cacheKey = $cacheKey;
  }

  public function __construct($subdir, $plugin_interface){
    $this->subdir = $subdir;
    $this->plugin_interface = $plugin_interface;
  }

  /**
   * Gets a specific plugin definition.
   *
   * @param string $plugin_id
   *   A plugin id.
   * @param bool $exception_on_invalid
   *   (optional) If TRUE, an invalid plugin ID will throw an exception.
   *
   * @return mixed
   *   A plugin definition, or NULL if the plugin ID is invalid and
   *   $exception_on_invalid is FALSE.
   *
   */
  public function getIncludes($plugin_id, $exception_on_invalid = TRUE) {
    $module_path = \Drupal::service('helper.path')->module($plugin_id);
    $subdir_path = realpath($module_path) . "/src/" . $this->subdir;
    if (\File::exists($subdir_path)) {
      foreach (\File::allFiles($subdir_path) as $file) {
        /** @var $file \SplFileInfo */
        include_once $file->getRealPath();
      }
    }
  }

  /**
   * Gets a specific plugin definition.
   *
   * @param string $plugin_id
   *   A plugin id.
   * @param bool $exception_on_invalid
   *   (optional) If TRUE, an invalid plugin ID will throw an exception.
   *
   * @return mixed
   *   A plugin definition, or NULL if the plugin ID is invalid and
   *   $exception_on_invalid is FALSE.
   *
   */
  public function getDefinition($plugin_id, $exception_on_invalid = TRUE) {
    $definitions = $this->getDefinitions();
    if (isset($definitions[$plugin_id])) {
      return $definitions[$plugin_id];
    }
    return FALSE;
  }

  /**
   * Gets the definition of all plugins for this type.
   *
   * @return mixed[]
   *   An array of plugin definitions (empty array if no definitions were
   *   found).
   */
  public function getDefinitions() {
    if ($this->definitions) {
      return $this->definitions;
    }
    if ($this->cacheKey) {
      $this->definitions = \Cache::rememberForever('illuminate_plugin_' . $this->cacheKey, function(){
        return $this->findDefinitions();
      });
    }else{
      $this->definitions = $this->findDefinitions();
    }

    return $this->definitions;
  }

  /**
   * Indicates if a specific plugin definition exists.
   *
   * @param string $plugin_id
   *   A plugin ID.
   *
   * @return bool
   *   TRUE if the definition exists, FALSE otherwise.
   */
  public function hasDefinition($plugin_id) {
    $definitions = $this->getDefinitions();

    return isset($definitions[$plugin_id]);
  }

  /**
   * Find all definitions with plugin.
   *
   * @return array
   */
  protected function findDefinitions() {
    $module_enabled = system_list('module_enabled');
    $modules = $this->fetch($module_enabled);
    foreach ($modules as $module) {
      $this->getIncludes($module, $this->subdir);
    }

    $info = array();
    $classes = $this->getInterfaceImplementations();
    foreach ($classes as $class) {
      $instance = new $class();
      if (method_exists($instance, 'id')) {
        $info[$instance->id()] = array(
          'class' => $class,
          'id' => $instance->id(),
        );
      }
    }

    return $info;
  }

  /**
   * Returns an array with the classes that implements the specified interface
   */
  protected function getInterfaceImplementations() {
    $interface_name = $this->plugin_interface;

    $classes = array();
    foreach (get_declared_classes() as $class_name) {
      $reflection_class = new \ReflectionClass($class_name);
      if (!$reflection_class->isAbstract()) {
        if ($reflection_class->implementsinterface($interface_name)) {
          $classes[$class_name] = $class_name;
        }
      }
    }
    return $classes;
  }

  protected function fetch($enabled_modules) {
    $modules = array();
    foreach ($enabled_modules as $name => $info) {
      if ($this->validateModule($name, $info->info)) {
        $modules[] = $name;
      }
    }
    return $modules;
  }

  /**
   * @param $name
   * @param $info
   */
  protected function validateModule($name, $info) {
    if (empty($info['dependencies'])) {
      return FALSE;
    }
    if (!in_array($this->base_module, $info['dependencies'])) {
      return FALSE;
    }

    return TRUE;
  }
}