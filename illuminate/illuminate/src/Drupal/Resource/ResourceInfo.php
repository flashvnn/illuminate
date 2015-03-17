<?php
namespace Drupal\Resource;


use Symfony\Component\Yaml\Yaml;

class ResourceInfo {

  public function import($config_file = "") {
    $info           = array();
    $base_module    = 'illuminate';
    $modules = illuminate_modules($base_module, $config_file);
    foreach ($modules as $module) {
      $info += $this->importResource($module, $config_file);
    }

    return $info;
  }

  /**
   * Import resource info from module yml config file.
   *
   * @param $module
   */
  protected function importResource($module, $config_file) {
    /** @var  $path \Drupal\Helper\Path\Path */
    $path = \Drupal::service('helper.path');
    $config = \File::get($path->module($module, $module . "." . $config_file . ".yml"));
    return Yaml::parse($config);
  }
}