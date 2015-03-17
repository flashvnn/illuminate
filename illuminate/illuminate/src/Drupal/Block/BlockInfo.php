<?php


namespace Drupal\Block;


use Drupal\Resource\ResourceInfo;

class BlockInfo extends ResourceInfo {
  public function import($config_file = "") {
    $config_file = "blocks";
    return parent::import($config_file);
  }

  protected function importResource($module, $config_file) {
    $blocks = parent::importResource($module, $config_file);
    $info = array();
    foreach ($blocks as $k => $block) {
      $cache = DRUPAL_CACHE_PER_ROLE;
      if (!empty($block['cache'])) {
        switch ($block['cache']) {
          case 'DRUPAL_NO_CACHE':
            $cache = DRUPAL_NO_CACHE;
            break;
          case 'DRUPAL_CACHE_GLOBAL':
            $cache = DRUPAL_CACHE_GLOBAL ;
            break;
          case 'DRUPAL_CACHE_PER_PAGE':
            $cache = DRUPAL_CACHE_PER_PAGE ;
            break;
          case 'DRUPAL_CACHE_PER_USER':
            $cache = DRUPAL_CACHE_PER_USER ;
            break;
          case 'DRUPAL_CACHE_CUSTOM':
            $cache = DRUPAL_CACHE_CUSTOM ;
            break;
        }
      }
      $delta = "{$module}|{$k}";
      $info[$delta] = array(
        'info' => empty($block['info']) ? $k : $block['info'],
        'cache' => $cache,
      );
    }
    return $info;
  }


}