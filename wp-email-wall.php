<?php
/*
Plugin Name:  WP Email Wall Plugin
Plugin URI:   https://kojiflowers.com
Description:  A Wordpress plugin that prevents blocked email domains from registering as a user and removes any existing users with blocked email domains
Version:      1.0
Author:       Koji Flowers
Author URI:   https://kojiflowers.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

include('helpers/settings.php');


function wp_email_wall_check_email( $errors, $sanitized_user_login, $user_email ) {

    $options = get_option( 'blocked_emails' );

    // convert list of email domains to array
    $banned_emails = explode(',',$options['blocked_email'] );

    // loop banned email array and check domains against current email
    foreach($banned_emails as $key => $banned_email){
        if (strpos($user_email, trim($banned_email)) !== false) {
            $errors->add( 'email_wall_error', __( '<strong>ERROR</strong>: Invalid Email', 'my_textdomain' ) );
            return $errors;
        }
    }

    return $errors;
}


/**
 * Actions
 */

// add registration error filter that checks user emails against banned domain list
add_filter( 'registration_errors', 'wp_email_wall_check_email', 10, 3 );