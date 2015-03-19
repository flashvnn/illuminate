<?php
/**
 * Created by PhpStorm.
 * User: thaohuynh
 * Date: 3/19/15
 * Time: 10:35
 */

namespace Drupal\illuminate_demo\Form;


use Drupal\Core\Form\FormBase;

class DemoForm extends FormBase{
  public function buildForm(array &$form, array &$form_state) {
    $form['name'] = array(
      '#type'          => 'textfield',
      '#required'      => TRUE,
      '#title'         => t('Name'),
      '#description'   => t('Input yourname'),
      '#default_value' => '',
    );

    $form['submit'] = array(
      '#type'  => 'submit',
      '#value' => t('Save'),
    );

    return $form;
  }

  public function validateForm(array &$form, array &$form_state) {
    // Validate your form here.
  }

  public function submitForm(array &$form, array &$form_state) {
    dpm($form_state['values'], "submit_form");
  }
}