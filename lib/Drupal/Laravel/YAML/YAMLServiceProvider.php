<?php namespace Drupal\Laravel\YAML;

use Illuminate\Support\ServiceProvider;

class YAMLServiceProvider extends ServiceProvider {
  /**
  * Register the service provider.
  *
  * @return void
  */
  public function register()
  {
    $this->app->bindShared('yaml', function($app) {
        return new \Drupal\Laravel\YAML\YAMLPraser();
    });
  }

  /**
  * Get the services provided by the provider.
  *
  * @return array
  */
  public function provides()
  {
    return array('yaml');
  }
}
