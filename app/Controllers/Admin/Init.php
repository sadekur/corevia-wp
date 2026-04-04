<?php
namespace CoreviaWP\Controllers\Admin;

defined( 'ABSPATH' ) || exit;

use CoreviaWP\Traits\Hook;
use CoreviaWP\Traits\Asset;

class Init {

	use Hook;
	use Asset;

	/**
	 * Constructor to add all hooks.
	 */
	public function __construct() {
		$this->action( 'admin_enqueue_scripts', array( $this, 'add_assets' ) );
	}

	public function add_assets() {

		$this->enqueue_script(
			'thrail-wp_admin',
			COREVIAWP_PLUGIN_URL . 'assets/admin/js/init.js'
		);

		global $thrailwp_menus;

		$this->localize_script(
			'thrail-wp_admin',
			'THRAILWP_PLUGIN_ADMIN',
			array(
				'menus'    => $thrailwp_menus,
				'api_base' => rest_url( 'corevia-wp/v1' ),
				'nonce'    => wp_create_nonce( 'wp_rest' ),
			)
		);
	}
}
