<?php

require 'config.php';



return [
  'paths' => [
    'migrations' => './migrations',
    'seeds' => './seeds'
  ],
  'migration_base_class' => '\CallDoc\Migration\Migration',
  'environments' => [
    'default_migration_table' => 'phinxlog',
    'default_database' => 'dev',
    'dev' => [
      'adapter' => DB_ADAPTER,
      'host' => DB_HOST,
      'name' => DB_NAME,
      'user' => DB_USER,
      'pass' => DB_PASSWORD,
      'port' => DB_PORT
      
    ]
  ]
];