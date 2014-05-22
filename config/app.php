<?php

/**
 * App configuration for Laravel module.
 * You can remove some options alias if you don't use.
 */



return array(
  'debug'           => TRUE,
  'locale'          => 'en',
  'fallback_locale' => 'en',
  'key'             => 'rIJdQkSmzQwYHlOJ7PlOYeB0oCjX6i3q',
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
    'Illuminate\Filesystem\FilesystemServiceProvider',
    'Drupal\Laravel\Cache\CacheServiceProvider',
    'Illuminate\Database\DatabaseServiceProvider',
    'Illuminate\Encryption\EncryptionServiceProvider',
    'Illuminate\Log\LogServiceProvider',
    'Illuminate\Mail\MailServiceProvider',
    'Illuminate\Translation\TranslationServiceProvider',
    'Illuminate\Validation\ValidationServiceProvider',
    'Illuminate\Queue\QueueServiceProvider',
    'Drupal\Laravel\Cookie\CookieServiceProvider',
    'Drupal\Laravel\Config\DrupalConfigServiceProvider',
    'Drupal\Laravel\Assets\DrupalAssetsServiceProvider',
    'Drupal\Laravel\ContentRender\ContentRenderServiceProvider',
    'Drupal\Laravel\YAML\YAMLServiceProvider',
    'Drupal\Laravel\View\ViewServiceProvider',
  ),
  'aliases' => array(
     'Arrays'      => 'Underscore\Types\Arrays',
     'Functions'   => 'Underscore\Types\Functions',
     'Object'      => 'Underscore\Types\Object',
     'String'      => 'Underscore\Types\String',
     'Parse'       => 'Underscore\Parse',
     'Carbon'      => 'Carbon\Carbon',
     'Str'         => 'Illuminate\Support\Str',
     'ClassLoader' => 'Illuminate\Support\ClassLoader',

     'App'          => 'Illuminate\Support\Facades\App',
     'Event'        => 'Illuminate\Support\Facades\Event',
     'Cache'        => 'Illuminate\Support\Facades\Cache',
     'Config'       => 'Illuminate\Support\Facades\Config',
     'Controller'   => 'Illuminate\Routing\Controller',
     'DrupalConfig' => 'Drupal\Laravel\Config\Facades\DrupalConfig',
     'DrupalAssets' => 'Drupal\Laravel\Assets\Facades\DrupalAssets',
     'File'         => 'Illuminate\Support\Facades\File',
     'DB'           => 'Illuminate\Support\Facades\DB',
     'Eloquent'     => 'Illuminate\Database\Eloquent\Model',
     'Log'          => 'Illuminate\Support\Facades\Log',
     'Mail'         => 'Illuminate\Support\Facades\Mail',
     'Request'      => 'Illuminate\Support\Facades\Request',
     'Response'     => 'Illuminate\Support\Facades\Response',
     'Route'        => 'Illuminate\Support\Facades\Route',
     'Schema'       => 'Illuminate\Support\Facades\Schema',
     'Cookie'       => 'Illuminate\Support\Facades\Cookie',
     'Crypt'        => 'Illuminate\Support\Facades\Crypt',
     'Input'        => 'Illuminate\Support\Facades\Input',
     'Queue'        => 'Illuminate\Support\Facades\Queue',
     'URL'          => 'Illuminate\Support\Facades\URL',
     'Validator'    => 'Illuminate\Support\Facades\Validator',
     'ViewRender'   => 'Illuminate\Support\Facades\View',
     'YAML'         => 'Drupal\Laravel\YAML\Facades\YAML',
  ),
);
