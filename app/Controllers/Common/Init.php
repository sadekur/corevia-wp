<?php
namespace CoreviaWP\Controllers\Common;

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
		$this->action( 'wp_head', array( $this, 'modal' ) );
		$this->action( 'admin_head', array( $this, 'modal' ) );
		$this->action( 'wp_enqueue_scripts', array( $this, 'add_assets' ) );
		$this->action( 'admin_enqueue_scripts', array( $this, 'add_assets' ) );
	}

	public function modal() {
		echo '
		<div id="corevia-wp-modal" style="display: none">
			<img id="corevia-wp-modal-loader" src="' . esc_attr( COREVIAWP_ASSETS_URL . 'common/img/loader.gif' ) . '" />
		</div>';
	}

	public function add_assets() {
		global $current_screen;

		if ( isset( $current_screen->base ) && strpos( $current_screen->base, 'corevia-wp' ) !== false || ! is_admin() ) {

			$this->enqueue_script(
				'tailwind-css',
				COREVIAWP_PLUGIN_URL . 'spa/build/tailwind.bundle.js'
			);

			$this->enqueue_script(
				'corevia-wp_common',
				COREVIAWP_ASSETS_URL . 'common/js/init.js'
			);

			$this->enqueue_style(
				'corevia-wp_common',
				COREVIAWP_ASSETS_URL . 'common/css/init.css'
			);
		}
	}
}
