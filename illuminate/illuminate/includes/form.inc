<?php

/**
 * Form callback for menu.
 *
 * @param $class string The class implement form.
 *
 * @return mixed
 */
function illuminate_get_form($class){
  return Drupal::formBuilder()->getForm($class);
}


function illuminate_form($form, &$form_state, $form_handler) {
  $form_class = illuminate_get_form_handler($form_handler);
  $form_class->buildForm($form, $form_state);

  $form['illuminate'] = array(
    '#type' => 'hidden',
    '#value' => $form_handler,
  );

  $form['illuminate_fid'] = array(
    '#type' => 'hidden',
    '#value' => $form_class->getFormId(),
  );

  return $form;
}

/**
 * Implements hook_form_illuminate_form_alter().
 */
function illuminate_form_illuminate_form_alter(&$form, &$form_state, $form_id) {
  // allow alter based form id from Form class.
  drupal_alter('form_' . $form['illuminate_fid']['#value'], $form, $form_state, $form_id);
}
/**
 * @param $form
 * @param $form_state
 *
 * @return mixed
 * @throws \Exception
 */
function illuminate_form_validate(&$form, &$form_state) {
  if (!isset($form['illuminate'])) {
    throw new Exception('The submitted form used FormInterface did not specify a FormInterface form handler.');
  }
  $form_handler = illuminate_get_form_handler($form['illuminate']['#value']);

  return $form_handler->validateForm($form, $form_state);
}

/**
 * Form submit handler.
 *
 * @param array $form
 *   The form.
 * @param array $form_state
 *   The form state.
 *
 * @throws \Exception
 * @return mixed
 *   Result of the submitForm operation.
 */
function illuminate_form_submit($form, &$form_state) {
  if (!isset($form['illuminate'])) {
    throw new Exception('The submitted form used FormInterface did not specify a FormInterface form handler.');
  }

  $form_handler = illuminate_get_form_handler($form['illuminate']['#value']);

  return $form_handler->submitForm($form, $form_state);
}

/**
 * Construct a form handler.
 *
 * @param string $form_handler
 *   Name of the form handler.
 *
 * @throws Exception
 * @return \Drupal\Core\Form\FormInterface
 *   A Form object.
 */
function illuminate_get_form_handler($form_handler) {
  if (is_object($form_handler)) {
    if ($form_handler instanceof \Drupal\Core\Form\FormInterface) {
      return $form_handler;
    }else{
      throw new Exception('The provided form handler class was not an instance of FormInterface.');
    }
  }

  if (!class_exists($form_handler)) {
    throw new Exception('Could not find form handler class.');
  }

  $form_handler = new $form_handler();

  if (!$form_handler instanceof \Drupal\Core\Form\FormInterface) {
    throw new Exception('The provided form handler class was not an instance of FormInterface.');
  }

  return $form_handler;
}