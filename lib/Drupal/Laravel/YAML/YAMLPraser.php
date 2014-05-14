<?php namespace Drupal\Laravel\YAML;

use Symfony\Component\Yaml\Yaml;
use Underscore\Types\Arrays;
use Illuminate\Filesystem\Filesystem;

class YAMLPraser{

  private $files = array();


  /**
   * Load yaml config from module.
   * @param  string  $module Name of module.
   * @param  boolean $pure   Return pure array of Arrays object.
   * @return array|Arrays          return array of Arrays object.
   */
  public function load($module, $config = 'config', $pure = FALSE){
    $path = drupal_get_path('module', $module) . "/config/{$config}.yml";

    return $this->loadFromFile($path, $pure);
  }

  public function loadFromFile($path, $pure = FALSE){
    if(file_exists($path) && !in_array($path, array_keys($this->files))){
      $data = \Symfony\Component\Yaml\Yaml::parse($path);
      if($pure){
        $this->files[$path] = $data;
      }else{
        $this->files[$path] = new Arrays($data);
      }
    }
    return isset($this->files[$path])? $this->files[$path] : array();
  }

  /**
  * Dumps a PHP array to module config.
  *
  * The dump method, when supplied with an array, will do its best
  * to convert the array into friendly YAML.
  *
  * @param string  $module                module name.
  * @param array   $array                  PHP array
  * @param integer $inline                 The level where you switch to inline YAML
  * @param integer $indent                 The amount of spaces to use for indentation of nested nodes.
  * @param Boolean $exceptionOnInvalidType true if an exception must be thrown on invalid types (a PHP resource or object), false otherwise
  * @param Boolean $objectSupport          true if object support is enabled, false otherwise
  *
  * @return string A YAML string representing the original PHP array
  *
  * @api
  */
  public function save($module, $array, $config = 'config', $inline = 2, $indent = 4, $exceptionOnInvalidType = false, $objectSupport = false){
    if(!File::isDirectory(drupal_get_path('module', $module) . "/config")){
      File::makeDirectory(drupal_get_path('module', $module) . "/config");
    }
    $path = drupal_get_path('module', $module) . "/config/{$config}.yml";
    return $this->saveToFile($path, $array, $inline, $indent, $exceptionOnInvalidType, $objectSupport);
  }

  public function saveToFile($path, $array, $inline = 2, $indent = 4, $exceptionOnInvalidType = false, $objectSupport = false){
    $string = \Symfony\Component\Yaml\Yaml::dump($array, $inline, $indent, $exceptionOnInvalidType, $objectSupport);
    return File::put($path, $string);
  }
}
