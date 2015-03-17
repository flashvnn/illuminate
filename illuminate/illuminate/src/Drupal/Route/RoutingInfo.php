<?php
/**
 * Created by PhpStorm.
 * User: thaohuynh
 * Date: 3/11/15
 * Time: 20:41
 */

namespace Drupal\Route;


use Drupal\Resource\ResourceInfo;

class RoutingInfo extends ResourceInfo{
  public function import($config_file = "") {
    $config_file = "routing";
    $infos = parent::import($config_file);
    $items = array();
    $replace_count = 1;
    foreach ($infos as $key => $item) {
      $items[str_replace("/", "", $item['path'], $replace_count)] = array(
        'title'            => array_get($item, 'defaults._title', ""),
        'access arguments' => array(array_get($item, "requirements._permission", "access content")),
        'page callback'    => 'illuminate_controller',
        'page arguments'   => array(array_get($item, 'defaults._controller')),
      );
    }

    return $items;
  }
}