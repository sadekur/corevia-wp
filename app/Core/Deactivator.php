<?php
namespace CoreviaWP\Core;

defined( 'ABSPATH' ) || exit;

use CoreviaWP\Models\Database;

class Deactivator {

	/**
	 * Run deactivation routines.
	 */
	public static function deactivate() {
		$deactivater = new self();

		$deactivater->remove_db_version();
	}

	/**
	 * Remove the database version from the options table.
	 */
	protected function remove_db_version() {
		delete_option( 'thrail-wp_db_version' );
	}
}
