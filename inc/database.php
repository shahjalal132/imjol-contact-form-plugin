<?php
global $wpdb;

$all_data = $_POST;

$software       = isset( $all_data['software'] ) ? $all_data['software'] : null;
$software_value = isset( $software ) ? 1 : 0;

$website       = isset( $all_data['website'] ) ? $all_data['website'] : null;
$website_value = isset( $website ) ? 1 : 0;

$mobile_app       = isset( $all_data['mobileApp'] ) ? $all_data['mobileApp'] : null;
$mobile_app_value = isset( $mobile_app ) ? 1 : 0;

$customBudget    = isset( $all_data['customBudget'] ) ? $all_data['customBudget'] : null;
$select_budget   = isset( $all_data['budget'] ) ? $all_data['budget'] : null;
$fullBudget      = $customBudget . " " . $select_budget;
$trimFullBudget  = trim( $fullBudget );

$customDeadline    = isset( $all_data['customProjectDeadline'] ) ? $all_data['customProjectDeadline'] : null;
$select_deadline   = isset( $all_data['deadline'] ) ? $all_data['deadline'] : null;
$fullDeadline      = $customDeadline . " " . $select_deadline;
$trimDeadline      = trim( $fullDeadline );

// Cleaned budget and deadline values
$cleanFullBudget = str_replace("Budget Planner", "", $trimFullBudget);
$cleanFullDeadline = str_replace(" Preferred Project Duration", "", $trimDeadline);

$requirement     = isset( $all_data['requirement'] ) ? $all_data['requirement'] : null;
$newRequirement  = isset( $all_data['newRequirement'] ) ? $all_data['newRequirement'] : null;
// do foreach loop in $newRequirement and send it to $fullRequirement single string
$customRequirement = $requirement;
// $customRequirement = '';
if (!empty($newRequirement)) {
    foreach ($newRequirement as $requirement) {
        $customRequirement .= ', ' . $requirement;
    }
}
$fullRequirement = $customRequirement;

$first_name    = isset( $all_data['firstName'] ) ? $all_data['firstName'] : null;
$address       = isset( $all_data['address'] ) ? $all_data['address'] : null;
$email         = isset( $all_data['email'] ) ? $all_data['email'] : null;
$number        = isset( $all_data['number'] ) ? $all_data['number'] : null;
$watsAppNumber = isset( $all_data['watsAppNumber'] ) ? $all_data['watsAppNumber'] : null;

// Get the admin username dynamically
$admin_users    = get_users( array( 'role' => 'administrator' ) );
$admin_username = !empty( $admin_users ) ? $admin_users[0]->user_login : 'Admin';

// Get the admin email dynamically
$admin_email = get_option( 'admin_email' );

$data = [
    'first_name'  => $first_name,
    'address'     => $address,
    'email'       => $email,
    'phone'       => $number,
    'whatsapp'    => $watsAppNumber,
    'mobile_app'  => $mobile_app_value,
    'website'     => $website_value,
    'software'    => $software_value,
    'requirement' => $fullRequirement,
    'budget'      => $cleanFullBudget,      // Use cleaned budget value
    'deadline'    => $cleanFullDeadline,    // Use cleaned deadline value
];

// Table name
$table_name = $wpdb->prefix . 'imjol_requirement_forms';

// Insert data into the database
if ( !empty( $first_name ) ) {
    $wpdb->insert( $table_name, $data );

    // Check if the data was successfully inserted and send an email
    if ( $wpdb->insert_id ) {
        // Send email
        $to      = $admin_email;
        $subject = 'New Form Submission from - ' . $first_name;
        $message = "A new form submission has been received from $first_name. Here is the information:\r\n" .
            "Name: $first_name\r\n" .
            "Email: $email\r\n" .
            "Address: $address\r\n" .
            "Requirement: $fullRequirement\r\n" .
            "Budget: $cleanFullBudget\r\n" .
            "Deadline: $cleanFullDeadline";

        $headers = 'From: ' . $email; // Set the sender's email address

        // Send the email
        $mailSuccess = mail( $to, $subject, $message, $headers );

        // Check if the email was sent successfully
        if ( $mailSuccess ) {
            // Redirect to the thanks page
            wp_redirect( home_url( '/success-page/' ) );
            exit;
        } else {
            echo 'Email not sent. Please try again.';
        }
    } else {
        echo 'form not send. Please try again.';
    }
}


function display_form_data_callback() {
    echo '<pre>';
    print_r( $all_data );
    wp_die();
}
add_shortcode( 'display_form_data', 'display_form_data_callback' );
?>