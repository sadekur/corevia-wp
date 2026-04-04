<?php
namespace CoreviaWP\API;

defined( 'ABSPATH' ) || exit;

use CoreviaWP\Traits\Rest;

class Option {

	use Rest;


	/**
	 * Get the value of a specified option.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
	public function get( $request ) {
		$key = $request->get_param( 'key' );

		if ( empty( $key ) ) {
			return $this->response_error( __( 'Option key is required.', 'corevia-wp' ) );
		}

		$value = get_option( $key );

		// if ( empty( $value ) ) {
		// return $this->response_error( __( 'Option not found.', 'corevia-wp' ) );
		// }

		return $this->response_success( $value );
	}

	/**
	 * Update the value of a specified option.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
	public function update( $request ) {
		$key   = $request->get_param( 'key' );
		$value = $request->get_param( 'value' );

		if ( empty( $key ) || empty( $value ) ) {
			return $this->response_error( __( 'Option key and value are required.', 'corevia-wp' ) );
		}

		$updated = update_option( $key, $value );

		if ( ! $updated ) {
			return $this->response_success( __( 'Option not updated.', 'corevia-wp' ) );
		}

		return $this->response_success( __( 'Option updated successfully.', 'corevia-wp' ) );
	}

	/**
	 * Delete the specified option.
	 *
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
	public function delete( $request ) {
		$key = $request->get_param( 'key' );

		if ( empty( $key ) ) {
			return $this->response_error( __( 'Option key is required.', 'corevia-wp' ) );
		}

		$deleted = delete_option( $key );

		if ( ! $deleted ) {
			return $this->response_error( __( 'Failed to delete option.', 'corevia-wp' ) );
		}

		return $this->response_success( __( 'Option deleted successfully.', 'corevia-wp' ) );
	}
}
