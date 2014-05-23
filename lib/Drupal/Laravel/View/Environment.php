<?php namespace Drupal\Laravel\View;

use Illuminate\View\Environment as BaseEnvironment;

class Environment extends BaseEnvironment {

  /**
   * Get the evaluated view contents for the given view.
   *
   * @param  string  $view
   * @param  array   $data
   * @param  array   $mergeData
   * @return \Illuminate\View\View
   */
  public function make($view, $data = array(), $mergeData = array())
  {
    if (is_file($view)) {
      $view_info = pathinfo($view);
      $namespace = str_replace('/', '_', $view_info['dirname']);
      $this->addNamespace($namespace, $view_info['dirname']);
      if ($extension = $this->getExtension($view)){
        $path = $view;
        $view = str_replace($extension, '', $view_info['filename']);
      }
    } else {
      $path = $this->finder->find($view);
    }

    $data = array_merge($mergeData, $this->parseData($data));

    $this->callCreator($view = new \Illuminate\View\View($this, $this->getEngineFromPath($path), $view, $path, $data));

    return $view;
  }

}
