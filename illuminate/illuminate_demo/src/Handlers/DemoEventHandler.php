<?php
/**
 * Created by PhpStorm.
 * User: thaohuynh
 * Date: 3/19/15
 * Time: 17:22
 */

namespace Drupal\illuminate_demo\Handlers;


use Illuminate\Events\Dispatcher;

class DemoEventHandler {
  /**
   * Handle user login events.
   */
  public function demo($event)
  {
    /** @var $event \Drupal\illuminate_demo\Events\DemoEvent */
    $event->setConfig("DemoEventHandler::handled data");
    return "DemoEventHandler::handled";
  }

  /**
   * Register the listeners for the subscriber.
   *
   * @param  Dispatcher  $events
   * @return array
   */
  public function subscribe($events)
  {
    $events->listen('Drupal\illuminate_demo\Events\DemoEvent', 'Drupal\illuminate_demo\Handlers\DemoEventHandler@demo');
  }
}