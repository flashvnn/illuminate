<?php
namespace Drupal\Core\Block;

use Drupal\Core\Plugin\DefaultPluginManager;

class BlockManager extends DefaultPluginManager{

  /**
   * Construct BlockManager.
   */
  public function __construct() {
    parent::__construct("Plugin/Block", 'Drupal\Core\Block\BlockPluginInterface');
    $this->setCacheKey('block_plugins');
  }

  /**
   * @inheritdoc
   */
  protected function findDefinitions() {
    $this->includeLaravelApp();

    return parent::findDefinitions();
  }

  /**
   * Build blocks info.
   *
   * @return array
   */
  public function info(){
    $blocks = array();
    $info = $this->getDefinitions();
    foreach ($info as $id => $item) {
      /** @var $instance \Drupal\Core\Block\BlockBase */
      if (!class_exists($item['class'])) {
        continue;
      }
      $instance                = new $item['class']();
      $settings                = $instance->defineSettings();
      $blocks[$instance->getPluginId()] = array(
        'info'  => isset($settings['info']) ? $settings['info'] : $instance->getPluginId(),
        'cache' => isset($settings['cache']) ? $settings['cache'] : DRUPAL_CACHE_PER_USER,
      );
    }

    return $blocks;
  }

  /**
   * Build block view.
   *
   * @param $delta
   *
   * @return array
   */
  public function view($delta) {
    $block = array();
    if (!empty($delta)) {
      $define = $this->getDefinition($delta);
      if ($define && class_exists($define['class'])) {
        /** @var $instance \Drupal\Core\Block\BlockBase */
        $instance         = new $define['class']();
        $block['subject'] = $instance->title();
        $block['content'] = \Drupal::service('content.render')
                                   ->render($instance->build());
      }
    }

    return $block;
  }

  /**
   * Add include laravel app folder for Block Plugin.
   */
  private function includeLaravelApp() {
    $subdir_path = app_path() . "/" . $this->subdir;
    if (\File::exists($subdir_path)) {
      foreach (\File::allFiles($subdir_path) as $file) {
        /** @var $file \SplFileInfo */
        include_once $file->getRealPath();
      }
    }
  }

  public function buildForm($delta) {
    $form = array();
    $define = $this->getDefinition($delta);
    if ($define && class_exists($define['class'])) {
      /** @var $instance \Drupal\Core\Block\BlockBase */
      $instance         = new $define['class']();
      $form = $instance->blockForm($form);
    }

    return $form;
  }

  public function blockValidate($delta, &$form, &$form_state) {
    $define = $this->getDefinition($delta);
    if ($define && class_exists($define['class'])) {
      /** @var $instance \Drupal\Core\Block\BlockBase */
      $instance         = new $define['class']();
      $instance->blockValidate($form, $form_state);
    }
  }

  public function blockSubmit($delta, $edit = array()){
    $define = $this->getDefinition($delta);
    if ($define && class_exists($define['class'])) {
      /** @var $instance \Drupal\Core\Block\BlockBase */
      $instance         = new $define['class']();
      $form = array();
      $form_state = array(
        'values' => $edit,
      );
      $instance->blockSubmit($form, $form_state);
    }
  }
}