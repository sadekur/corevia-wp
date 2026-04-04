<?php
/**
 * Plugin Name: Corevia WP
 * Plugin URI: https://sadekurrahman.net
 * Author: Sadekur Rahman
 * Author URI: https://sadekurrahman.net
 * Description: A Simple Plugin
 * Version: 0.9
 * Requires at least: 5.0
 * Tested up to: 6.5
 * Requires PHP: 7.4
 * Text Domain: corevia-wp
 * Domain Path: /languages
 *
 * Corevia WP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Corevia WP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

namespace ThrailWP;

defined( 'ABSPATH' ) || exit;

define( 'COREVIAWP_FILE', __FILE__ );
define( 'COREVIAWP_VERSION', '0.9' );
define( 'COREVIAWP_PLUGIN_DIR', plugin_dir_path( COREVIAWP_FILE ) );
define( 'COREVIAWP_PLUGIN_URL', plugin_dir_url( COREVIAWP_FILE ) );
define( 'COREVIAWP_ASSETS_URL', COREVIAWP_PLUGIN_URL . 'assets/' );

require_once 'vendor/autoload.php';

/**
 * Register the activation hook.
 * This hook is triggered when the plugin is activated.
 * It installs necessary database tables, sets initial seeds, 
 * and checks database versions.
 */
register_activation_hook( COREVIAWP_FILE, __NAMESPACE__ . '\\thrailwp_install' );
function thrailwp_install() {
	Bootstrap\Installer::install();
}

/**
 * Register the deactivation hook.
 * This hook is triggered when the plugin is activated.
 * It uninstalls necessary database tables, sets initial seeds, 
 * and checks database versions.
 */
register_deactivation_hook( COREVIAWP_FILE, __NAMESPACE__ . '\\thrailwp_uninstall' );
function thrailwp_uninstall() {
	Bootstrap\Uninstaller::uninstall();
}

/**
 * Add action for plugins_loaded to activate the plugin.
 * This action is triggered when all active plugins are fully loaded.
 * It sets up cron jobs, registers custom user roles, and performs other 
 * necessary activation tasks.
 */
add_action( 'plugins_loaded', __NAMESPACE__ . '\\thrailwp_activate' );
function thrailwp_activate() {
	Bootstrap\Activator::activate();
}

/**
 * Add action for plugins_loaded to initialize the plugin.
 * This action is triggered when all active plugins are fully loaded.
 * It sets the plugin's runtime environment and initializes hooks.
 */
add_action( 'plugins_loaded', __NAMESPACE__ . '\\thrailwp_initialize' );
function thrailwp_initialize() {
	Bootstrap\Initializer::initialize();
}
