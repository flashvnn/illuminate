<?php
/**
 * Created by PhpStorm.
 * User: thaohuynh
 * Date: 3/23/15
 * Time: 21:52
 */

namespace Drupal\Core\Field;


use Drupal\Core\Plugin\DefaultPluginManager;

class FormatterPluginManager extends DefaultPluginManager{

  public function __construct() {
    parent::__construct('Plugin\Field\FieldFormatter', 'Drupal\Core\Field\FormatterInterface');

  }
}