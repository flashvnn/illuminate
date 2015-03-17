<?php
namespace Drupal\Helper\Path;

class Path {

  /**
   * Get module path with option file path in module.
   *
   * @param $name
   * @param null $file
   *
   * @return string
   */
  public function module($name, $file = NULL) {
    $path = drupal_get_path('module', $name);
    if ($file) {
      return $path . "/" . $file;
    }

    return $path;
  }

  /**
   * Get theme path with option file path in theme.
   *
   * @param $name
   * @param null $file
   *
   * @return string
   */
  public function theme($name, $file = NULL) {
    $path = drupal_get_path('theme', $name);
    if ($file) {
      return $path . "/" . $file;
    }

    return $path;
  }

  /**
   * Get real path with token supported.
   * Example:
   *   {module-name}/demo.css
   *   {theme-name}/mytheme.css
   *
   * @param $path
   *
   * @return mixed|string
   */
  public function realPath($file) {
    $file = trim($file);

    if (strpos($file, '{') === FALSE) {
      return $file;
    }

    if (strpos($file, '{theme}') !== FALSE) {
      $theme_default = $GLOBALS['conf']['theme_default'];
      $theme_path    = drupal_get_path('theme', $theme_default);
      $file          = str_replace('{theme}', $theme_path, $file);

      return $file;
    }

    $matches = array();
    $types   = array('module', 'theme', 'library');

    foreach ($types as $type) {
      $pattern = '/\{' . $type . '-(.+)\}/';

      preg_match($pattern, $file, $matches);

      if ($type == "library") {
        if (count($matches) > 1 && ($path = libraries_get_path($matches[1])) != '') {
          $file = str_replace($matches[0], $path, $file);

          return $file;
        }
      }
      else {
        if (count($matches) > 1 && ($path = drupal_get_path($type, $matches[1])) != '') {
          $file = str_replace($matches[0], $path, $file);

          return $file;
        }
      }
    }

    return $file;
  }
}