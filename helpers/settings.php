<?php

class WpEmailWallSettings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin',
            'WP Email Wall Settings',
            'manage_options',
            'wp-email-wall-admin',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'blocked_emails' );
        ?>
        <div class="wrap">
            <h1>My Settings</h1>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'wp-email-wall-admin' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'my_option_group', // Option group
            'blocked_emails', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Block Email Domains', // Title
            array( $this, 'print_section_info' ), // Callback
            'wp-email-wall-admin' // Page
        );

        add_settings_field(
            'blocked_email', // ID
            'Blocked Email Domains List', // Title
            array( $this, 'blocked_email_callback' ), // Callback
            'wp-email-wall-admin', // Page
            'setting_section_id' // Section           
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     * @return array
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['blocked_email'] ) )
            $new_input['blocked_email'] = sanitize_text_field( $input['blocked_email'] );

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter the email domains you would like to block below (comma separated):';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function blocked_email_callback()
    {

        $blocked_email = $this->options['blocked_email'];
        printf(
            '<textarea style="width:500px; height:200px;" id="blocked_emails" name="blocked_emails[blocked_email]" />%s</textarea>',
            isset( $blocked_email ) ? esc_attr( $blocked_email) : ''
        );

    }
}

if( is_admin() )
    $my_settings_page = new WpEmailWallSettings();