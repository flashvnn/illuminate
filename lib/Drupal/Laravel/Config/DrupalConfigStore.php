<?php namespace Drupal\Laravel\Config;

use Illuminate\Database\Connection;

class DrupalConfigStore {

  /**
   * The database connection instance.
   *
   * @var \Illuminate\Database\Connection
   */
  protected $connection;

  /**
   * The name of the cache table.
   *
   * @var string
   */
  protected $table;

  /**
   * A string that should be prepended to keys.
   *
   * @var string
   */
  protected $collection;

  /**
   * Create a new database store.
   *
   * @param  \Illuminate\Database\Connection  $connection
   * @param  string  $table
   * @return void
   */
  public function __construct(Connection $connection, $table)
  {
    $this->table = $table;
    $this->connection = $connection;
  }

  /**
   * Retrieve an item from the collection by key.
   *
   * @param  string  $collection
   * @param  string  $key
   *
   * @return mixed
   */
  public function get($collection, $key)
  {
    $cache = $this->table()->where('collection', '=', $collection)->where('key', '=', $key)->first();

    if ( ! is_null($cache))
    {
      if (is_array($cache)) $cache = (object) $cache;

      if (time() >= $cache->expiration)
      {
        return $this->forget($collection, $key);
      }

      return json_decode($cache->value);
    }
  }

  /**
   * Retrieve all items from the collection.
   *
   * @param  string  $collection
   *
   * @return mixed
   */
  public function all($collection) {
    $collection = $this->table()->where('collection', '=', $collection)->get();
    $ret = NULL;
    foreach ($collection as $key => $cache) {
      if (is_array($cache)) $cache = (object) $cache;
      if (time() >= $cache->expiration)
      {
        $this->forget($collection, $key);
      }else{
        $ret[$cache->key] = json_decode($cache->value);
      }
    }

    return $ret;
  }

  /**
   * Store an item in the cache for a given number of minutes.
   *
   * @param  string  $key
   * @param  mixed   $value
   * @param  int     $minutes
   * @return void
   */
  public function put($collection, $key, $value, $minutes = 5256000)
  {
    $value = json_encode($value);
    $expiration = $this->getTime() + ($minutes * 60);

    try
    {
      $this->table()->insert(compact('collection', 'key', 'value', 'expiration'));
    }
    catch (\Exception $e)
    {
      $this->table()->where('collection', '=', $collection)->where('key', '=', $key)->update(compact('value', 'expiration'));
    }
  }

  /**
   * Store an item in the cache indefinitely.
   *
   * @param  string  $key
   * @param  mixed   $value
   * @return void
   */
  public function forever($collection, $key, $value)
  {
    return $this->put($collection, $key, $value, 5256000);
  }

  /**
   * Remove an item from the cache.
   *
   * @param  string  $key
   * @return void
   */
  public function forget($collection, $key)
  {
    $this->table()
             ->where('collection', '=', $collection)
             ->where('key', '=', $key)
             ->delete();
  }

  /**
   * Remove all items of collection.
   *
   * @return void
   */
  public function flush($collection)
  {
    $this->table()
             ->where('collection', '=', $collection)
             ->delete();
  }

  /**
   * Get a query builder for the cache table.
   *
   * @return \Illuminate\Database\Query\Builder
   */
  protected function table()
  {
    return $this->connection->table($this->table);
  }

  /**
   * Get the underlying database connection.
   *
   * @return \Illuminate\Database\Connection
   */
  public function getConnection()
  {
    return $this->connection;
  }

  /**
   * Get the current system time.
   *
   * @return int
   */
  protected function getTime()
  {
    return time();
  }
}
