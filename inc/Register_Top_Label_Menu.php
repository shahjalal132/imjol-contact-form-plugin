<?php

class Register_Top_Label_Menu {
    public function __construct() {
        $this->setup_hooks();
    }

    public function setup_hooks() {
        add_action( 'admin_menu', [ $this, 'register_top_label_menu' ] );
    }

    public function register_top_label_menu() {
        add_menu_page(
            'all_users_infos',
            'Imjol Users Requirements',
            'manage_options',
            'all_users_infos',
            [ $this, 'show_all_users_infos_html' ],
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
                    'table_name' => 'wp_imjol_forms',
                    'fields'     => [ 'user_id', 'first_name', 'address', 'email', 'phone', 'whatsapp', 'mobile_app', 'website', 'software', 'requirement', 'budget', 'deadline' ],
                    'order_by'   => 'user_id',
                    'order'      => 'ASC',
                ] );

                // Conditionally table create
                if ( $imjol_user_data->have_posts() ) {
                    ?>
                    <table>
                        <style>
                            table,
                            th,
                            td {
                                border: 1px solid black;
                                border-collapse: collapse;
                            }

                            th,
                            td {
                                padding-top: 10px;
                                padding-bottom: 10px;
                                padding-left: 20px;
                                padding-right: 20px;
                            }
                        </style>
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>User Name</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>What's App</th>
                                <th>Mobile App</th>
                                <th>Website</th>
                                <th>Software</th>
                                <th>Requirements</th>
                                <th>Budget</th>
                                <th>Deadline</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            global $wpdb;
                            $query   = "SELECT * FROM `{$wpdb->prefix}imjol_forms`";
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
}

// Instantiate the class
$register_top_label_menu = new Register_Top_Label_Menu();

?>