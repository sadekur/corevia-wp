<?php
/**
 * All public facing functions
 */
namespace Codexpert\CoreviaWP\App;
use Codexpert\Plugin\Base;
use Codexpert\CoreviaWP\Helper;
/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Front
 * @author Codexpert <hi@codexpert.io>
 */
class Front extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->version	= $this->plugin['Version'];
	}

	public function head() {}
	
	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {
		$min = defined( 'COREVIAWP_DEBUG' ) && COREVIAWP_DEBUG ? '' : '.min';

		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/front{$min}.css", COREVIAWP ), '', $this->version, 'all' );

		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/front{$min}.js", COREVIAWP ), [ 'jquery' ], $this->version, true );
		
		$localized = [
			'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
			'_wpnonce'	=> wp_create_nonce(),
		];
		wp_localize_script( $this->slug, 'COREVIAWP', apply_filters( "{$this->slug}-localized", $localized ) );
	}

	public function modal() {
		echo '
		<div id="corevia-wp-modal" style="display: none">
			<img id="corevia-wp-modal-loader" src="' . esc_attr( COREVIAWP_ASSET . '/img/loader.gif' ) . '" />
		</div>';
	}
}