<?php
/**
 * Implements hook_block_info().
 */
function illuminate_block_info() {
  if(illuminate_load_instance()){
    return Drupal::service('block.manager')->info();
  }

  return array();
}

/**
 * Implements hook_block_view().
 */
function illuminate_block_view($delta = '') {
  if(illuminate_load_instance()) {
    return Drupal::service('block.manager')->view($delta);
  }

  return array();
}

/**
 * Implements hook_block_configure().
 */
function illuminate_block_configure($delta = '') {
  $form = array();
  if (!empty($delta)) {
    return Drupal::service('block.manager')->buildForm($delta);
  }
  return $form;
}


/**
 * Implements hook_block_save().
 */
function illuminate_block_save($delta = '', $edit = array()) {
  if (!empty($delta)) {
    return Drupal::service('block.manager')->blockSubmit($delta, $edit);
  }
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function illuminate_form_block_admin_configure_alter(&$form, &$form_state, $form_id) {
  if ($form['module']['#value'] == 'illuminate') {
    Drupal::service('block.manager')->blockValidate($form['delta']['#value'], $form, $form_state);
  }
}