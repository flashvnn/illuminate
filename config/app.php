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
    'Illuminate\Filesystem\FilesystemServiceProvider',
    'Illuminate\Database\DatabaseServiceProvider',
    'Illuminate\Encryption\EncryptionServiceProvider',
    'Illuminate\Translation\TranslationServiceProvider',
    'Illuminate\Validation\ValidationServiceProvider',
    'Drupal\Laravel\Cache\CacheServiceProvider',
    'Drupal\Laravel\Cookie\CookieServiceProvider',
    'Drupal\Laravel\Config\DrupalConfigServiceProvider',
    'Drupal\Laravel\Assets\DrupalAssetsServiceProvider',
    'Drupal\Laravel\ContentRender\ContentRenderServiceProvider',
    'Drupal\Laravel\YAML\YAMLServiceProvider',
    'Drupal\Laravel\View\ViewServiceProvider',
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
     'DrupalAssets'=> 'Drupal\Laravel\Assets\Facades\DrupalAssets',
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
