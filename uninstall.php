<?php

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

$deletable_options = [ 'thrail-wp_activated', 'corevia-wp_db_version' ];
foreach ( $deletable_options as $option ) {
    delete_option( $option );
}