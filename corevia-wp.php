<?php
/**
 * Plugin Name: Corevia WP
 * Description: A Simple Broilerplate for WordPress Plugin Development.
 * Plugin URI: https://sadekurrahman.net
 * Author: Corevial, Inc
 * Author URI: https://sadekurrahman.net
 * Version: 0.9
 * Requires at least: 5.0
 * Tested up to: 6.3
 * Requires PHP: 7.4
 * Text Domain: corevia-wp
 * Domain Path: /languages
 *
 * CoreviaWP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * CorevialWP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

namespace Corevia\Base;

use Corevia\Plugin\Widget;
use Corevia\Plugin\Notice;
use Corevia\Marketing\Survey;
use Corevia\Marketing\Feature;
use Corevia\Marketing\Deactivator;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class for the plugin
 * @package Plugin
 * @author Corevia <hi@codexpert.io>
 */
final class Plugin {
	
	/**
	 * The Plugin
	 * 
	 * @access private
	 */
	private $plugin;
	
	/**
	 * Plugin instance
	 * 
	 * @access private
	 * 
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * The constructor method
	 * 
	 * @access private
	 * 
	 * @since 0.9
	 */
	private function __construct() {
		
		/**
		 * Includes required files
		 */
		$this->include();

		/**
		 * Defines contants
		 */
		$this->define();

		/**
		 * Runs actual hooks
		 */
		$this->hook();

		/**
		 * Plugin is loaded
		 */
		do_action( 'base_loaded' );
	}

	/**
	 * Includes files
	 * 
	 * @access private
	 * 
	 * @uses composer
	 * @uses psr-4
	 */
	private function include() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		require_once( dirname( __FILE__ ) . '/inc/functions.php' );
		require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
	}

	/**
	 * Define variables and constants
	 * 
	 * @access private
	 * 
	 * @uses get_plugin_data
	 * @uses plugin_basename
	 */
	private function define() {

		/**
		 * Define some constants
		 * 
		 * @since 0.9
		 */
		define( 'BASE_FILE', __FILE__ );
		define( 'BASE_DIR', dirname( BASE_FILE ) );
		define( 'BASE_ASSETS', plugins_url( 'assets', BASE_FILE ) );
		define( 'BASE_DEBUG', apply_filters( 'base-wp_debug', true ) );

		/**
		 * The plugin data
		 * 
		 * @since 0.9
		 * @var $plugin
		 */
		$this->plugin					= get_plugin_data( BASE_FILE );
		$this->plugin['basename']		= plugin_basename( BASE_FILE );
		$this->plugin['file']			= BASE_FILE;
		$this->plugin['doc_id']			= 1960;
		$this->plugin['server']			= 'https://api.corevia.com';
		$this->plugin['icon']			= BASE_ASSETS . '/img/icon.png';
		$this->plugin['depends']		= [ 'woocommerce/woocommerce.php' => __( 'WooCommerce', 'base' ) ];

		/**
		 * Set plugin data instance
		 */
		define( 'BASE', apply_filters( 'base_instance', $this->plugin ) );
	}

	/**
	 * Hooks
	 * 
	 * @access private
	 * 
	 * Executes main plugin features
	 *
	 * To add an action, use $instance->action()
	 * To apply a filter, use $instance->filter()
	 * To register a shortcode, use $instance->register()
	 * To add a hook for logged in users, use $instance->priv()
	 * To add a hook for non-logged in users, use $instance->nopriv()
	 * 
	 * @return void
	 */
	private function hook() {

		if( is_admin() ) :

			/**
			 * The installer
			 */
			$installer = new App\Installer();
			$installer->activate( 'install' );
			$installer->deactivate( 'uninstall' );
			$installer->action( 'admin_footer', 'update' );

			/**
			 * Admin facing hooks
			 */
			$admin = new App\Admin();
			$admin->action( 'admin_footer', 'modal' );
			$admin->action( 'plugins_loaded', 'i18n' );
			// $admin->action( 'admin_menu', 'admin_menu' );
			$admin->action( 'admin_enqueue_scripts', 'enqueue_scripts' );
			$admin->filter( "plugin_action_links_{$this->plugin['basename']}", 'action_links' );
			$admin->filter( 'plugin_row_meta', 'plugin_row_meta', 10, 2 );
			$admin->action( 'save_post', 'update_cache', 10, 3 );
			$admin->action( 'admin_footer_text', 'footer_text' );

			/**
			 * The setup wizard
			 */
			$wizard = new App\Wizard();
			$wizard->action( 'plugins_loaded', 'render' );
			$wizard->filter( "plugin_action_links_{$this->plugin['basename']}", 'action_links' );

			/**
			 * Settings related hooks
			 */
			$settings = new App\Settings();
			$settings->action( 'plugins_loaded', 'init_menu' );

			/**
			 * Blog posts from Corevia blog
			 * 
			 * @package Corevia\Plugin
			 * 
			 * @author Corevia <hi@codexpert.io>
			 */
			$widget = new Widget();

			/**
			 * Renders different notices
			 * 
			 * @package Corevia\Plugin
			 * 
			 * @author Corevia <hi@codexpert.io>
			 */
			$notice = new Notice( $this->plugin );

			/**
			 * Asks to participate in a survey
			 * 
			 * @package Corevia\Marketing
			 * 
			 * @author Corevia <hi@codexpert.io>
			 */
			$survey = new Survey( $this->plugin );

			/**
			 * Shows a popup window asking why a user is deactivating the plugin
			 * 
			 * @package Corevia\Marketing
			 * 
			 * @author Corevia <hi@codexpert.io>
			 */
			$deactivator = new Deactivator( $this->plugin );

			/**
			 * Alters featured plugins
			 * 
			 * @package Corevia\Marketing
			 * 
			 * @author Corevia <hi@codexpert.io>
			 */
			$feature = new Feature( $this->plugin );

		else : // ! is_admin() ?

			/**
			 * Front facing hooks
			 */
			$front = new App\Front();
			$front->action( 'wp_head', 'head' );
			$front->action( 'wp_footer', 'modal' );
			$front->action( 'wp_enqueue_scripts', 'enqueue_scripts' );
			$front->action( 'admin_bar_menu', 'add_admin_bar', 70 );

			/**
			 * Shortcode related hooks
			 */
			$shortcode = new App\Shortcode();
			$shortcode->register( 'my-shortcode', 'my_shortcode' );

			/**
			 * Custom REST API related hooks
			 */
			$api = new App\API();
			$api->action( 'rest_api_init', 'register_endpoints' );

		endif;

		/**
		 * Common hooks
		 *
		 * Executes on both the admin area and front area
		 */
		$common = new App\Common();

		/**
		 * AJAX related hooks
		 */
		$ajax = new App\AJAX();
		$ajax->priv( 'some-route', 'some_callback' );
	}

	/**
	 * Cloning is forbidden.
	 * 
	 * @access public
	 */
	public function __clone() { }

	/**
	 * Unserializing instances of this class is forbidden.
	 * 
	 * @access public
	 */
	public function __wakeup() { }

	/**
	 * Instantiate the plugin
	 * 
	 * @access public
	 * 
	 * @return $_instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

Plugin::instance();
