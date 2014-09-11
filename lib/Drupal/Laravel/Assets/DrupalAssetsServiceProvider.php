<?php namespace Drupal\Laravel\Assets;

use Illuminate\Support\ServiceProvider;

/**
* DrupalAssetsServiceProvider
*/
class DrupalAssetsServiceProvider extends ServiceProvider {

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
    $this->app->bindShared('drupalassets', function($app) {
      return new DrupalAssets();
    });

  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return array('drupalassets');
  }
}
