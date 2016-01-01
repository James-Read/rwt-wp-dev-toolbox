<?php
/*
Plugin Name: WP Developers Toolbox
Plugin URI: https://readwebtechnology.com/
Description: WP Developers Toolbox
Version: 1.0
Author: James Read
Author URI: https://readwebtechnology.com/
License: GPLv2
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Assign global variables
$plugin_url = plugins_url( '' , __FILE__ );
$options = array();
$display_json = false;
// Include pluggable.php
require_once(ABSPATH . 'wp-includes/pluggable.php');
// Copy file for renaming the plugins directory function
    $file = plugin_dir_path( __FILE__ ) . 'rwt_toolbox_rename_plugins_dir.php';
    $newfile = WP_CONTENT_DIR . '/rwt_toolbox_rename_plugins_dir.php';

    copy($file, $newfile);
// Turn on error reporting
function rwt_wp_debug_mode_switch() {

        error_reporting( E_ALL );

        if ( WP_DEBUG_DISPLAY )
            ini_set( 'display_errors', 1 );
        elseif ( null !== WP_DEBUG_DISPLAY )
            ini_set( 'display_errors', 0 );

        if ( WP_DEBUG_LOG ) {
            ini_set( 'log_errors', 1 );
            ini_set( 'error_log', WP_CONTENT_DIR . '/debug.log' );
        }
        error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
    if ( defined( 'XMLRPC_REQUEST' ) )
        ini_set( 'display_errors', 0 );
}//wp_debug_mode_switch();

// Add a menu in the WP admin bar
function rwt_debug_switch_menu() {
    global $wp_admin_bar;

    $options = get_option( 'rwt_debug_switcher_options' );
    $switch_option_on = $options['switch_option_on'];
    $show_admin_bar = $options['wp_admin_bar_option'];
    if ($switch_option_on == 'off') {
        $debug_toggle = 'ON';
    }
    if ($switch_option_on == 'on') {
        $debug_toggle = 'OFF';
    }
    if ($show_admin_bar == 'hide') {
        $admin_bar_toggle = 'SHOW';
    }
    if ($show_admin_bar == 'show') {
        $admin_bar_toggle = 'HIDE';
    }

    $menu_id = 'debug_switch';
    $wp_admin_bar->add_menu(array('id' => $menu_id, 'title' => __('Debug Switcher'), 'href' => admin_url( 'options-general.php?page=wp_developers_toolbox' )));
    $wp_admin_bar->add_menu(array('parent' => $menu_id, 'title' => __('Debug Switcher Options'), 'id' => 'debug_switch_options', 'href' =>  admin_url( 'options-general.php?page=wp_developers_toolbox' )));
    $wp_admin_bar->add_menu(array('parent' => $menu_id, 'title' => 'Toggle Debug Mode : ' . $debug_toggle, 'id' => 'debug_switch_toggle', 'href'  => admin_url( 'options-general.php?page=wp_developers_toolbox&debug=toggle')));
    $wp_admin_bar->add_menu(array('parent' => $menu_id, 'title' => 'Toggle WP Admin Bar to: ' . $admin_bar_toggle, 'id' => 'debug_wp_admin_toggle', 'href'  => admin_url( 'options-general.php?page=wp_developers_toolbox&wp_admin_bar=toggle')));
}
add_action('admin_bar_menu', 'rwt_debug_switch_menu', 2000);

// the dashboard page
function rwt_wp_developers_toolbox_page() {
    if( !current_user_can( 'manage_options' ) ) {
        wp_die( 'You do not have sufficient permissions to access this page.' );
    }

    global $plugin_url;
    global $options;
    global $display_json;

    // default options
    $options = get_option( 'rwt_debug_switcher_options' );
    if( $options == false ) {
        $options['switch_option_on'] = 'off';
        $options['admin_option'] = 'on';
        $options['wp_admin_bar_option'] = 'show_admin_bar';
        $options['debug_switcher_log'] = 'off';
        $options['wp_admin_bar_option'] = 'show';

        update_option( 'rwt_debug_switcher_options', $options );
    }
    // reset rename plugins directory
    $options['rename_plugins_directory'] = 'off';
    update_option( 'rwt_debug_switcher_options', $options );

    if( isset( $_POST['debug_switcher_form_submitted'] ) ) {
        $hidden_field = sanitize_text_field( $_POST['debug_switcher_form_submitted'] );
        if( $hidden_field == 'Y' ) {
            check_admin_referer( 'update-debug-settings_' );
            $switch_option_on = sanitize_text_field( $_POST['switch_option_on'] );
            $admin_option = sanitize_text_field( $_POST['admin_option'] );
            $wp_admin_bar_option = sanitize_text_field( $_POST['wp_admin_bar_option'] );
            $debug_switcher_log = sanitize_text_field( $_POST['debug_switcher_log'] );
            $delete_error_log = sanitize_text_field( $_POST['delete_error_log'] );
            $rename_plugins_directory = sanitize_text_field( $_POST['rename_plugins_directory'] );

            $options['switch_option_on'] = $switch_option_on;
            $options['admin_option'] = $admin_option;
            $options['wp_admin_bar_option'] = $wp_admin_bar_option;
            $options['debug_switcher_log'] = $debug_switcher_log;
            $options['delete_error_log'] = $delete_error_log;
            $options['last_updated'] = time();

            update_option( 'rwt_debug_switcher_options', $options );
        }
    }
    // rename /plugins directory
    function rwt_rename_plugins_directory() {
        $nonce = wp_create_nonce( 'rename-plugin-dir' );
        require(WP_CONTENT_DIR . '/rwt_toolbox_rename_plugins_dir.php');
        exit;
    }
    if ( $rename_plugins_directory == 'rename' ) {
        rwt_rename_plugins_directory();
    }
    $options = get_option( 'rwt_debug_switcher_options' );
    if( $options != '' ) {
        $switch_option_on = $options['switch_option_on'];
        $admin_option = $options['admin_option'];
        $wp_admin_bar_option = $options['wp_admin_bar_option'];
        $debug_switcher_log = $options['debug_switcher_log'];
    }
    // delete error log
    function rwt_delete_error_log() {
        if (file_exists(WP_CONTENT_DIR . '/debug.log' )) {
        unlink( WP_CONTENT_DIR . '/debug.log' );
        }
    }
    if ( $delete_error_log == 'delete' ) {
        rwt_delete_error_log();
        $notification = '<h4>Error log deleted</h4>';
    }

    require( 'options-page-wrapper.php' );
} // the dashboard page function
// toggle debug mode from WP admin bar
if  ( sanitize_text_field( $_GET["debug"] == 'toggle') ) {
    $options = get_option( 'rwt_debug_switcher_options' );
    $switch_option_on = $options['switch_option_on'];
    if ($switch_option_on == 'on') {
        $options['switch_option_on'] = 'off';
    }
    if ($switch_option_on == 'off') {
        $options['switch_option_on'] = 'on';
    }
    update_option( 'rwt_debug_switcher_options', $options );
    if ( wp_get_referer() ) {
        wp_safe_redirect( wp_get_referer() );
    }
}
// toggle admin bar visability from WP admin bar
if  ( sanitize_text_field( $_GET["wp_admin_bar"] == 'toggle') ) {
    $options = get_option( 'rwt_debug_switcher_options' );
    $show_admin_bar_options = $options['wp_admin_bar_option'];
    if ($show_admin_bar_options == 'show') {
        $options['wp_admin_bar_option'] = 'hide';
    }
    if ($show_admin_bar_options == 'hide') {
        $options['wp_admin_bar_option'] = 'show';
    }
    update_option( 'rwt_debug_switcher_options', $options );
    if ( wp_get_referer() ) {
        wp_safe_redirect( wp_get_referer() );
    }
}
// switch debug mode on or off from dashboard settings
function rwt_rwt_debug_switcher_options() {
    $options = get_option( 'rwt_debug_switcher_options' );
    if( $options != '' ) {
        $switch_option_on = $options['switch_option_on'];
        $admin_option = $options['admin_option'];
    }
    if ( $switch_option_on == 'on' ) {
        if ( $admin_option == 'on' ) {
           if( current_user_can( 'manage_options' ) ) {
                $debug_switcher = true;
            }
        }
        if ( $admin_option == 'off' ) {
            $debug_switcher = true;
        }
    }
    return $debug_switcher;
}
if ( rwt_rwt_debug_switcher_options() == true ) {
    rwt_wp_debug_mode_switch();
}
// show admin bar / log errors from dashboard options
$options = get_option( 'rwt_debug_switcher_options' );
$wp_admin_bar_option = $options['wp_admin_bar_option'];
$debug_switcher_log = $options['debug_switcher_log'];

// hide the admin bar
if ( $wp_admin_bar_option == 'hide' ) {
    show_admin_bar( false );
}
// turn on error logging
function rwt_debug_switcher_logger() {
    ini_set( 'log_errors', 1 );
    ini_set( 'error_log', WP_CONTENT_DIR . '/debug.log' );
}
if ( $debug_switcher_log == 'on' ) {
    rwt_debug_switcher_logger();
}

function rwt_wp_dev_tool_box_menu(){
  add_menu_page('WP Developers Toolbox', 'WP Developers Toolbox', 'manage_options', 'wp_developers_toolbox', 'rwt_wp_developers_toolbox_page');
  add_submenu_page( 'wp_developers_toolbox', 'System Info', 'System Info', 'manage_options', 'system-info', 'rwt_system_info');
  add_submenu_page( 'wp_developers_toolbox', 'Database Export', 'Database Export', 'manage_options', 'database-info', 'rwt_database_export');
}
add_action('admin_menu', 'rwt_wp_dev_tool_box_menu');

function rwt_system_info(){
    echo '<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
    <h2>System Info</h2>';
    ob_start();
    phpinfo();
    $pinfo = ob_get_contents();
    ob_end_clean();

    $pinfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);
    echo $pinfo;
    echo '</div>';
}
function rwt_database_export(){
    echo '<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
    <h2>Database Export</h2></div>';
    require_once( 'db_backup.php' );
}