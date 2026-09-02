<?php
namespace Corevia\Base\App;

use Corevia\Plugin\Base;
use Corevia\Plugin\Metabox;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Admin
 * @author Corevia <hi@codexpert.io>
 */
class Admin extends Base {

	public $plugin;
	
	public $slug;

	public $name;

	public $version;

	/**
	 * Constructor function
	 */
	public function __construct() {
		$this->plugin	= BASE;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->version	= $this->plugin['Version'];
	}

	/**
	 * Internationalization
	 */
	public function i18n() {
		load_plugin_textdomain( 'base', false, BASE_DIR . '/languages/' );
	}

	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {
		$min = defined( 'BASE_DEBUG' ) && BASE_DEBUG ? '' : '.min';
		
		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/admin{$min}.css", BASE_FILE ), '', $this->version, 'all' );
		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/admin{$min}.js", BASE_FILE ), [ 'jquery' ], $this->version, true );

	    wp_enqueue_script( "{$this->slug}-react", plugins_url( 'spa/admin/build/index.js', BASE_FILE ), [ 'wp-element' ], '1.0.0', true );

	    $localized = [
	    	'homeurl'		=> get_bloginfo( 'url' ),
	    	'adminurl'		=> admin_url(),
	    	'asseturl'		=> BASE_ASSETS,
	    	'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
	    	'_wpnonce'		=> wp_create_nonce(),
	    	'api_base'		=> get_rest_url(),
	    	'rest_nonce'	=> wp_create_nonce( 'wp_rest' ),
	    ];
	    
	    wp_localize_script( $this->slug, 'BASE', apply_filters( "{$this->slug}-localized", $localized ) );
	}

	public function admin_menu() {

		add_menu_page(
			__( 'CoreviaWP', 'base' ),
			__( 'CoreviaWP', 'base' ),
			'manage_options',
			'base',
			function(){},
			'dashicons-wordpress',
			25
		);

		add_submenu_page(
			'base',
			__( 'Help', 'base' ),
			__( 'Help', 'base' ),
			'manage_options',
			'base-help',
			function() {
				printf( '<div id="base_help"><p>%s</p></div>', __( 'Loading..', 'base' ) );
			}
		);

		add_submenu_page(
			'base',
			__( 'License', 'base' ),
			__( 'License', 'base' ),
			'manage_options',
			'base-license',
			function() {
				printf( '<div id="base_license"><p>%s</p></div>', __( 'Loading..', 'base' ) );
			}
		);
	}

	public function action_links( $links ) {
		$this->admin_url = admin_url( 'admin.php' );

		$new_links = [
			'settings'	=> sprintf( '<a href="%1$s">' . __( 'Settings', 'base' ) . '</a>', add_query_arg( 'page', $this->slug, $this->admin_url ) )
		];
		
		return array_merge( $new_links, $links );
	}

	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		
		if ( $this->plugin['basename'] === $plugin_file ) {
			$plugin_meta['help'] = '<a href="https://help.codexpert.io/" target="_blank" class="cx-help">' . __( 'Help', 'base' ) . '</a>';
		}

		return $plugin_meta;
	}

	public function update_cache( $post_id, $post, $update ) {
		wp_cache_delete( "coreviawp_{$post->post_type}", 'coreviawp' );
	}

	public function footer_text( $text ) {
		if( get_current_screen()->parent_base != $this->slug ) return $text;

		return sprintf( __( 'If you like <strong>%1$s</strong>, please <a href="%2$s" target="_blank">leave us a %3$s rating</a> on WordPress.org! It\'d motivate and inspire us to make the plugin even better!', 'base' ), $this->name, "https://wordpress.org/support/plugin/{$this->slug}/reviews/?filter=5#new-post", '⭐⭐⭐⭐⭐' );
	}

	public function modal() {
		echo '
		<div id="base-modal" style="display: none">
			<img id="base-modal-loader" src="' . esc_attr( BASE_ASSETS . '/img/loader.gif' ) . '" />
		</div>';
	}
}