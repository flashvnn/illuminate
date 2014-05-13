<?php namespace Drupal\ZM\Hook;

class BlockView{
  private $module;
  private $key;

  public function __construct($delta) {
    list($module, $key) = explode('|', $delta);
    $this->module = $module;
    $this->key = $key;
  }

  public function view(){
    $blocks = \YAML::load($this->module, 'blocks')->get('blocks');
    if (!isset($blocks[$this->key])) {
      throw new \Exception("Invalid block: {$this->module}:{$this->key}");
    }
    $info = $blocks[$this->key];
    $block = array();
    foreach (array('subject', 'content') as $k) {
      try {
        $block[$k] = app('contentrender')->render($info[$k]);
      }
      catch (\Exception $e) {
        $block[$k] = $e->getMessage();;
      }
    }

    return $block;
  }
}
