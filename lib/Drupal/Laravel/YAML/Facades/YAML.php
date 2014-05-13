<?php namespace Drupal\Laravel\YAML\Facades;

use Illuminate\Support\Facades\Facade;

class YAML extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'yaml';
    }
}
