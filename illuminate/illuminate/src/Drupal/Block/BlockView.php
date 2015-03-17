<?php namespace Drupal\Block;

use Drupal\Resource\ResourceInfo;

class BlockView extends ResourceInfo{
  private $module;
  private $key;

  public function __construct($delta) {
    list($module, $key) = explode('|', $delta);
    $this->module = $module;
    $this->key = $key;
  }

  /**
   * Build content for Block.
   *
   * @return array
   * @throws \Exception
   */
  public function view(){
    $blocks = $this->importResource($this->module, "blocks");
    if (!isset($blocks[$this->key])) {
      throw new \Exception("Invalid block: {$this->module}:{$this->key}");
    }
    $info             = $blocks[$this->key];
    $block            = array();
    $block['subject'] = array_get($info, 'subject', "");
    try {
      $block['content'] = \Drupal::service('contentRender')
                                 ->render(array('content' => $info['content']));
    } catch (\Exception $e) {
      $block['content'] = $e->getMessage();;
    }

    return $block;
  }
}
