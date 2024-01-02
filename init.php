<?php

/*
 * Plugin Name:       imjol-contact-form
 * Plugin URI:        #
 * Description:       ImJol Contact Form sent requirements
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Shah jalal
 * Author URI:        #
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       imjol-contact-form
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
defined( "ABSPATH" ) || exit( "Direct Access Not Allowed" );

// Define plugin path
if ( !defined( 'IMJOL_PLUGIN_PATH' ) ) {
    define( 'IMJOL_PLUGIN_PATH', untrailingslashit( dirname( __FILE__ ) ) );
}

// Define plugin url
if ( !defined( 'IMJOL_PLUGIN_URL' ) ) {
    define( 'IMJOL_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
}


// db table create when plugin activate
register_activation_hook( __FILE__, 'imjol_db_table_create' );

// remove db table when plugin deactivate
register_deactivation_hook( __FILE__, 'imjol_db_table_remove' );





// include all files
require_once IMJOL_PLUGIN_PATH . '/inc/Custom_Functions.php';
require_once IMJOL_PLUGIN_PATH . '/inc/Register_Top_Label_Menu.php';
require_once IMJOL_PLUGIN_PATH . '/inc/Enqueue_Assets.php';