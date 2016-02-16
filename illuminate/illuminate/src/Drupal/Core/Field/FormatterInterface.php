<?php
namespace Drupal\Core\Field;

/**
 * Interface definition for field formatter plugins.
 *
 * @ingroup field_formatter
 */
interface FormatterInterface{

  /**
   * Returns a form to configure settings for the formatter.
   *
   * @return array
   *   The form elements for the formatter settings.
   */
  public function settingsForm(array &$form, array &$form_state);

  /**
   * Returns a short summary for the current formatter settings.
   *
   * If an empty result is returned, a UI can still be provided to display
   * a settings form in case the formatter has configurable settings.
   *
   * @return array()
   *   A short summary of the formatter settings.
   */
  public function settingsSummary();

  /**
   * Allows formatters to load information for field values being displayed.
   */
  public function prepareView(array $entities_items);

  /**
   * Builds a renderable array for a fully themed field.
   *
   * @return array
   *   A renderable array for a themed field with its label and all its values.
   */
  public function view(array $items);

  /**
   * Builds a renderable array for a field value.
   *
   * @param $items
   *   The field values to be rendered.
   *
   * @return array
   *   A renderable array for $items, as an array of child elements keyed by
   *   consecutive numeric indexes starting from 0.
   */
  public function viewElements(array $items);

  /**
   * Returns if the formatter can be used for the provided field.
   *
   * @param array $field_definition
   *   The field definition that should be checked.
   *
   * @return bool
   *   TRUE if the formatter can be used, FALSE otherwise.
   */
  public static function isApplicable(array $field_definition);

}

