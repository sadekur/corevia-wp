<?php
namespace ThrailWP\Controllers\Admin;

defined( 'ABSPATH' ) || exit;

use ThrailWP\Traits\Hook;
use ThrailWP\Traits\Asset;
use ThrailWP\Traits\Menu as Menu_Trait;
use ThrailWP\Helpers\Utility;

class Menu {

	use Hook;
	use Asset;
	use Menu_Trait;

	/**
	 * Constructor to add all hooks.
	 */
	public function __construct() {
		$this->action( 'admin_enqueue_scripts', array( $this, 'add_assets' ) );
		$this->action( 'admin_menu', array( $this, 'register' ) );
	}

	public function add_assets() {
		global $current_screen;

		if ( strpos( $current_screen->base, 'thrail-wp' ) !== false ) {

			$this->enqueue_script(
				'thrail-wp_main-menu',
				THRAILWP_PLUGIN_URL . 'spa/build/admin.bundle.js',
				array( 'wp-element', 'thrail-wp_common' )
			);
		}

		if ( strpos( $current_screen->base, 'thrail-wp' ) !== false ) {

			$this->enqueue_style(
				'thrail-wp_settings',
				THRAILWP_ASSETS_URL . 'admin/css/settings.css'
			);

			$this->enqueue_script(
				'thrail-wp_settings',
				THRAILWP_ASSETS_URL . 'admin/js/settings.js'
			);
		}
	}

	public function register() {
		$this->add_menu(
			__( 'Corevia WP', 'thrail-wp' ),
			__( 'Corevia WP', 'thrail-wp' ),
			'manage_options',
			'thrail-wp',
			array( $this, 'callback_main_menu' ),
			'dashicons-wordpress',
			2
		);

		$this->add_submenu(
			'thrail-wp',
			__( 'Dashboard', 'thrail-wp' ),
			__( 'Dashboard', 'thrail-wp' ),
			'manage_options',
			'thrail-wp',
			function () {}
		);

		$this->add_submenu(
			'thrail-wp',
			__( 'Help', 'thrail-wp' ),
			__( 'Help', 'thrail-wp' ),
			'manage_options',
			'thrail-wp#/help',
			function () {}
		);

		$this->add_submenu(
			'thrail-wp',
			__( 'Settings', 'thrail-wp' ),
			__( 'Settings', 'thrail-wp' ),
			'manage_options',
			'thrail-wp-settings',
			array( $this, 'callback_submenu' ),
		);
	}

	public function callback_main_menu() {
		printf(
			'<div class="wrap">
				<h2>%1$s</h2>
				<div id="thrail-wp_render">%2$s</div>
			</div>',
			'Corevia WP',
			__( 'Loading..', 'thrail-wp' )
		);
	}

	public function callback_submenu() {
		echo Utility::get_template( 'settings/layout.php' );
	}
}
