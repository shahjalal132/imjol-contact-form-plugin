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


// Register Top Label Menu
add_action( 'admin_menu', 'show_all_user_infos' );
function show_all_user_infos() {
    add_menu_page(
        'all_users_infos',
        'Imjol Users Requirements',
        'manage_options',
        'all_users_infos',
        'show_all_users_infos_html',
        'dashicons-admin-users',
        30
    );

    // Display users information's table
    function show_all_users_infos_html() {
        // Include necessary WordPress files
        require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
        require_once ABSPATH . 'wp-admin/includes/template.php';

        // Extend WP_List_Table class
        class Imjol_Users_Infos_Table extends WP_List_Table {
            // Function to prepare items for the table
            function prepare_items() {
                global $wpdb;

                $per_page     = 10;
                $current_page = $this->get_pagenum();

                // Query to get total items count
                $total_items = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}imjol_requirement_forms" );

                // Set up the data for the current page
                $this->items = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}imjol_requirement_forms ORDER BY user_id DESC LIMIT " . ( ( $current_page - 1 ) * $per_page ) . ", $per_page" );

                // Set up pagination
                $this->set_pagination_args(
                    array(
                        'total_items' => $total_items,
                        'per_page'    => $per_page,
                    )
                );
            }

            // Function to define columns for the table
            function get_columns() {
                return array(
                    'user_id'     => 'ID',
                    'first_name'  => 'Name',
                    'address'     => 'Address',
                    'email'       => 'Email',
                    'phone'       => 'Phone',
                    'whatsapp'    => "What's App",
                    'mobile_app'  => 'Mobile App',
                    'website'     => 'Website',
                    'software'    => 'Software',
                    'requirement' => 'Requirements',
                    'budget'      => 'Budget',
                    'deadline'    => 'Deadline',
                );
            }

            // Function to render individual columns
            function column_default( $item, $column_name ) {
                return $item->$column_name;
            }
        }

        // Create an instance of the custom table class
        $imjol_users_infos_table = new Imjol_Users_Infos_Table();

        // Prepare the items for the table
        $imjol_users_infos_table->prepare_items();

        ?>
        <div class="wrap">
            <h2>Imjol Users Requirements</h2>
            <form method="post">
                <?php $imjol_users_infos_table->display(); ?>
            </form>
        </div>
        <?php
    }


    ?>
    </div>
    <?php
}


?>