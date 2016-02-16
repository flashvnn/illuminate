<?php
/**
 * Implements hook_field_formatter_info().
 */
function formatter_field_formatter_info() {
  $return = array();

  $return['formatter_my_formatter'] = array(
    'label'       => t('Formatter: My formatter'),
    'field types' => array('text', 'text_long', 'text_with_summary'),
    // 'settings' => array('my_key_1' => 'default value'),
  );

  return $return;
}

// hook_field_formatter_settings_form

// hook_field_formatter_settings_summary

// hook_field_formatter_prepare_view

// hook_field_formatter_view