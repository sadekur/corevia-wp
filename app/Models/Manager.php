<?php
namespace ThrailWP\Models;

defined( 'ABSPATH' ) || exit;

use ThrailWP\Abstracts\User;

/**
 * Concrete Manager Class
 */
class Manager extends User {

	protected $role = 'manager';

	public function __construct( $id = null ) {
		parent::__construct( $id );
	}
}
