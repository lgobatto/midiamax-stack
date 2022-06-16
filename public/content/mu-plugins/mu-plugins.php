<?php

/**
 * Plugin Name: Must Use Plugins
 * Plugin URI: https://github.com/roots/bedrock-autoloader
 * Description: Must-use plugins (a.k.a. mu-plugins) are plugins installed in a special directory inside the content folder and which are automatically enabled on all sites in the installation.
 * Version: 1.0.0
 * Author: Roots
 * Author URI: https://roots.io/
 * License: MIT
 */

if (is_blog_installed() && class_exists('Roots\Bedrock\Autoloader')) {
	new Roots\Bedrock\Autoloader();
}
