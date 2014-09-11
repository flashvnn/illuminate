<?php

/**
 * @file
 * Contains Drupal.
 */

use Illuminate\Container\Container;
use Drupal\Laravel\Application;
use Drupal\Component\Utility\Settings;

class Drupal {

  /**
   * The currently active container object.
   *
   * @var \Drupal\Laravel\Application
   */
  protected static $container;

  /**
   * Sets a new global container.
   *
   * @param \Illuminate\Container\Container $container
   *   A new container instance to replace the current. NULL may be passed by
   *   testing frameworks to ensure that the global state of a previous
   *   environment does not leak into a test.
   */
  public static function setContainer($container = NULL) {
    static::$container = $container;
    $settings = array();

    new Settings($settings);
  }

  /**
   * Returns the currently active global container.
   *
   * @deprecated This method is only useful for the testing environment. It
   * should not be used otherwise.
   *
   * @return \Illuminate\Container\Container
   */
  public static function getContainer() {
    return static::$container;
  }

  /**
   * Retrieves a service from the container.
   *
   * Use this method if the desired service is not one of those with a dedicated
   * accessor method below. If it is listed below, those methods are preferred
   * as they can return useful type hints.
   *
   * @param string $id
   *   The ID of the service to retrieve.
   * @return mixed
   *   The specified service.
   */
  public static function service($id) {
    return static::$container->get($id);
  }

  /**
   * Indicates if a service is defined in the container.
   *
   * @param string $id
   *   The ID of the service to check.
   *
   * @return bool
   *   TRUE if the specified service exists, FALSE otherwise.
   */
  public static function hasService($id) {
    return static::$container && static::$container->offsetExists($id);
  }

  /**
   * Retrieves the currently active request object.
   *
   * Note: The use of this wrapper in particular is especially discouraged. Most
   * code should not need to access the request directly.  Doing so means it
   * will only function when handling an HTTP request, and will require special
   * modification or wrapping when run from a command line tool, from certain
   * queue processors, or from automated tests.
   *
   * If code must access the request, it is considerably better to register
   * an object with the Service Container and give it a setRequest() method
   * that is configured to run when the service is created.  That way, the
   * correct request object can always be provided by the container and the
   * service can still be unit tested.
   *
   * If this method must be used, never save the request object that is
   * returned.  Doing so may lead to inconsistencies as the request object is
   * volatile and may change at various times, such as during a subrequest.
   *
   * @return \Symfony\Component\HttpFoundation\Request
   *   The currently active request object.
   */
  public static function request() {
    return static::$container->get('request');
  }

  /**
   * Gets the current active user.
   *
   * @return \Drupal\Core\Session\AccountProxyInterface
   */
  public static function currentUser() {
    return $_GLOBAL['user'];
  }

  /**
   * Retrieves a configuration object.
   *
   * This is the main entry point to the configuration API. Calling
   * @code \Drupal::config('book.admin') @endcode will return a configuration
   * object in which the book module can store its administrative settings.
   *
   * @param string $name
   *   The name of the configuration object to retrieve. The name corresponds to
   *   a configuration file. For @code \Drupal::config('book.admin') @endcode, the config
   *   object returned will contain the contents of book.admin configuration file.
   *
   * @return \Drupal\Core\Config\Config
   *   A configuration object.
   */
  public static function config($name) {
    return static::$container['config'][$name];
  }

  /**
   * Retrieves the configuration factory.
   *
   * This is mostly used to change the override settings on the configuration
   * factory. For example, changing the language, or turning all overrides on
   * or off.
   *
   * @return \Drupal\Core\Config\ConfigFactoryInterface
   *   The configuration factory service.
   */
  public static function configFactory() {
    // TODO: implement configFactory.
    return FALSE;
    //return static::$container->get('config.factory');
  }

  /**
   * Returns a queue for the given queue name.
   *
   * The following values can be set in your settings.php file's $settings
   * array to define which services are used for queues:
   * - queue_reliable_service_$name: The container service to use for the
   *   reliable queue $name.
   * - queue_service_$name: The container service to use for the
   *   queue $name.
   * - queue_default: The container service to use by default for queues
   *   without overrides. This defaults to 'queue.database'.
   *
   * @param string $name
   *   The name of the queue to work with.
   * @param bool $reliable
   *   (optional) TRUE if the ordering of items and guaranteeing every item
   *   executes at least once is important, FALSE if scalability is the main
   *   concern. Defaults to FALSE.
   *
   * @return \Drupal\Core\Queue\QueueInterface
   *   The queue object for a given name.
   */
  public static function queue($name, $reliable = FALSE) {
    // TODO: implement queue.
    return FALSE;
    //return static::$container->get('queue')->get($name, $reliable);
  }

  /**
   * Returns the default http client.
   *
   * @return \GuzzleHttp\ClientInterface
   *   A guzzle http client instance.
   */
  public static function httpClient() {
    return static::$container->get('http_client');
  }

  /**
   * Returns the current primary database.
   *
   * @return \Drupal\Core\Database\Connection
   *   The current active database's master connection.
   */
  public static function database() {
    return static::$container->get('database');
  }

  /**
   * Returns the requested cache bin.
   */
  public static function cache($bin = 'default') {
    return static::$container->get('cache');
  }

  /**
   * Returns an expirable key value store collection.
   *
   * @param string $collection
   *   The name of the collection holding key and value pairs.
   *
   * @return \Drupal\Core\KeyValueStore\KeyValueStoreExpirableInterface
   *   An expirable key value store collection.
   */
  public static function keyValueExpirable($collection) {
    return static::$container->get('keyvalue.expirable')->get($collection);
  }

  /**
   * Returns a key/value storage collection.
   *
   * @param string $collection
   *   Name of the key/value collection to return.
   *
   * @return \Drupal\Core\KeyValueStore\KeyValueStoreInterface
   */
  public static function keyValue($collection) {
    return static::$container->get('keyvalue')->get($collection);
  }

  public static function url($route_name, $route_parameters = array(), $options = array()) {
    $options['query'] = $route_parameters;

    return url($route_name, $options);
  }

  public static function l($text, $route_name, array $parameters = array(), array $options = array()) {
    $options['query'] = $route_parameters;

    return l($text, $route_name, $options);
  }

  /**
   * Call a controller with action and params
   * @param  string $controller_action
   *   Controller class name with action.
   *
   * @param  mix $params
   *
   * @return mix
   */
  public static function controller($controller_action, $params = NULL) {
    list($controller, $action) = explode(":", $controller_action);
    return app($controller)->{$action}($params);
  }

}
