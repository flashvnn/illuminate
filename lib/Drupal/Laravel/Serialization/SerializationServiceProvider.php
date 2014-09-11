<?php namespace Drupal\Laravel\Serialization;

use Illuminate\Support\ServiceProvider;
use Drupal\Component\Serialization\PhpSerialize;
use Drupal\Component\Serialization\Yaml;
use Drupal\Component\Serialization\Json;
/**
* SerializationServiceProvider
*/
class SerializationServiceProvider extends ServiceProvider {
  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {

    $this->app->bindShared('serialization.phpserialize', function($app) {
      return new PhpSerialize();
    });

    $this->app->bindShared('serialization.yaml', function($app) {
      return new Yaml();
    });

    $this->app->bindShared('serialization.json', function($app) {
      return new Json();
    });
  }
}
