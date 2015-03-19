<?php
/**
 * Created by PhpStorm.
 * User: thaohuynh
 * Date: 3/19/15
 * Time: 09:33
 */

namespace Drupal\Core\Form;


class FormBuilder implements FormBuilderInterface{

  /**
   * Determines the ID of a form.
   *
   * @param FormInterface|string $form_arg
   *   The value is identical to that of self::getForm()'s $form_arg argument.
   *
   * @return string
   *   The unique string identifying the desired form.
   */
  public function getFormId($form_arg) {
    // TODO: Implement getFormId() method.
  }

  /**
   * Gets a renderable form array.
   *
   * This function should be used instead of self::buildForm() when $form_state
   * is not needed (i.e., when initially rendering the form) and is often
   * used as a menu callback.
   *
   * @param FormInterface|string $form_arg
   */
  public function getForm($form_arg) {
    return drupal_get_form('illuminate_form', $form_arg);
  }
}