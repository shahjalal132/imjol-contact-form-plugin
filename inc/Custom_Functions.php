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
        ?>
        <div id="user-infos-table">
            <?php

            // Retrieve Data From Database
            $imjol_user_data = new WP_Query( [
                'table_name' => 'wp_imjol_requirement_forms',
                'fields'     => [ 'user_id', 'first_name', 'address', 'email', 'phone', 'whatsapp', 'mobile_app', 'website', 'software', 'requirement', 'budget', 'deadline' ],
                'order_by'   => 'user_id',
                'order'      => 'ASC',
            ] );

            // Conditionally table create
            if ( $imjol_user_data->have_posts() ) {
                ?>
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Address</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">What's App</th>
                            <th scope="col">Mobile App</th>
                            <th scope="col">Website</th>
                            <th scope="col">Software</th>
                            <th scope="col">Requirements</th>
                            <th scope="col">Budget</th>
                            <th scope="col">Deadline</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        global $wpdb;
                        $query   = "SELECT * FROM `{$wpdb->prefix}imjol_requirement_forms`";
                        $results = $wpdb->get_results( $query );
                        if ( $results ) {
                            foreach ( $results as $result ) {
                                $need_app      = $result->mobile_app == 1 ? 'Yes' : 'No';
                                $need_website  = $result->website == 1 ? 'Yes' : 'No';
                                $need_software = $result->software == 1 ? 'Yes' : 'No';
                                echo '<tr>';
                                echo '<td>' . $result->user_id . '</td>';
                                echo '<td>' . $result->first_name . '</td>';
                                echo '<td>' . $result->address . '</td>';
                                echo '<td>' . $result->email . '</td>';
                                echo '<td>' . $result->phone . '</td>';
                                echo '<td>' . $result->whatsapp . '</td>';
                                echo '<td>' . $need_app . '</td>';
                                echo '<td>' . $need_website . '</td>';
                                echo '<td>' . $need_software . '</td>';
                                echo '<td>' . $result->requirement . '</td>';
                                echo '<td>' . $result->budget . '</td>';
                                echo '<td>' . $result->deadline . '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            } else {
                echo 'No Data Found';
            }

            ?>
        </div>
        <?php
    }
}

?>