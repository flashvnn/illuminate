<?php
namespace Drupal\Core\Block;


use Drupal\Component\Utility\NestedArray;

abstract class BlockBase implements  BlockPluginInterface{
  protected $configuration;
  protected $pluginId;

  /**
   * Default settings for Block.
   */
  public function defineSettings(){
      return array();
  }

  /**
   * Block title.
   *
   * @return string
   */
  public function title() {
    return "";
  }
  /**
   * Block label in Admin.
   */
  public function label() {
    return $this->getPluginId();
  }

  public function access($account, $return_as_object = FALSE) {
    return TRUE;
  }

  /**
   * Builds and returns the renderable array for this block plugin.
   *
   * @return array
   *   A renderable array representing the content of the block.
   */
  public function build() {
    return array();
  }

  public function setConfigurationValue($key, $value) {
    $this->configuration[$key] = $value;
  }

  public function blockValidate($form, &$form_state) {

  }

  public function blockSubmit($form, &$form_state) {

  }

  public function getMachineNameSuggestion() {
    return $this->getPluginId();
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }
  /**
   * Returns generic default configuration for block plugins.
   *
   * @return array
   *   An associative array with the default configuration.
   */
  protected function baseConfigurationDefaults() {
    return array(
      'id' => $this->getPluginId(),
      'label' => '',
      'cache' => DRUPAL_CACHE_PER_ROLE,
    );
  }
  /**
   * {@inheritdoc}
   */
  public function setConfiguration(array $configuration) {
    $this->configuration = NestedArray::mergeDeep(
      $this->baseConfigurationDefaults(),
      $this->defaultConfiguration(),
      $configuration
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    // TODO: Implement defaultConfiguration() method.
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginId() {
    if (!$this->pluginId) {
      $this->pluginId = str_replace(array('/', '\\'), '__', get_called_class());
    }

    return $this->pluginId;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, $form_state = array()) {
    return array();
  }
}