<?php

/**
 * App configuration for Laravel module.
 * You can remove some options alias if you don't use.
 */



return array(
  'locale' => 'en',
  'fallback_locale' => 'en',
  'key' => 'rIJdQkSmzQwYHlOJ7PlOYeB0oCjX6i3q',
  /*
  |--------------------------------------------------------------------------
  | Autoloaded Service Providers
  |--------------------------------------------------------------------------
  |
  | The service providers listed here will be automatically loaded on the
  | request to your application. Feel free to add your own services to
  | this array to grant expanded functionality to your applications.
  |
  */
  'providers' => array(
    'Illuminate\Events\EventServiceProvider',
    'Drupal\Laravel\Cache\CacheServiceProvider', //used with Cache alias.
    'Drupal\Laravel\CookieExtraServiceProvider',
    'Illuminate\Filesystem\FilesystemServiceProvider', //used with File alias.
    'Illuminate\Database\DatabaseServiceProvider',// used with DB alias
    'Illuminate\Encryption\EncryptionServiceProvider',
    'Illuminate\Translation\TranslationServiceProvider',//used with Request, Cookie alias.
    'Illuminate\Validation\ValidationServiceProvider',//used with Validator alias.
    'Drupal\Laravel\Config\DrupalConfigServiceProvider',
    'Drupal\Laravel\ContentRender\ContentRenderServiceProvider',//used with View alias.
    'Drupal\Laravel\YAML\YAMLServiceProvider',//used with View alias.
    'Drupal\Laravel\ViewServiceProvider',//used with View alias.
  ),
  'aliases' => array(
     // Helper classes (option)
     'Arrays'      => 'Underscore\Types\Arrays',
     'Functions'   => 'Underscore\Types\Functions',
     'Object'      => 'Underscore\Types\Object',
     'String'      => 'Underscore\Types\String',
     'Parse'       => 'Underscore\Parse',
     'Carbon'      => 'Carbon\Carbon',
     'Str'         => 'Illuminate\Support\Str',
     'ClassLoader' => 'Illuminate\Support\ClassLoader',

     'App'         => 'Illuminate\Support\Facades\App',
     'Event'       => 'Illuminate\Support\Facades\Event',
     'Cache'       => 'Illuminate\Support\Facades\Cache',
     'Config'      => 'Illuminate\Support\Facades\Config',
     'DrupalConfig'=> 'Drupal\Laravel\Config\Facades\DrupalConfig',
     'File'        => 'Illuminate\Support\Facades\File',
     'DB'          => 'Illuminate\Support\Facades\DB',
     'Eloquent'    => 'Illuminate\Database\Eloquent\Model',
     'Schema'      => 'Illuminate\Support\Facades\Schema',
     'Request'     => 'Illuminate\Support\Facades\Request',
     'Cookie'      => 'Illuminate\Support\Facades\Cookie',
     'Crypt'       => 'Illuminate\Support\Facades\Crypt',
     'Input'       => 'Illuminate\Support\Facades\Input',
     /*'Response'    => 'Illuminate\Support\Facades\Response',*/
     'Validator'   => 'Illuminate\Support\Facades\Validator',
     'ViewRender'  => 'Illuminate\Support\Facades\View',
     'YAML'        => 'Drupal\Laravel\YAML\Facades\YAML',
  ),
);
