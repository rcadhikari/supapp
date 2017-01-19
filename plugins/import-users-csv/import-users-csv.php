<?php
/*
Plugin Name: RC Import Users from CSV
Plugin URI: https://github.com/rcadhikari
Description: This is a simple plugin to import the users from CSV file.
Author: RC Adhikari
Version: 1.0
Author URI: https://github.com/rcadhikari
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$url_plugin = WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ), "", plugin_basename( __FILE__ ) );

// Listing the array with Users table columns.
$wp_users_fields = array( "ID", "user_login", "user_pass", "user_nicename", "user_email","user_url", "display_name");
                    /* User Meta values:
                        usermeta values: "nickname", "first_name", "last_name", "description"
                    */
$wp_min_fields = array("Username", "Email");

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if( is_plugin_active( 'buddypress/bp-loader.php' ) ){
    if ( defined( 'BP_VERSION' ) )
        iuc_loader();
    else
        add_action( 'bp_init', 'iuc_loader' );
}
else
    iuc_loader();

function iuc_loader(){
    require_once( "lib/import-users-core.php" );
}

function iuc_init(){
    iuc_activate();
}

function iuc_activate(){
    add_option( "iuc_columns" );
}

function iuc_deactivate(){
    wp_clear_scheduled_hook( 'iuc_cron' );
}

function iuc_delete_options(){
    delete_option( "iuc_columns" );
}

function iuc_check_options(){
}

function iuc_admin_menu() {

    add_submenu_page(
        'options-general.php',
        __( 'Import Users from CSV', 'import-users-csv' ),
        __( 'Import users', 'import-users-csv' ),
        'create_users',
        'iuc',
        'iuc_options'
    );
}

function iuc_admin_tabs( $current = 'import-users' ) {
    $tabs = array(
        'import-users' => __( 'Import users from CSV', 'import-users-csv' )
    );

    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        $href = "?page=iuc&tab=$tab";
        $target = "_self";

        echo "<a class='nav-tab$class' href='$href' target='$target'>$name</a>";

    }
    echo '</h2>';
}

register_activation_hook( __FILE__,'iuc_init' );
register_deactivation_hook( __FILE__, 'iuc_deactivate' );
add_action( "plugins_loaded", "iuc_init" );
add_action( "admin_menu", "iuc_admin_menu" );