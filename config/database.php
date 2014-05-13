<?php
global $databases;
$database = $databases['default']['default'];

return array(
  "fetch"       => PDO::FETCH_CLASS,
  'default' => 'mysql',
  'connections' => array(
    'mysql' => array(
        'driver'    => $database['driver'],
        'host'      => $database['host'],
        'database'  => $database['database'],
        'username'  => $database['username'],
        'password'  => $database['password'],
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ),
  ),
);
