<?php namespace Drupal\Laravel\Cookie;
use Drupal\Laravel\CookieExtra;
use Illuminate\Support\ServiceProvider;

class CookieServiceProvider extends ServiceProvider
{
  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bindShared('cookie', function($app)
    {
      $config = $app['config']['session'];

      return with(new CookieExtra)->setDefaultPathAndDomain($config['path'], $config['domain']);
    });
  }
}
