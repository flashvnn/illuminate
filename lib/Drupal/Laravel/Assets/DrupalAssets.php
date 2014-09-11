<?php namespace Drupal\Laravel\Assets;
/**
* Assets
*/
class DrupalAssets {

  private $type;
  private $name;

  private function getRealPath($path) {
    return \Str::contains($path, "{") ? illuminate_realpath($path) : $path;
  }

  private function addAsset($path, $options, $type = 'js') {
    $folder = drupal_get_path($this->type, $this->name);
    $fn = "drupal_add_" . $type;
    foreach ((array)$path as $item) {
      $fn($folder . "/". $item, $options);
    }
    $this->reset();
  }

  /**
   * Add JS file(s) to site.
   *
   * @param mixin $path
   *   File(s) add website.
   * @param array  $options
   */
  public function js($path, $options = array()) {
    $options = $options + array('scope' => 'footer');
    if ($this->hasType()) {
      $this->addAsset($path, $options, "js");
    } else {
      foreach ((array)$path as $item) {
        $item = $this->getRealPath($item);
        drupal_add_js($item, $options);
      }
    }
  }

  /**
   * Add inline javascript.
   *
   * @param string $data
   */
  public function jsinline($data) {
    drupal_add_js($data, 'inline');
  }

  /**
   * Add inline javascript.
   *
   * @param string $data
   */
  public function jssetting($data) {
    drupal_add_js($data, 'setting');
  }

  /**
   * Add CSS file(s) to site.
   *
   * @param mixin $path
   *   File(s) add website.
   * @param array  $options
   */
  public function css($path, $options = array()) {
    $options = $options + array('scope' => 'footer');
    if ($this->hasType()) {
      $this->addAsset($path, $options, "css");
    } else {
      foreach ((array) $path as $item) {
        $item = $this->getRealPath($item);
        drupal_add_css($item, $options);
      }
    }
  }

  /**
   * Add inline css.
   *
   * @param string $data
   */
  public function cssinline($data) {
    drupal_add_css($data, 'inline');
  }

  public function module($name) {
    $this->current("module", $name);

    return $this;
  }

  public function theme($name) {
    $this->current("theme", $name);

    return $this;
  }

  private function hasType() {
    return !is_null($this->type);
  }

  private function reset() {
    $this->type = NULL;
    $this->name = NULL;
  }

  private function current($type, $name) {
    $this->type = $type;
    $this->name = $name;
  }
}
