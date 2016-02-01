<?php
/*
* Plugin Name: WP Developer's Toolbox
* Plugin URI: https://readwebtechnology.com/
* Description: WP Developers Toolbox
* Version: 1.0
* Text Domain: wp-debug-switcher
* Domain Path: /lang/
* Author: James Read
* Author URI: https://readwebtechnology.com/
* License: GPLv2
*/
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}
require_once(ABSPATH . 'wp-includes/pluggable.php');
$plugin_url = plugins_url('', __FILE__);

class rwtToolboxOptions
{
    public $debug_mode;
    public $public_view_errors;
    public $debug_log;
    public $hide_wp_admin_bar;
    public $white_listed_ip;
    public $delete_error_log;
    public $name;

    protected function __construct()
    {
        $this->debug_mode = $debug_mode;
        $this->public_view_errors = $public_view_errors;
        $this->debug_log = $debug_log;
        $this->hide_wp_admin_bar = $hide_wp_admin_bar;
        $this->white_listed_ip =  $white_listed_ip;
        $this->delete_error_log = $delete_error_log;
        $this->name = $name;
        $this->rwtGetOptions();
    }
    // Get options from database
    public function rwtGetOptions()
    {
        $options = get_option('rwtToolboxOptions');
        if ($options != false) {
            $this->debug_mode = $options->params->debug_mode;
            $this->public_view_errors = $options->params->public_view_errors;
            $this->debug_log = $options->params->debug_log;
            $this->hide_wp_admin_bar = $options->params->hide_wp_admin_bar;
            $this->white_listed_ip = $options->params->white_listed_ip;
        }
        return $this;
    }
    // Submit options
    public function rwtSubmitOptions()
    {
        if (isset($_POST['rwtToolboxOptions_form_submitted'])) {
            $hidden_field = sanitize_text_field($_POST['rwtToolboxOptions_form_submitted']);
            if ($hidden_field == 'Y') {
                check_admin_referer('update-debug-settings_');
                $this->debug_mode = sanitize_text_field($_POST['debug_mode']);
                $this->public_view_errors = sanitize_text_field($_POST['public_view_errors']);
                $this->debug_log = sanitize_text_field($_POST['debug_log']);
                $this->hide_wp_admin_bar = sanitize_text_field($_POST['hide_wp_admin_bar']);
                $this->white_listed_ip = sanitize_text_field($_POST['white_listed_ip']);
                $this->rename_plugins_directory = sanitize_text_field($_POST['rename_plugins_directory']);
                $this->white_listed_ip = sanitize_text_field($_POST['white_listed_ip']);
                $this->delete_error_log = sanitize_text_field($_POST['delete_error_log']);
                return $this;
            }// if $hidden_field == 'Y'
        }
    }// public function rwtSubmitOptions()
    // Set options
    public function rwtSetOptions()
    {
        //$this->rwt_options = new rwtToolboxOptions();
        $this->rwt_options->name = 'rwtToolboxOptions';
        $this->rwt_options->params->debug_mode = $this->debug_mode;
        $this->rwt_options->params->public_view_errors = $this->public_view_errors;
        $this->rwt_options->params->debug_log = $this->debug_log;
        $this->rwt_options->params->hide_wp_admin_bar = $this->hide_wp_admin_bar;
        $this->rwt_options->params->white_listed_ip = $this->white_listed_ip;
        $this->rwt_options->params->last_updated = time();
        return $this->rwt_options;
    }
    // Update options
    public function rwtUpdateOptions()
    {
        update_option('rwtToolboxOptions', $this->rwtSetOptions());
    }
}// class rwtToolboxOptions

class rwtDevToolbox
{
    public $user_ip;
    public $user_ip_match;
    public $debug_switcher;
    public $admin_option;

    protected function __construct()
    {
        $this->user_ip = 'false';
        $this->user_ip_match = 'false';
        $this->debug_switcher = 'false';
        $this->admin_option = 'false';
    }

    protected function rwtGetUtilities()
    {
        $this->user_ip = 'false';
        $this->user_ip_match = $this->rwtTestIp();
        $this->debug_switcher = 'false';
        $this->admin_option = 'false';
    }

    // Copy file for renaming the plugins directory function
    public function rwtAddRenamePluginsFile()
    {
        $file = plugin_dir_path(__FILE__) . 'rwt_toolbox_rename_plugins_dir.php';
        $newfile = WP_CONTENT_DIR . '/rwt_toolbox_rename_plugins_dir.php';

        copy($file, $newfile);
    }

    // Error reporting
    public function rwtDebugMode()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        error_reporting(E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR);
    }// Error reporting

    // White list IP address to view errors
    // Get user's IP address
    public function rwtGetUserIp()
    {
        $user_ip = 'Not found';
        if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
            //shared connection
            $this->user_ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //from proxy
            $this->user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $this->user_ip = $_SERVER['REMOTE_ADDR'];
        }
        return $this->user_ip;
    }
    // Match the IP
    public function rwtTestIp()
    {
        $rwt_toolbox_opts = new rwtToolboxOptions('false', 'false', 'false', 'false', '', 'false', 'rwtToolboxOptions');
        if ($this->user_ip == $rwt_toolbox_opts->white_listed_ip) {
            $this->user_ip_match = 'true';
        }
        return $this->user_ip_match;
    }
    // Rename /plugins directory
    public function rwtRenamePluginsDirectory()
    {
        $nonce = wp_create_nonce('rename-plugin-dir');
        require(WP_CONTENT_DIR . '/rwt_toolbox_rename_plugins_dir.php');
        exit;
    }
    // Log errors
    public function rwtDebugLogger()
    {
        ini_set('log_errors', 1);
        ini_set('error_log', WP_CONTENT_DIR . '/debug.log');
    }
    // Print system info
    public function rwtSystemInfo()
    {
        $system_info_title = __('System Info', ‘wp-debug-switcher’);
        echo '<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
        <h2>'.$system_info_title.'</h2>';
        ob_start();
        phpinfo();
        $pinfo = ob_get_contents();
        ob_end_clean();

        $pinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo);
        echo $pinfo;
        echo '</div>';
    }
    public function rwtDatabaseExport()
    {
        $database_export_title = __('Database Export', ‘wp-debug-switcher’);
        echo '<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
        <h2>'.$database_export_title.'</h2></div>';
        require_once('db_backup.php');
    }
    // // switch debug mode on or off from dashboard settings
    // public function rwt_debug_switcher_options() {
    //     $rwt_toolbox_opts = new rwtToolboxOptions();
    //     $this->debug_switcher = false;
    //     $debug_mode = $rwt_toolbox_opts->debug_mode;
    //     $public_view_errors = $rwt_toolbox_opts->public_view_errors;
    //     if ($debug_mode == 'true') {
    //         if ($public_view_errors == 'false') {
    //            if(current_user_can('manage_options')) {
    //                 $this->debug_switcher = true;
    //             }
    //         }
    //         if ($this->public_view_errors == 'true') {
    //             $this->debug_switcher = true;
    //         }
    //         if ($this->user_ip_match == true) {
    //             $this->debug_switcher = true;
    //         }
    //     }
    //     return $this->debug_switcher;
    // }
    // Delete error log
    public function rwt_delete_error_log()
    {
        if (file_exists(WP_CONTENT_DIR . '/debug.log')) {
            unlink(WP_CONTENT_DIR . '/debug.log');
        }
    }
    // The switching logic
    public function rwt_toolbox_switcher()
    {
        $rwt_toolbox_opts = new rwtToolboxOptions('false', 'false', 'false', 'false', '', 'false', 'rwtToolboxOptions');
        $rwt_toolbox_utilities = new rwtDevToolbox();
        // if ($rename_plugins_directory == 'rename') {
        //     rwtRenamePluginsDirectory();
        // }
        // if ($debug_switcher_log == 'on') {
        //     rwt_debug_switcher_logger();
        // }

        // if ($delete_error_log == 'delete') {
        //     rwt_delete_error_log();
        //     $notification = '<h4>Error log deleted</h4>';
        // }
        // var_dump($rwt_toolbox_opts);
        // if ($debug_mode == 'true') {
        //     if ($public_view_errors == 'false') {
        //        if(current_user_can('manage_options')) {
        //             $this->debug_switcher = true;
        //         }
        //     }
        //     if ($this->public_view_errors == 'true') {
        //         $this->debug_switcher = true;
        //     }
        //     if ($this->user_ip_match == true) {
        //         $this->debug_switcher = true;
        //     }
        if ($rwt_toolbox_opts->debug_mode === 'true' && current_user_can('manage_options') || $rwt_toolbox_opts->public_view_errors === 'true' && $rwt_toolbox_opts->debug_mode === 'true') {
             $rwt_toolbox_utilities->rwtDebugMode();
        }
        if ($rwt_toolbox_opts->debug_log === 'true') {
            $rwt_toolbox_utilities->rwtDebugLogger();
        }
        if ($rwt_toolbox_opts->delete_error_log === 'delete') {
            $rwt_toolbox_utilities->rwt_delete_error_log();
            $notification = '<h4>Error log deleted</h4>';
        }
        // Hide the admin bar
        if ($rwt_toolbox_opts->hide_wp_admin_bar === 'true') {
            show_admin_bar(false);
        }
    }
}// class rwtDevToolbox

function rwt_options_page()
{
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    $rwt_toolbox = new rwtToolboxOptions('false', 'false', 'false', 'false', '', 'false', 'rwtToolboxOptions');
    $rwt_toolbox->rwtGetOptions();
    $rwt_toolbox->rwtSetOptions();
    $rwt_toolbox->rwtSubmitOptions();
    $rwt_toolbox->rwtUpdateOptions();

    $rwt_toolbox_utilities = new rwtDevToolbox();
    // $rwt_toolbox_utilities->rwtAddRenamePluginsFile();
    // $rwt_toolbox_utilities->rwtGetUserIp();
    // $rwt_toolbox_utilities->rwtTestIp();
    // $rwt_toolbox_utilities->rwt_debug_switcher_options();
    // $rwt_toolbox_utilities->rwtDebugLogger();
    // $rwt_toolbox_utilities->rwtDebugMode();
    //var_dump($rwt_toolbox_utilities);
    require('options-page-wrapper.php');
}

function rwt_wp_dev_tool_box_menu()
{
    $developers_toolbox_title = __('WP Developer\'s Toolbox', ‘wp-debug-switcher’);
    $system_info_title = __('System Info', ‘wp-debug-switcher’);
    $database_export_title = __('Database Export', ‘wp-debug-switcher’);

    add_menu_page($developers_toolbox_title, $developers_toolbox_title, 'manage_options', 'wp_developers_toolbox', 'rwt_options_page');
    add_submenu_page('wp_developers_toolbox', $system_info_title, $system_info_title, 'manage_options', 'system-info', 'rwtSystemInfo');
    add_submenu_page('wp_developers_toolbox', $database_export_title, $database_export_title, 'manage_options', 'database-info', 'rwtDatabaseExport');
}
add_action('admin_menu', 'rwt_wp_dev_tool_box_menu');

// Add menu to the WP admin bar
function rwt_debug_switch_menu()
{
    global $wp_admin_bar;
    $debug_true_label = __('ON', ‘wp-debug-switcher’);
    $debug_false_label = __('OFF', ‘wp-debug-switcher’);
    $public_view_errors_true_label = __('SHOW', ‘wp-debug-switcher’);
    $public_view_errors_false_label = __('HIDE', ‘wp-debug-switcher’);

    if ($rwt_toolbox->debug_mode == 'true') {
        $debug_toggle = $debug_false_label;
    } else {
        $debug_toggle = $debug_true_label;
    }
    if ($rwt_toolbox->public_view_errors == 'true') {
        $admin_bar_toggle = $public_view_errors_false_label;
    } else {
        $admin_bar_toggle = $public_view_errors_true_label;
    }

    $menu_id = 'debug_switch';
    $wp_admin_bar->add_menu(array('id' => $menu_id, 'title' => __('Developer\'s Toolbox', ‘wp-debug-switcher’), 'href' => admin_url('options-general.php?page=wp_developers_toolbox')));
    $wp_admin_bar->add_menu(array('parent' => $menu_id, 'title' => __('Developer\'s Toolbox : Options', ‘wp-debug-switcher’), 'id' => 'debug_switch_options', 'href' =>  admin_url('options-general.php?page=wp_developers_toolbox')));
    $wp_admin_bar->add_menu(array('parent' => $menu_id, 'title' =>  __('Toggle Debug Mode : ', ‘wp-debug-switcher’) . $debug_toggle, 'id' => 'debug_switch_toggle', 'href'  => admin_url('options-general.php?page=wp_developers_toolbox&debug=toggle')));
    $wp_admin_bar->add_menu(array('parent' => $menu_id, 'title' => __('Toggle WP Admin Bar to: ', ‘wp-debug-switcher’) . $admin_bar_toggle, 'id' => 'debug_wp_admin_toggle', 'href'  => admin_url('options-general.php?page=wp_developers_toolbox&wp_admin_bar=toggle')));
}
add_action('admin_bar_menu', 'rwt_debug_switch_menu', 2000);
$rwt_toolbox_utilities = new rwtDevToolbox();
$rwt_toolbox_utilities->rwt_toolbox_switcher();
