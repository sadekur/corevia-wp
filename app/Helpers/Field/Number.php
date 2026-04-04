<?php
namespace CoreviaWP\Helpers\Field;

use CoreviaWP\Abstracts\Field;

defined( 'ABSPATH' ) || exit;

/**
 * Number Field Class
 */
class Number extends Text {

	public function __construct( $config = array() ) {
		parent::__construct( $config );
		$this->set_type( 'number' );
	}
}
