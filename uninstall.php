<?php
/**
 * Perform when the plugin is being uninstalled
 */

// If uninstall is not called, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

$deletable_options = [ 'corevia-wp_version', 'corevia-wp_install_time', 'corevia-wp_docs_json', 'codexpert-blog-json' ];
foreach ( $deletable_options as $option ) {
    delete_option( $option );
}