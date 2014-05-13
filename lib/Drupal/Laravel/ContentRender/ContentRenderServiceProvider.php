<?php namespace Drupal\Laravel\ContentRender;

use Illuminate\Support\ServiceProvider;

class ContentRenderServiceProvider extends ServiceProvider {
  /**
  * Register the service provider.
  *
  * @return void
  */
  public function register()
  {
    $this->app['contentrender'] = $this->app->share(function($app)
    {
      return new ContentRender();
    });
  }

  /**
  * Get the services provided by the provider.
  *
  * @return array
  */
  public function provides()
  {
    return array('contentrender');
  }
}
