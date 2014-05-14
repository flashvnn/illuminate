<?php namespace Drupal\Laravel\Assets;


/**
* Assets
*/
class DrupalAssets {

  private function getRealPath($path) {
    return \Str::contains($path, "{") ? illuminate_realpath($path) : $path;
  }

  /**
   * Add JS file(s) to site.
   *
   * @param mixin $path
   *   File(s) add website.
   * @param array  $options
   */
  public function addJS($path, $options = array()) {
    $options = $options + array('scope' => 'footer');
    foreach ((array)$path as $item) {
      $item = $this->getRealPath($item);
      drupal_add_js($item, $options);
    }
  }

  /**
   * Add inline javascript.
   *
   * @param string $data
   */
  public function addJSInline($data) {
    drupal_add_js($data, 'inline');
  }

  /**
   * Add CSS file(s) to site.
   *
   * @param mixin $path
   *   File(s) add website.
   * @param array  $options
   */
  public function addCSS($path, $options = array()) {
    $options = $options + array('scope' => 'footer');
    foreach ((array) $path as $item) {
      $item = $this->getRealPath($item);
      drupal_add_css($item, $options);
    }
  }

  /**
   * Add inline css.
   *
   * @param string $data
   */
  public function addCSSInline($data) {
    drupal_add_css($data, 'inline');
  }
}
