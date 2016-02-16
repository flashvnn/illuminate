<?php
namespace Drupal\Core\Block;


use Drupal\Component\Plugin\ConfigurablePluginInterface;

interface BlockPluginInterface extends ConfigurablePluginInterface{
  /**
   * Returns the user-facing block label.
   *
   * @todo Provide other specific label-related methods in
   *   https://drupal.org/node/2025649.
   *
   * @return string
   *   The block label.
   */
  public function label();


  public function access($account, $return_as_object = FALSE);

  /**
   * Builds and returns the renderable array for this block plugin.
   *
   * @return array
   *   A renderable array representing the content of the block.
   *
   * @see \Drupal\block\BlockViewBuilder
   */
  public function build();

  public function setConfigurationValue($key, $value);

  public function blockForm($form, $form_state = array());

  public function blockValidate($form, &$form_state);

  public function blockSubmit($form, &$form_state);

  public function getMachineNameSuggestion();
}