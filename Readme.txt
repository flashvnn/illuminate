Laravel Illuminate

Integrate Laravel Illuminate with Drupal

Install
---------------------

1. Install requirements module libraries and composer-manager.
2. Download laravel libraries here:
   https://github.com/flashvnn/laravel/archive/master.zip
   Extract laravel to sites/all/libraries and make sure it have structure
    - sites/all/libraries/laravel/app
    - sites/all/libraries/laravel/config
      ...
3. Using drush and composer-manager install module illuminate_core
   You can easy install with command drush en illuminate_core
   Drush will enable module iluuminate_core and install all laravel framework with composer

4. After installed illuminate_core now you can ready to install main module illuminate.

Now Laravel ready for working Drupal.

Open file sites/all/libraries/laravel/app/Http/routes.php

Route::get('/laravel', array(
  function()
  {
    return "Welcome Laravel";
  }
));

Clear Drupal cache and visit your site with url yoursite.dev/laravel with yoursite.dev is your website domain,
you will see the magic.

More information about Laravel 5 route and other information you can view here: http://laravel.com/

To use controller with hook_menu in Drupal module you can define menu like bellow:

/**
 * Implements hook_menu().
 */
$items['illuminate_demo'] = array(
    'title'            => 'Demo Controller',
    'description'      => 'Demo using controller with Drupal',
    'access callback'  => 'user_access',
    'access arguments' => array('access content'),
    'page callback'    => 'illuminate_controller',
    'page arguments'   => array('Drupa\illuminate_demo\Controller\DemoController::index'),
    'type'             => MENU_CALLBACK,
);

the "page arguments" params is Controller class with function will called when visit the url.
You can use with xautoload to create controller class like Drupal 8 style.
You can view module illuminate_demo for more information.