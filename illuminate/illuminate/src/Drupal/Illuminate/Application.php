<?php

namespace Drupal\Illuminate;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\ProviderRepository;

class Application extends \Illuminate\Foundation\Application {

  /**
   * @inheritdoc
   */
  public function storagePath() {
    return $this->storagePath ?: drupal_realpath("public://storage");// $this->basePath.DIRECTORY_SEPARATOR.'storage';
  }

  /**
   * @inheritdoc
   */
  public function registerConfiguredProviders() {
    $manifestPath = $this->basePath().'/services.json';

    (new ProviderRepository($this, new Filesystem(), $manifestPath))
      ->load($this->config['app.providers']);
  }


}