<?php
/**
 * Created by PhpStorm.
 * User: thaohuynh
 * Date: 3/22/15
 * Time: 20:43
 */

namespace Drupal\Cache;


use Illuminate\Cache\DatabaseStore;

class KeyValueStore extends DatabaseStore implements KeyValueStoreInterface{

  /**
   * Set key, value data.
   *
   * @param $key
   * @param $value
   * @param int $minutes
   */
  public function set($key, $value, $minutes = 5256000){
    $this->put($key, $value, $minutes);
  }

  /**
   * @inheritdoc
   */
  public function put($key, $value, $minutes) {
    $key = $this->prefix.$key;
    $value = json_encode($value);
    $expiration = $this->getTime() + ($minutes * 60);
    try
    {
      $this->table()->insert(compact('key', 'value', 'expiration'));
    }
    catch (\Exception $e)
    {
      $this->table()->where('key', '=', $key)->update(compact('value', 'expiration'));
    }
  }

  /**
   * @inheritdoc
   */
  public function get($key) {
    $prefixed = $this->prefix.$key;

    $cache = $this->table()->where('key', '=', $prefixed)->first();

    if ( ! is_null($cache))
    {
      if (is_array($cache)) $cache = (object) $cache;

      if (time() >= $cache->expiration)
      {
        $this->forget($key);

        return;
      }

      return json_decode($cache->value);
    }
  }


}