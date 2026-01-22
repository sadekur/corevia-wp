<?php
use ThrailWP\Helpers\Utility;

/**
 * Returns the home URL of the WordPress site.
 *
 * @param string $path    Optional. Path relative to the home URL.
 * @param int    $blog_id Optional. ID of the blog in a multisite installation.
 *
 * @return string Home URL with optional path appended.
 */
function thrailwp_home_url( $path = '', $blog_id = null ) {
	return get_home_url( $blog_id, $path );
}

function thrailwp_settings_menus() {

	$pages = Utility::get_posts( array( 'post_type' => 'page' ) );

	return apply_filters(
		'thrailwp_settings_menus',
		array(
			'general' => array(
				'label'    => __( 'General', 'thrail-wp' ),
				'desc'     => __( 'General settings', 'thrail-wp' ),
				'icon'     => '',
				'submenus' => array(
					'pages' => array(
						'label'    => __( 'Pages', 'thrail-wp' ),
						'desc'     => __( 'Page Settings', 'thrail-wp' ),
						'sections' => array(
							'main_pages' => array(
								'label'  => __( 'Main Pages', 'thrail-wp' ),
								'desc'   => __( 'Main Pages Settings', 'thrail-wp' ),
								'fields' => array(
									array(
										'id'      => 'homepage',
										'type'    => 'select',
										'label'   => __( 'Homepage', 'thrail-wp' ),
										'options' => $pages,
									),
									array(
										'id'      => 'landing_page',
										'type'    => 'select',
										'label'   => __( 'Landing Page', 'thrail-wp' ),
										'options' => $pages,
									),
								),
							),
						),
					),
				),
			),
			'email'   => array(
				'label'    => __( 'Email', 'thrail-wp' ),
				'desc'     => __( 'Email settings', 'thrail-wp' ),
				'icon'     => '',
				'submenus' => array(
					'new_ticket'    => array(
						'label'    => __( 'New Ticket', 'thrail-wp' ),
						'desc'     => __( 'New Ticket Notification', 'thrail-wp' ),
						'sections' => array(
							'agent_email'  => array(
								'label'  => __( 'Agent Email', 'thrail-wp' ),
								'desc'   => __( 'Email to an Agent', 'thrail-wp' ),
								'fields' => array(
									array(
										'id'    => 'agent_header',
										'type'  => 'text',
										'label' => __( 'Header', 'thrail-wp' ),
									),
									array(
										'id'    => 'agent_subject',
										'type'  => 'text',
										'label' => __( 'Subject', 'thrail-wp' ),
									),
									array(
										'id'    => 'agent_body',
										'type'  => 'wysiwyg',
										'label' => __( 'Body', 'thrail-wp' ),
									),
								),
							),
							'client_email' => array(
								'label'  => __( 'Client Email', 'thrail-wp' ),
								'desc'   => __( 'Email to a Client', 'thrail-wp' ),
								'fields' => array(
									array(
										'id'    => 'client_header',
										'type'  => 'text',
										'label' => __( 'Header', 'thrail-wp' ),
									),
									array(
										'id'    => 'client_subject',
										'type'  => 'text',
										'label' => __( 'Subject', 'thrail-wp' ),
									),
									array(
										'id'    => 'client_body',
										'type'  => 'wysiwyg',
										'label' => __( 'Body', 'thrail-wp' ),
									),
								),
							),
						),
					),
					'agent_replied' => array(
						'label'    => __( 'Agent Reply', 'thrail-wp' ),
						'desc'     => __( 'Agent Reply Notification', 'thrail-wp' ),
						'sections' => array(
							'agent_email_reply' => array(
								'label'  => __( 'Agent Reply Email', 'thrail-wp' ),
								'desc'   => __( 'Email to a Client', 'thrail-wp' ),
								'fields' => array(
									array(
										'id'    => 'client_header',
										'type'  => 'text',
										'label' => __( 'Header', 'thrail-wp' ),
									),
									array(
										'id'    => 'client_subject',
										'type'  => 'text',
										'label' => __( 'Subject', 'thrail-wp' ),
									),
									array(
										'id'    => 'client_body',
										'type'  => 'wysiwyg',
										'label' => __( 'Body', 'thrail-wp' ),
									),
								),
							),
						),
					),
				),
			),
		)
	);
}

function thrailwp_get_field_factory( $type ) {

	if ( $type == 'switch' ) {
		$type = 'switcher';
	} elseif ( $type == 'wysiwyg' ) {
		$type = 'WYSIWYG';
	}

	return '\\ThrailWP\\Helpers\\Field\\' . ucfirst( $type );
}
