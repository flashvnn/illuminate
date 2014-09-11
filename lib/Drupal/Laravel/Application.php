<?php namespace Drupal\Laravel;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Drupal\Laravel\Exception\ExceptionServiceProvider;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Routing\RoutingServiceProvider;
use Illuminate\Config\Repository as Config;
use Illuminate\Support\Contracts\ResponsePreparerInterface;

class Application extends Container implements ResponsePreparerInterface {

  /**
   * Indicates if the application has "booted".
   *
   * @var bool
   */
  protected $booted = false;

  /**
   * The array of booting callbacks.
   *
   * @var array
   */
  protected $bootingCallbacks = array();

  /**
   * The array of booted callbacks.
   *
   * @var array
   */
  protected $bootedCallbacks = array();

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
    $this->registerBaseServiceProviders();
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

  /**
   * Determine if the application has booted.
   *
   * @return bool
   */
  public function isBooted()
  {
    return $this->booted;
  }

  /**
   * Boot the application's service providers.
   *
   * @return void
   */
  public function boot()
  {
    if ($this->booted) return;

    array_walk($this->serviceProviders, function($p) { $p->boot(); });

    $this->bootApplication();
  }

  /**
   * Boot the application and fire app callbacks.
   *
   * @return void
   */
  protected function bootApplication()
  {
    // Once the application has booted we will also fire some "booted" callbacks
    // for any listeners that need to do work after this initial booting gets
    // finished. This is useful when ordering the boot-up processes we run.
    $this->fireAppCallbacks($this->bootingCallbacks);

    $this->booted = true;

    $this->fireAppCallbacks($this->bootedCallbacks);
  }

  /**
   * Call the booting callbacks for the application.
   *
   * @return void
   */
  protected function fireAppCallbacks(array $callbacks)
  {
    foreach ($callbacks as $callback)
    {
      call_user_func($callback, $this);
    }
  }

  private function getConfigLoader(){
    return new \Illuminate\Config\FileLoader(new \Illuminate\Filesystem\Filesystem, $this['path'].'/config');
  }

  private function registerBaseBindings(){
    $this->instance('app', $this);
    $this['env'] = 'production';
    if(!empty($this['config']['app.aliases']['Response'])){
      $request = \Illuminate\Http\Request::createFromGlobals();
      $this->instance('request', $request);
    }
    $this->instance('Illuminate\Container\Container', $this);
  }

  /**
   * Register all of the base service providers.
   *
   * @return void
   */
  protected function registerBaseServiceProviders()
  {
    foreach (array('Event', 'Exception', 'Routing') as $name)
    {
      $this->{"register{$name}Provider"}();
    }
  }

  /**
   * Register the event service provider.
   *
   * @return void
   */
  protected function registerEventProvider()
  {
    $this->register(new EventServiceProvider($this));
  }

  /**
   * Register the routing service provider.
   *
   * @return void
   */
  protected function registerRoutingProvider()
  {
    $this->register(new RoutingServiceProvider($this));
  }

  /**
   * Determine if we are running in the console.
   *
   * @return bool
   */
  public function runningInConsole()
  {
    return php_sapi_name() == 'cli';
  }

  /**
   * Register the exception service provider.
   *
   * @return void
   */
  protected function registerExceptionProvider()
  {
    $this->register(new ExceptionServiceProvider($this));
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

  /**
   * Start the exception handling for the request.
   *
   * @return void
   */
  public function startExceptionHandling()
  {
    $this['exception']->register($this->environment());

    $this['exception']->setDebug($this['config']['app.debug']);
  }

  /**
   * Get or check the current application environment.
   *
   * @param  dynamic
   * @return string
   */
  public function environment()
  {
    if (count(func_get_args()) > 0)
    {
      return in_array($this['env'], func_get_args());
    }
    else
    {
      return $this['env'];
    }
  }

  /**
   * Register an application error handler.
   *
   * @param  Closure  $callback
   * @return void
   */
  public function error(Closure $callback)
  {
    $this['exception']->error($callback);
  }

  /**
   * Register an error handler at the bottom of the stack.
   *
   * @param  Closure  $callback
   * @return void
   */
  public function pushError(Closure $callback)
  {
    $this['exception']->pushError($callback);
  }

  /**
   * Register an error handler for fatal errors.
   *
   * @param  Closure  $callback
   * @return void
   */
  public function fatal(Closure $callback)
  {
    $this->error(function(FatalErrorException $e) use ($callback)
    {
      return call_user_func($callback, $e);
    });
  }

  /**
   * Register a 404 error handler.
   *
   * @param  Closure  $callback
   * @return void
   */
  public function missing(Closure $callback)
  {
    $this->error(function(NotFoundHttpException $e) use ($callback)
    {
      return call_user_func($callback, $e);
    });
  }

  /**
   * Prepare the given value as a Response object.
   *
   * @param  mixed  $value
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function prepareResponse($value)
  {
    if ( ! $value instanceof SymfonyResponse) $value = new Response($value);

    return $value->prepare($this['request']);
  }

  /**
   * Determine if the application is ready for responses.
   *
   * @return bool
   */
  public function readyForResponses()
  {
    return $this->booted;
  }

  /**
   * Fake function get provider by name.
   *
   * @param  string $provider
   *
   * @return mix
   */
  public function get($provider) {
    return $this->make($provider);
  }

}
