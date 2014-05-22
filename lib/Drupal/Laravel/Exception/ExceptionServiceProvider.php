<?php namespace Drupal\Laravel\Exception;

use Illuminate\Exception\ExceptionServiceProvider as BaseExceptionServiceProvider;

class ExceptionServiceProvider extends BaseExceptionServiceProvider {

  /**
   * Overide Get the Whoops custom resource path.
   *
   * @return string
   */
  protected function getResourcePath()
  {
    $base = composer_manager_vendor_dir();

    return $base.'/illuminate/exception/Illuminate/Exception/resources';
  }
}
