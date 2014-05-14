<?php namespace Drupal\Laravel\Assets\Facades;

use Illuminate\Support\Facades\Facade;

class DrupalAssets extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'drupalassets';
    }
}
