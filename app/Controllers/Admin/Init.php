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
			'corevia-wp_admin',
			COREVIAWP_PLUGIN_URL . 'assets/admin/js/init.js'
		);

		global $coreviawp_menus;

		$this->localize_script(
			'corevia-wp_admin',
			'coreviawp_PLUGIN_ADMIN',
			array(
				'menus'    => $coreviawp_menus,
				'api_base' => rest_url( 'corevia-wp/v1' ),
				'nonce'    => wp_create_nonce( 'wp_rest' ),
			)
		);
	}
}
