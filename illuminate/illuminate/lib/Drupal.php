<?php

class Drupal {
  /**
   * The currently active container object, or NULL if not initialized yet.
   *
   * @var \Illuminate\Container\Container
   */
  protected static $container;

  /**
   * Sets a new global container.
   *
   * @param \Illuminate\Contracts\Container\Container $container
   *   A new container instance to replace the current. NULL may be passed by
   *   testing frameworks to ensure that the global state of a previous
   *   environment does not leak into a test.
   */
  public static function setContainer(\Illuminate\Contracts\Container\Container $container = NULL) {
    static::$container = $container;
  }

  /**
   * Returns the currently active global container.
   *
   *
   * @return \Illuminate\Contracts\Container\Container|null
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
   *
   * @return mixed
   *   The specified service.
   */
  public static function service($id) {
    return static::$container->make($id);
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
   * Gets the current active user.
   *
   * @return object
   */
  public static function currentUser() {
    global $user;
    return $user;
  }

  /**
   * Retrieves the entity manager service.
   *
   * @return EntityFieldQuery
   *   The entity manager service.
   */
  public static function entityManager() {
    return static::$container->make('entity.manager');
  }

  /**
   * Returns the current primary database.
   *
   * @return Database
   *   The current active database's master connection.
   */
  public static function database() {
    return static::$container->make('database');
  }

  /**
   * Gererater URL.
   *
   * @param $route_name
   * @param array $route_parameters
   * @param array $options
   *
   * @return string
   */
  public static function url($route_name, $route_parameters = array(), $options = array()) {
    $options['query'] = $route_parameters;

    return url($route_name, $options);
  }

  /**
   * Gererater link.
   *
   * @param $text
   * @param $route_name
   * @param array $route_parameters
   * @param array $options
   *
   * @return string
   */
  public static function l($text, $route_name, array $route_parameters = array(), array $options = array()) {
    $options['query'] = $route_parameters;
    if ( ! isset( $options['html'] )) {
      $options['html'] = TRUE;
    }

    return l($text, $route_name, $options);
  }

  /**
   * Return themed image.
   *
   * @param $path
   * @param array $options
   *
   * @return string
   * @throws Exception
   */
  public static function image($path, $options = array(), $class = '') {
    return static::service('helper.image')
                 ->render($path, "", "", $class, $options);
  }

  /**
   * Return themed image style.
   *
   * @param $path
   * @param $style_name
   * @param array $options
   *
   * @return string
   * @throws Exception
   */
  public static function image_style($path, $style_name, $options = array()) {
    return static::service('helper.image')
                 ->renderStyle($path, $style_name, "", "", "", $options);
  }

  /**
   * Get real path.
   *
   * @param $path
   *
   * @return mixed|string
   */
  public static function real_path($path) {
    return static::service('helper.path')->realPath($path);
  }
}
