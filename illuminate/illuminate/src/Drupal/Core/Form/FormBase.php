<?php
/**
 * Created by PhpStorm.
 * User: thaohuynh
 * Date: 3/19/15
 * Time: 09:19
 */

namespace Drupal\Core\Form;


class FormBase implements FormInterface{

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return str_replace('\\', '__', get_class($this));
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param array $form_state
   *   An associative array containing the current state of the form.
   */
  public function buildForm(array &$form, array &$form_state) {
    // Do nothing in FormBase
  }

  /**
   * Form validation handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param array $form_state
   *   An associative array containing the current state of the form.
   */
  public function validateForm(array &$form, array &$form_state) {
    // Do nothing in FormBase
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param array $form_state
   *   An associative array containing the current state of the form.
   */
  public function submitForm(array &$form, array &$form_state) {
    // Do nothing in FormBase
  }
}