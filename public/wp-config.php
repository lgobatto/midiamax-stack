<?php
/**
 * Configuration for WordPress.
 */

/** Absolute path to the WordPress directory. */
if(!defined('ABSPATH')){
	define('ABSPATH', dirname(__FILE__) . '/');
}
require_once 'vendor/autoload.php';

/**
 * Adjust HTTPS and IP detection.
 */
if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO']){
	$_SERVER['HTTPS'] = 'on';
}
global $table_prefix;

/**
 * Environment variables.
 * - Load from ../shared/.env || ../configs/.env || ../.env || ./.env
 *
 * - Check for required variables.
 * - Define constant for each variable if not already defined.
 */
$root      = dirname(__DIR__);

$dotenv    = \Dotenv\Dotenv::createImmutable([$root . '/shared', $root . '/configs', $root, __DIR__]);

$variables = $dotenv->load();

$dotenv->required([
	'_HTTP_HOST',
	'DB_NAME',
	'DB_USER',
	'DB_PASSWORD',
	'AUTH_KEY',
	'SECURE_AUTH_KEY',
	'LOGGED_IN_KEY',
	'NONCE_KEY',
	'AUTH_SALT',
	'SECURE_AUTH_SALT',
	'LOGGED_IN_SALT',
	'NONCE_SALT'
])->notEmpty();

$variable_names = array_keys($variables);

array_walk(
	$variable_names,
	function ($name) {
		switch($name):
			// Some variables are not used as a constant.
			case '_HTTP_HOST':
			case 'URL_DEVELOPMENT':
			case 'URL_STAGING':
			case 'URL_PRODUCTION':
				break;

			// Assign table prefix to the $GLOBALS variable.
			case 'DB_TABLE_PREFIX':
				$GLOBALS['table_prefix'] = $_ENV[$name];
				break;

			// Any other variable will be a constant unless it's already defined.
			default:
				if(!defined($name)):
					$value = $_ENV[$name];
					$value = str_replace('{{DIR}}', __DIR__, $value);
					define($name, $value);
                endif;
				break;
        endswitch;
	}
);
unset($root, $dotenv, $variables, $variable_names);

/**
 * Environment settings.
 */
$envs = [
	'development' => $_ENV['URL_DEVELOPMENT'],
	'staging'     => $_ENV['URL_STAGING'],
	'production'  => $_ENV['URL_PRODUCTION'],
];
define('ENVIRONMENTS', serialize($envs));
defined('WP_ENV') || define('WP_ENV', 'development');

/**
 * Debugging settings.
 */
defined('QM_DISABLED') || define('QM_DISABLED', true);
defined('SAVEQUERIES') || define('SAVEQUERIES', false);
defined('WP_DEBUG') || define('WP_DEBUG', true);
defined('WP_DISABLE_FATAL_ERROR_HANDLER') || define('WP_DISABLE_FATAL_ERROR_HANDLER', WP_DEBUG);
defined('WP_DEBUG_LOG') || define('WP_DEBUG_LOG', true);
defined('WP_DEBUG_DISPLAY') || define('WP_DEBUG_DISPLAY', true);
defined('SCRIPT_DEBUG') || define('SCRIPT_DEBUG', false);
defined('DISALLOW_FILE_MODS') || define('DISALLOW_FILE_MODS', false);

/**
 * Database settings.
 */
defined('DB_HOST') || define('DB_HOST', 'localhost');
defined('DB_CHARSET') || define('DB_CHARSET', 'utf8mb4');
defined('DB_COLLATE') || define('DB_COLLATE', '');

if(empty($table_prefix)){
	$table_prefix = 'wp_';
}
/**
 * Object caching.
 */
defined('WP_CACHE_KEY_SALT') || define('WP_CACHE_KEY_SALT', WP_ENV);

/**
 * URLs and paths.
 */
define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] . '/');
define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
