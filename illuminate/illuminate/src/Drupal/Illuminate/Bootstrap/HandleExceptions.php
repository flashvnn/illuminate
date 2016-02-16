<?php
namespace Drupal\Illuminate\Bootstrap;


class HandleExceptions extends \Illuminate\Foundation\Bootstrap\HandleExceptions {

  /**
   * Override handleError, remove E_NOTICE
   *
   * @inheritdoc
   */
  public function handleError($level, $message, $file = '', $line = 0, $context = array()) {
    // call Drupal handle Error:
    // @see _drupal_error_handler_real
    $error_level = $level;
    if ($error_level & error_reporting()) {
      $types = drupal_error_levels();
      list($severity_msg, $severity_level) = $types[$error_level];
      $caller = _drupal_get_last_caller(debug_backtrace());

      if (!function_exists('filter_xss_admin')) {
        require_once DRUPAL_ROOT . '/includes/common.inc';
      }

      // We treat recoverable errors as fatal.
      _drupal_log_error(array(
        '%type' => isset($types[$error_level]) ? $severity_msg : 'Unknown error',
        // The standard PHP error handler considers that the error messages
        // are HTML. We mimick this behavior here.
        '!message' => filter_xss_admin($message),
        '%function' => $caller['function'],
        '%file' => $caller['file'],
        '%line' => $caller['line'],
        'severity_level' => $severity_level,
      ), $error_level == E_RECOVERABLE_ERROR);
    }

    if ($level != E_NOTICE) {
      parent::handleError($level, $message, $file, $line, $context);
    }
  }
}