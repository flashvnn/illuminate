<?php
namespace Drupal\illuminate_demo\Controller;

use Illuminate\Routing\Controller;

class DemoController extends Controller{

  public function index(){
    return "Welcome Laravel Controller";
  }

  public function json(){
    return \Response::json(array("data" => "value"));
  }
}