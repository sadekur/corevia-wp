<?php
namespace CoreviaWP\Helpers\Field;

use CoreviaWP\Abstracts\Field;

defined( 'ABSPATH' ) || exit;

/**
 * Email Field Class
 */
class Email extends Text {

	public function __construct( $config = array() ) {
		parent::__construct( $config );
		$this->set_type( 'email' );
	}
}
