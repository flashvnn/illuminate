<?php


/**
 * Forget cache of illuminate.
 */
function illuminate_forget_cache() {
  if (class_exists('\Cache')) {
    \Cache::flush();
  }
}

/**
 * Implements hook_modules_enabled().
 */
function illuminate_modules_enabled($modules) {
  illuminate_forget_cache();
}

/**
 * Implements hook_modules_disabled().
 */
function illuminate_modules_disabled($modules) {
  illuminate_forget_cache();
}

/**
 * Implements hook_flush_caches().
 */
function illuminate_flush_caches() {
  illuminate_forget_cache();
  return array();
}