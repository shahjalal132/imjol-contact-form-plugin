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

    // Display users information's table with pagination
    function show_all_users_infos_html() {

        global $wpdb;
        $table_name = $wpdb->prefix . 'imjol_requirement_forms';

        // Get current page number
        $current_page = max( 1, isset( $_GET['paged'] ) ? intval( $_GET['paged'] ) : 1 );
        $per_page     = 10;
        $offset       = ( $current_page - 1 ) * $per_page;

        // Fetch results with pagination and search
        $search_term = isset( $_GET['search-user-requirement'] ) ? sanitize_text_field( $_GET['search-user-requirement'] ) : '';
        $query       = "SELECT * FROM $table_name";

        // Add WHERE clause if search term is provided
        if ( !empty( $search_term ) ) {
            $query .= $wpdb->prepare( " WHERE first_name LIKE %s OR address LIKE %s OR email LIKE %s OR phone LIKE %s", '%' . $search_term . '%', '%' . $search_term . '%', '%' . $search_term . '%', '%' . $search_term . '%' );
        }

        $query .= " LIMIT $per_page OFFSET $offset";
        $results = $wpdb->get_results( $query );

        ?>

        <!-- Search box -->
        <nav class="navbar navbar-light bg-light mt-4">
            <form class="form-inline d-flex ms-auto gap-2 me-2" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
                <input class="form-control mr-sm-2" id="search-input" name="page" value="all_users_infos" type="hidden">
                <input class="form-control mr-sm-2" id="search-input" name="search-user-requirement"
                    value="<?php echo isset( $_GET['search-user-requirement'] ) ? esc_attr( $_GET['search-user-requirement'] ) : ''; ?>"
                    type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </nav>

        <!-- Display users information?s table -->
        <div id="user-infos-table">
            <table class="table table-hover table-striped" id="user-infos-table">
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
                    if ( !empty( $results ) && is_array( $results ) ) {
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
        </div>

        <!-- Pagination -->
        <div class="pagination float-end me-2">
            <?php
            $total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

            $total_pages = ceil( $total_items / $per_page );

            echo paginate_links(
                array(
                    'base'      => add_query_arg( 'paged', '%#%' ),
                    'format'    => '',
                    'prev_text' => __( 'Previous' ),
                    'next_text' => __( 'Next' ),
                    'total'     => $total_pages,
                    'current'   => $current_page,
                )
            );
            ?>
        </div>

        <?php
    }
}
