<?php 
use Symfony\Component\Yaml\Yaml;

$db_config = Yaml::parse(file_get_contents('./phinx.yml'));
$db = $db_config['environments']['development'];

define('DB_HOST', $db['host']);
define('DB_NAME', $db['name']);
define('DB_USER', $db['user']);
define('DB_PASSWORD', $db['pass']);
define('DB_PORT', $db['port']);
define('DB_ADAPTER', $db['adapter']);



