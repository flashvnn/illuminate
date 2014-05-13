<?php namespace Drupal\ZM\Hook;


/**
* BlockInfo
*/
class BlockInfo {
  public function import() {
    $info = array();
    foreach (illuminate_modules('illuminate', 'blocks') as $module) {
      $info += $this->importResource($module);
    }

    return $info;
  }

  private function importResource($module) {
    $info = array();
    foreach (\YAML::load($module, 'blocks')->get('blocks') as $k => $block) {
      $cache = DRUPAL_CACHE_PER_ROLE;

      if (!empty($block['cache'])) {
        switch ($block['cache']) {
          case 'DRUPAL_NO_CACHE':
            $cache = DRUPAL_NO_CACHE;
            break;

          case 'DRUPAL_CACHE_GLOBAL':
            $cache =  DRUPAL_CACHE_GLOBAL ;
            break;

          case 'DRUPAL_CACHE_PER_PAGE':
            $cache =  DRUPAL_CACHE_PER_PAGE ;
            break;

          case 'DRUPAL_CACHE_PER_USER':
            $cache =  DRUPAL_CACHE_PER_USER ;
            break;

          case 'DRUPAL_CACHE_CUSTOM':
            $cache =  DRUPAL_CACHE_CUSTOM ;
            break;
        }
      }
      $delta = "{$module}|{$k}";
      $info[$delta] = array(
        'info'    => empty($block['info']) ? $k : $block['info'],
        'cache'   => $cache,
      );
    }

    return $info;
  }
}
