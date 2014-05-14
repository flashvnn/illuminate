<?php namespace Drupal\Laravel\Config;

use Illuminate\Support\ServiceProvider;
/**
* DrupalConfig
*/
class DrupalConfigServiceProvider extends ServiceProvider {

  /**
   * Indicates if loading of the provider is deferred.
   *
   * @var bool
   */
  protected $defer = true;

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bindShared('drupalconfig', function($app)
    {
      $connection = $this->getDatabaseConnection();
      $table = $this->app['config']['drupal.configtable'];

      return new DrupalConfigStore($connection, $table);
    });

  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return array('drupalconfig');
  }

  /**
   * Get the database connection for the database driver.
   *
   * @return \Illuminate\Database\Connection
   */
  protected function getDatabaseConnection()
  {
    $connection = $this->app['config']['cache.connection'];

    return $this->app['db']->connection($connection);
  }
}
