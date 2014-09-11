<?php namespace Drupal\Laravel\KeyValue;

use Drupal\Core\KeyValueStore\KeyValueFactory;
use Drupal\Core\KeyValueStore\KeyValueDatabaseFactory;
use Illuminate\Support\ServiceProvider;
use Drupal\Component\Utility\Settings;
/**
* KeyValueServiceProvider
*/
class KeyValueServiceProvider extends ServiceProvider {

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
    $this->app->bindShared('keyvalue.database', function($app) {
      $connection = $app['db']->connection();
      $serializer = $app['serialization.phpserialize'];

      return new KeyValueDatabaseFactory($serializer, $connection);
    });


    $this->app->bindShared('keyvalue', function($app) {
      $settings = \Drupal\Component\Utility\Settings::getInstance();
      return new KeyValueFactory($app, $settings);
    });

  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return array('keyvalue');
  }

}
