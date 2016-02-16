<?php
namespace Drupal\illuminate_demo\Controller;

use Drupal\illuminate_demo\Events\DemoEvent;
use Illuminate\Routing\Controller;

class DemoController extends Controller{

  public function index(){
    \Drupal::keyValue()->set('mykey', array(1,2,3,4,5));
    dpm(\Drupal::keyValue()->get('mykey'));

    return "Welcome Laravel Controller";
  }

  public function json(){
    return \Response::json(array("data" => "value"));
  }

  public function form(){
    return \Drupal::formBuilder()->getForm('Drupal\illuminate_demo\Form\DemoForm');
  }

  public function event() {
    $response = \Event::fire("events.demo");
    if ($response) {
      dpm($response);
      return "Event handled";
    }

    return "No handler for events.demo";
  }

  public function eventClass() {
    $demoEvent = new DemoEvent();
    $response = \Event::fire($demoEvent);
    if ($response) {
      dpm($response);
      dpm($demoEvent->getConfig(), 'EventConfig');
      return "Event handled";
    }

    return "No handler for class DemoEvent";
  }
}