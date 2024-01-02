<?php

// Create Database When Plugin Activated
function imjol_db_table_create() {
    global $wpdb;

    $table_name      = $wpdb->prefix . 'imjol_requirement_forms';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        user_id INT AUTO_INCREMENT,
        first_name VARCHAR(255) NOT NULL,
        address TEXT NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        whatsapp VARCHAR(20),
        mobile_app TINYINT(1) NOT NULL DEFAULT 0,
        website TINYINT(1) NOT NULL DEFAULT 0,
        software TINYINT(1) NOT NULL DEFAULT 0,
        requirement TEXT,
        budget VARCHAR(255),
        deadline VARCHAR(255),

        PRIMARY KEY (user_id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}

//  remove table when deactivated plugin
function imjol_db_table_remove() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'imjol_requirement_forms';
    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}

?>