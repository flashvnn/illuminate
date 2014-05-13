<?php namespace Drupal\Laravel\Cache;

use Illuminate\Cache\CacheManager;

class CacheManagerDrupal extends CacheManager{
  /**
   * Override DatabaseDriver.
   *
   * @return \Illuminate\Cache\DatabaseStore
   */
  protected function createDatabaseDriver()
  {
    $connection = $this->getDatabaseConnection();

    $encrypter = $this->app['encrypter'];

    // We allow the developer to specify which connection and table should be used
    // to store the cached items. We also need to grab a prefix in case a table
    // is being used by multiple applications although this is very unlikely.
    $table = $this->app['config']['cache.table'];

    $prefix = $this->getPrefix();

    return $this->repository(new DatabaseStoreDrupal($connection, $encrypter, $table, $prefix));
  }
}
