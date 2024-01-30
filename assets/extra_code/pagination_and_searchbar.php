<?php
// partner-history.php

function partner_history_page_content() {
    ?>
    <style>
        .history {
            padding-top: 20px;
        }

        .history .tablenav-pages a {
            text-decoration: none;
        }

        .history .tablenav-pages .current {
            background-color: #fff;
        }

        .history .tablenav-pages .page-numbers {
            padding: 5px 10px;
            border: solid 1px #ccc;
            border-radius: 5px;
        }
    </style>
    <div class="wrap">
        <h2>
            <?php echo esc_html( get_admin_page_title() ); ?>
        </h2>

        <!-- Search Form -->
        <form method="post">
            <p class="search-box">
                <label class="screen-reader-text" for="user-search-input">Search Partner:</label>
                <input type="search" id="user-search-input" name="user_search"
                    value="<?php echo isset( $_POST['user_search'] ) ? esc_attr( $_POST['user_search'] ) : ''; ?>">
                <input type="submit" id="search-submit" class="button" value="Search Partner">
            </p>
        </form>

        <?php
        // Query and display user information
        $search_term = isset( $_POST['user_search'] ) ? sanitize_text_field( $_POST['user_search'] ) : '';
        // Retrieve the 'paged' parameter from the URL
        $current_page = max( 1, isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1 );

        // Use get_query_var as a fallback
        $current_page = max( 1, get_query_var( 'paged', $current_page ) );

        $users_per_page = 10; // Change this as needed
        $offset         = ( $current_page - 1 ) * $users_per_page;

        $user_query = new WP_User_Query(
            array(
                'role'    => 'partner',
                'orderby' => 'login',
                'order'   => 'ASC',
                'search'  => '*' . $search_term . '*',
                'number'  => $users_per_page,
                'offset'  => $offset,
            )
        );

        if ( !empty( $user_query->get_results() ) ) {
            ?>
            <div class="tablenav top history">
                <?php
                // Top Pagination
                $total_users = $user_query->get_total();
                $total_pages = ceil( $total_users / $users_per_page );

                $pagination_args_top = array(
                    'base'      => add_query_arg( 'paged', '%#%' ),
                    'format'    => '',
                    'total'     => $total_pages,
                    'current'   => $current_page,
                    'prev_text' => 'Prev',
                    'next_text' => 'Next',
                );

                echo '<div class="tablenav-pages">' . paginate_links( $pagination_args_top ) . '</div>';
                ?>
            </div>

            <table class="wp-list-table widefat striped">
                <thead>
                    <tr>
                        <th>
                            <?php echo esc_html( 'Username' ); ?>
                        </th>
                        <th>
                            <?php echo esc_html( 'Total Income' ); ?>
                        </th>
                        <th>
                            <?php echo esc_html( 'Total Withdraw' ); ?>
                        </th>
                        <th>
                            <?php echo esc_html( 'Roles' ); ?>
                        </th>
                        <th>
                            <?php echo esc_html( 'Wallet Balance' ); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ( $user_query->get_results() as $user ) {
                        $user_id        = $user->ID;
                        $username       = $user->user_login;
                        $name           = $user->display_name;
                        $email          = $user->user_email;
                        $roles          = implode( ', ', $user->roles );
                        $wallet_balance = woo_wallet()->wallet->get_wallet_balance( $user_id );

                        // Assuming that the following functions are correctly defined
                        $total_income = wc_price( get_pending_total_by_user_id( $user_id ) );

                        // Calculate total value for each user based on the 'payment' post type
                        $total_withdraw = 0; // Initialize total value
            
                        $payment_query = new WP_Query(
                            array(
                                'post_type'      => 'payment',
                                'posts_per_page' => -1,
                                'author'         => $user_id,
                            )
                        );

                        while ( $payment_query->have_posts() ) :
                            $payment_query->the_post();
                            // Adjust the following logic based on your 'payment' post type structure
                            $order_ids = get_post_meta( get_the_ID(), 'order_ids', true );

                            // Assuming that get_payment_request_total and deduct_payment_by_payment_id return numeric values
                            $total_withdraw += get_payment_request_total( $order_ids, $user_id ) - deduct_payment_by_payment_id( $user_id );
                        endwhile;

                        wp_reset_postdata();

                        echo '<tr>';
                        echo '<td>' . esc_html( $username ) . '</td>';
                        echo '<td>' . $total_income . '</td>';
                        echo '<td>' . wc_price( $total_withdraw ) . '</td>';
                        echo '<td>' . esc_html( $roles ) . '</td>';
                        echo '<td>' . $wallet_balance . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <div class="tablenav bottom history">
                <?php
                // Bottom Pagination
                $pagination_args_bottom = array(
                    'base'      => add_query_arg( 'paged', '%#%' ),
                    'format'    => '',
                    'total'     => $total_pages,
                    'current'   => $current_page,
                    'prev_text' => 'Prev',
                    'next_text' => 'Next',
                );

                echo '<div class="tablenav-pages">' . paginate_links( $pagination_args_bottom ) . '</div>';
                ?>
            </div>
            <?php
        } else {
            echo '<p>No Partner found</p>';
        }
        ?>
    </div>
    <?php
}