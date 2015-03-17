<?php

namespace Drupal\Illuminate;


class Application extends \Illuminate\Foundation\Application {

  /**
   * @inheritdoc
   */
  public function storagePath() {
    return $this->storagePath ?: drupal_realpath("public://storage");// $this->basePath.DIRECTORY_SEPARATOR.'storage';
  }

}