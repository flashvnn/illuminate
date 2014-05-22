<?php namespace Drupal\illuminate_example\Example;

class DemoController extends \Controller {

  /**
   * Show the profile for the given user.
   */
  public function showProfile($id) {
      // Tinh toan
      return \ViewRender::make('demo', array("name" => "HKT-" . $id));
  }
}
