<?php
namespace CoreviaWP\Controllers\Front;

defined( 'ABSPATH' ) || exit;

use CoreviaWP\Traits\Hook;

class Debug {

	use Hook;

	/**
	 * Constructor to add all hooks.
	 */
	public function __construct() {
		$this->action( 'wp_head', array( $this, 'test' ) );
	}

	public function test() {}
}
