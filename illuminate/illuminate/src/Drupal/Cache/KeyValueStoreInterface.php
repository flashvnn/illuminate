<?php
/**
 * Created by PhpStorm.
 * User: thaohuynh
 * Date: 3/22/15
 * Time: 22:33
 */

namespace Drupal\Cache;
use Illuminate\Contracts\Cache\Store;

interface KeyValueStoreInterface extends Store{

  /**
   * Alias of forever
   *
   * @param $key
   * @param $value
   * @param int $minutes
   *
   * @return mixed
   */
  public function set($key, $value, $minutes = 5256000);
}