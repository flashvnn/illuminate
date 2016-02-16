<?php
/**
 * Created by PhpStorm.
 * User: thaohuynh
 * Date: 3/19/15
 * Time: 16:59
 */

namespace Drupal\illuminate_demo\Events;


class DemoEvent{
  private $_config;

  /**
   * @return mixed
   */
  public function getConfig() {
    return $this->_config;
  }

  /**
   * @param mixed $config
   */
  public function setConfig($config) {
    $this->_config = $config;
  }
}