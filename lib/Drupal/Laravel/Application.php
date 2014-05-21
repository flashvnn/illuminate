<?php namespace Drupal\Laravel;

use Illuminate\Support\Facades\Facade;
use Illuminate\Container\Container;
use Illuminate\Config\Repository as Config;

class Application extends Container {
  /**
   * All of the registered service providers.
   *
   * @var array
   */
  protected $serviceProviders = array();

  /**
   * The names of the loaded service providers.
   *
   * @var array
   */
  protected $loadedProviders = array();


  /**
   * The deferred services and their providers.
   *
   * @var array
   */
  protected $deferredServices = array();


  public function __construct($path = ''){
    $this['path'] = $path;
    $this->registerConfig();
    $this->registerBaseBindings();
    $this->registerProviders();
    $this->registerFacedeApplication();
    $this->registerAliases();
  }

  /**
   * Resolve a service provider instance from the class name.
   *
   * @param  string  $provider
   * @return \Illuminate\Support\ServiceProvider
   */
  public function resolveProviderClass($provider)
  {
    return new $provider($this);
  }

  /**
   * Register a service provider with the application.
   *
   * @param  \Illuminate\Support\ServiceProvider|string  $provider
   * @param  array  $options
   * @param  bool   $force
   * @return \Illuminate\Support\ServiceProvider
   */
  public function register($provider, $options = array(), $force = false)
  {
    if ($registered = $this->getRegistered($provider) && ! $force)
                                     return $registered;

    // If the given "provider" is a string, we will resolve it, passing in the
    // application instance automatically for the developer. This is simply
    // a more convenient way of specifying your service provider classes.
    if (is_string($provider))
    {
      $provider = $this->resolveProviderClass($provider);
    }

    $provider->register();

    // Once we have registered the service we will iterate through the options
    // and set each of them on the application so they will be available on
    // the actual loading of the service objects and for developer usage.
    foreach ($options as $key => $value)
    {
      $this[$key] = $value;
    }

    $this->markAsRegistered($provider);

    // If the application has already booted, we will call this boot method on
    // the provider class so it has an opportunity to do its boot logic and
    // will be ready for any usage by the developer's application logics.
    if ($this->booted) $provider->boot();

    return $provider;
  }

  private function getConfigLoader(){
    return new \Illuminate\Config\FileLoader(new \Illuminate\Filesystem\Filesystem, $this['path'].'/config');
  }

  private function registerBaseBindings(){
    $this->instance('app', $this);
    $this['env'] = 'production';
    if(!empty($this['config']['app.aliases']['Response'])){
      $request = \Illuminate\Http\Request::createFromGlobals();
      $this->singleton('request', $request);
    }
    // Consider using UrlGenerator.
    /*
    $this->app->bindShared('url', function ($app)
    {
      return new \Illuminate\Routing\UrlGenerator();
    });*/
  }

  private function registerConfig($config = array()){
    $this->instance('config', $config = new Config(
      $this->getConfigLoader(), 'production'
    ));
  }

  /**
   * Register providers.
   */
  private function registerProviders(){
    $providers = $this['config']['app.providers'];
    $registered = array();
    foreach ($providers as $provider)
    {
        $instance = new $provider($this);
        $instance->register();
        $registered[] = $instance;
        $this->markAsRegistered($instance);
    }

    // Then boot them
    foreach ($registered as $instance)
    {
        $instance->boot();
    }
  }

  /**
   * Get the registered service provider instnace if it exists.
   *
   * @param  \Illuminate\Support\ServiceProvider|string  $provider
   * @return \Illuminate\Support\ServiceProvider|null
   */
  public function getRegistered($provider)
  {
    $name = is_string($provider) ? $provider : get_class($provider);

    if (array_key_exists($name, $this->loadedProviders))
    {
      return array_first($this->serviceProviders, function($key, $value) use ($name)
      {
        return get_class($value) == $name;
      });
    }
  }

  /**
   * Mark the given provider as registered.
   *
   * @param  \Illuminate\Support\ServiceProvider
   * @return void
   */
  protected function markAsRegistered($provider)
  {
    $this['events']->fire($class = get_class($provider), array($provider));

    $this->serviceProviders[] = $provider;
    $this->loadedProviders[$class] = true;
  }

  private function registerFacedeApplication(){
    Facade::setFacadeApplication($this);
  }

  /**
   * Register alias.
   *
   * @return void
   */
  private function registerAliases(){
    $aliases = $this['config']['app.aliases'];
    // Create alias
    foreach ($aliases as $key => $alias){
      class_alias($alias, $key);
    }
  }

  /**
   * Determine if the given service is a deferred service.
   *
   * @param  string  $service
   * @return bool
   */
  public function isDeferredService($service)
  {
    return isset($this->deferredServices[$service]);
  }

  /**
   * Load and boot all of the remaining deferred providers.
   *
   * @return void
   */
  public function loadDeferredProviders()
  {
    // We will simply spin through each of the deferred providers and register each
    // one and boot them if the application has booted. This should make each of
    // the remaining services available to this application for immediate use.
    foreach ($this->deferredServices as $service => $provider)
    {
      $this->loadDeferredProvider($service);
    }

    $this->deferredServices = array();
  }

  /**
   * Load the provider for a deferred service.
   *
   * @param  string  $service
   * @return void
   */
  protected function loadDeferredProvider($service)
  {
    $provider = $this->deferredServices[$service];

    // If the service provider has not already been loaded and registered we can
    // register it with the application and remove the service from this list
    // of deferred services, since it will already be loaded on subsequent.
    if ( ! isset($this->loadedProviders[$provider]))
    {
      $this->registerDeferredProvider($provider, $service);
    }
  }

  /**
   * Register a deffered provider and service.
   *
   * @param  string  $provider
   * @param  string  $service
   * @return void
   */
  public function registerDeferredProvider($provider, $service = null)
  {
    // Once the provider that provides the deferred service has been registered we
    // will remove it from our local list of the deferred services with related
    // providers so that this container does not try to resolve it out again.
    if ($service) unset($this->deferredServices[$service]);

    $this->register($instance = new $provider($this));

    if ( ! $this->booted)
    {
      $this->booting(function() use ($instance)
      {
        $instance->boot();
      });
    }
  }

  /**
   * Set the application's deferred services.
   *
   * @param  array  $services
   * @return void
   */
  public function setDeferredServices(array $services)
  {
    $this->deferredServices = $services;
  }

  /**
   * Resolve the given type from the container.
   *
   * (Overriding Container::make)
   *
   * @param  string  $abstract
   * @param  array   $parameters
   * @return mixed
   */
  public function make($abstract, $parameters = array())
  {
    $abstract = $this->getAlias($abstract);

    if (isset($this->deferredServices[$abstract]))
    {
      $this->loadDeferredProvider($abstract);
    }

    return parent::make($abstract, $parameters);
  }

}
