<?php
use CoreviaWP\Helpers\Utility;

/**
 * Returns the home URL of the WordPress site.
 *
 * @param string $path    Optional. Path relative to the home URL.
 * @param int    $blog_id Optional. ID of the blog in a multisite installation.
 *
 * @return string Home URL with optional path appended.
 */
function coreviawp_home_url( $path = '', $blog_id = null ) {
	return get_home_url( $blog_id, $path );
}

function coreviawp_settings_menus() {

	$pages = Utility::get_posts( array( 'post_type' => 'page' ) );

	return apply_filters(
		'coreviawp_settings_menus',
		array(
			'general' => array(
				'label'    => __( 'General', 'corevia-wp' ),
				'desc'     => __( 'General settings', 'corevia-wp' ),
				'icon'     => '',
				'submenus' => array(
					'pages' => array(
						'label'    => __( 'Pages', 'corevia-wp' ),
						'desc'     => __( 'Page Settings', 'corevia-wp' ),
						'sections' => array(
							'main_pages' => array(
								'label'  => __( 'Main Pages', 'corevia-wp' ),
								'desc'   => __( 'Main Pages Settings', 'corevia-wp' ),
								'fields' => array(
									array(
										'id'      => 'homepage',
										'type'    => 'select',
										'label'   => __( 'Homepage', 'corevia-wp' ),
										'options' => $pages,
									),
									array(
										'id'      => 'landing_page',
										'type'    => 'select',
										'label'   => __( 'Landing Page', 'corevia-wp' ),
										'options' => $pages,
									),
								),
							),
						),
					),
				),
			),
			'email'   => array(
				'label'    => __( 'Email', 'corevia-wp' ),
				'desc'     => __( 'Email settings', 'corevia-wp' ),
				'icon'     => '',
				'submenus' => array(
					'new_ticket'    => array(
						'label'    => __( 'New Ticket', 'corevia-wp' ),
						'desc'     => __( 'New Ticket Notification', 'corevia-wp' ),
						'sections' => array(
							'agent_email'  => array(
								'label'  => __( 'Agent Email', 'corevia-wp' ),
								'desc'   => __( 'Email to an Agent', 'corevia-wp' ),
								'fields' => array(
									array(
										'id'    => 'agent_header',
										'type'  => 'text',
										'label' => __( 'Header', 'corevia-wp' ),
									),
									array(
										'id'    => 'agent_subject',
										'type'  => 'text',
										'label' => __( 'Subject', 'corevia-wp' ),
									),
									array(
										'id'    => 'agent_body',
										'type'  => 'wysiwyg',
										'label' => __( 'Body', 'corevia-wp' ),
									),
								),
							),
							'client_email' => array(
								'label'  => __( 'Client Email', 'corevia-wp' ),
								'desc'   => __( 'Email to a Client', 'corevia-wp' ),
								'fields' => array(
									array(
										'id'    => 'client_header',
										'type'  => 'text',
										'label' => __( 'Header', 'corevia-wp' ),
									),
									array(
										'id'    => 'client_subject',
										'type'  => 'text',
										'label' => __( 'Subject', 'corevia-wp' ),
									),
									array(
										'id'    => 'client_body',
										'type'  => 'wysiwyg',
										'label' => __( 'Body', 'corevia-wp' ),
									),
								),
							),
						),
					),
					'agent_replied' => array(
						'label'    => __( 'Agent Reply', 'corevia-wp' ),
						'desc'     => __( 'Agent Reply Notification', 'corevia-wp' ),
						'sections' => array(
							'agent_email_reply' => array(
								'label'  => __( 'Agent Reply Email', 'corevia-wp' ),
								'desc'   => __( 'Email to a Client', 'corevia-wp' ),
								'fields' => array(
									array(
										'id'    => 'client_header',
										'type'  => 'text',
										'label' => __( 'Header', 'corevia-wp' ),
									),
									array(
										'id'    => 'client_subject',
										'type'  => 'text',
										'label' => __( 'Subject', 'corevia-wp' ),
									),
									array(
										'id'    => 'client_body',
										'type'  => 'wysiwyg',
										'label' => __( 'Body', 'corevia-wp' ),
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

function coreviawp_get_field_factory( $type ) {

	if ( $type == 'switch' ) {
		$type = 'switcher';
	} elseif ( $type == 'wysiwyg' ) {
		$type = 'WYSIWYG';
	}

	return '\\CoreviaWP\\Helpers\\Field\\' . ucfirst( $type );
}
