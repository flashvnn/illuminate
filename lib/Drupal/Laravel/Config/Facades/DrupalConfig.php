<?php namespace Drupal\Laravel\Config\Facades;

use Illuminate\Support\Facades\Facade;

class DrupalConfig extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'drupalconfig';
    }
}
