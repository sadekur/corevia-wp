<?php
namespace CoreviaWP\Controllers\Admin;

defined( 'ABSPATH' ) || exit;

use CoreviaWP\Traits\Hook;
use CoreviaWP\Traits\Asset;
use CoreviaWP\Traits\Menu as Menu_Trait;
use CoreviaWP\Helpers\Utility;

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

		if ( strpos( $current_screen->base, 'corevia-wp' ) !== false ) {

			$this->enqueue_script(
				'corevia-wp_main-menu',
				COREVIAWP_PLUGIN_URL . 'spa/build/admin.bundle.js',
				array( 'wp-element', 'corevia-wp_common' )
			);
		}

		if ( strpos( $current_screen->base, 'corevia-wp' ) !== false ) {

			$this->enqueue_style(
				'corevia-wp_settings',
				COREVIAWP_ASSETS_URL . 'admin/css/settings.css'
			);

			$this->enqueue_script(
				'corevia-wp_settings',
				COREVIAWP_ASSETS_URL . 'admin/js/settings.js'
			);
		}
	}

	public function register() {
		$this->add_menu(
			__( 'Corevia WP', 'corevia-wp' ),
			__( 'Corevia WP', 'corevia-wp' ),
			'manage_options',
			'corevia-wp',
			array( $this, 'callback_main_menu' ),
			'dashicons-wordpress',
			2
		);

		$this->add_submenu(
			'corevia-wp',
			__( 'Dashboard', 'corevia-wp' ),
			__( 'Dashboard', 'corevia-wp' ),
			'manage_options',
			'corevia-wp',
			function () {}
		);

		$this->add_submenu(
			'corevia-wp',
			__( 'Help', 'corevia-wp' ),
			__( 'Help', 'corevia-wp' ),
			'manage_options',
			'corevia-wp#/help',
			function () {}
		);

		$this->add_submenu(
			'corevia-wp',
			__( 'Settings', 'corevia-wp' ),
			__( 'Settings', 'corevia-wp' ),
			'manage_options',
			'corevia-wp-settings',
			array( $this, 'callback_submenu' ),
		);
	}

	public function callback_main_menu() {
		printf(
			'<div class="wrap">
				<h2>%1$s</h2>
				<div id="corevia-wp_render">%2$s</div>
			</div>',
			'Corevia WP',
			__( 'Loading..', 'corevia-wp' )
		);
	}

	public function callback_submenu() {
		echo Utility::get_template( 'settings/layout.php' );
	}
}
