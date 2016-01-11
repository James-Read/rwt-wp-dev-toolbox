<?php
/* Version: 1.0
Author: James Read
Author URI: https://readwebtechnology.com/
License: GPLv2
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2><?php _e( 'WP Developer\'s Toolbox', ‘wp-debug-switcher’ ) ?></h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<!-- /.main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h3><span><?php _e( 'Read Web Technology - WordPress developer\'s toolbox', ‘wp-debug-switcher’ ) ?></span></h3>
						<div class="inside">
							<p><?php _e( 'It is advised you only use these settings within a development environment and not production.', ‘wp-debug-switcher’ ) ?></p>
							<p><?php _e( 'Please note that these settings will not overrule some other error reporting configurations, for example on your system or using WP_DEBUG - see :', ‘wp-debug-switcher’ ) ?> <a href="https://codex.wordpress.org/WP_DEBUG" target="_blank">https://codex.wordpress.org/WP_DEBUG</a></p>
							<form name="rwt_debug_switcher_options_class_form" method="post" action="">
							<?php wp_nonce_field( 'update-debug-settings_' ); ?>
							<input type="hidden" name="debug_switcher_form_submitted" value="Y">
							<table class="form-table">
								<tr>
									<td>
										<label for="switch_option_on"><?php _e( 'Debug Mode - display errors : ', ‘wp-debug-switcher’ ) ?></label>
									</td>
									<td>
										<input name="switch_option_on" type="radio" value="on" <?php if ( $switch_option_on == "on" ) { echo 'checked'; } ?>><?php _e( 'On', ‘wp-debug-switcher’ ) ?>
									    <br>
									    <input name="switch_option_on" type="radio" value="off" <?php if ( $switch_option_on == "off" ) { echo 'checked'; } ?>><?php _e( 'Off (Defalut)', ‘wp-debug-switcher’ ) ?>
									</td>
								</tr>
								<tr>
									<td>
										<label for="admin_option"><?php _e( 'Display errors to logged in admin users only :', ‘wp-debug-switcher’ ) ?> </label>
									</td>
									<td>
										<input name="admin_option" type="radio" value="on" <?php if ( $admin_option == "on" ) { echo 'checked'; } ?>><?php _e( 'On (Defalut)', ‘wp-debug-switcher’ ) ?>
									    <br>
									    <input name="admin_option" type="radio" value="off" <?php if ( $admin_option == "off" ) { echo 'checked'; } ?>><?php _e( 'Off (Not recomended in production)', ‘wp-debug-switcher’ ) ?>
									</td>
								</tr>
								<tr>
									<td>
										<label for="current_ip_address"><?php _e( 'Your current client IP address is : ', ‘wp-debug-switcher’ ) ?> </label>
									</td>
									<td>
										<?php echo rwt_get_user_ip(); ?>
									</td>
								</tr>
								<tr>
									<td>
										<br><label for="white_listed_ip"><?php _e( 'Whitelisted IP address :', ‘wp-debug-switcher’ ) ?> </label>
										<p>View errors, without needing to sign in as an administrator.</p>
									</td>
									<td>
										<input name="white_listed_ip" type="input" value="<?php echo $white_listed_ip; ?>">
								    </td>
								</tr>
								<tr>
									<td>
										<label for="debug_switcher_log"><?php _e( 'Print errors to a log file : ', ‘wp-debug-switcher’ ) ?></label>
										<p>/wp-content/debug.log</p>
									</td>
									<td>
										<input name="debug_switcher_log" type="radio" value="on" <?php if ( $debug_switcher_log == "on" ) { echo 'checked'; } ?>><?php _e( 'On', ‘wp-debug-switcher’ ) ?>
									    <br>
									    <input name="debug_switcher_log" type="radio" value="off" <?php if ( $debug_switcher_log == "off" ) { echo 'checked'; } ?>><?php _e( 'Off (Defalut)', ‘wp-debug-switcher’ ) ?>
									    <br>
									    <?php if (file_exists(WP_CONTENT_DIR . '/debug.log' )) {?>
										<a target="_blank" href="<?php echo site_url(); ?>/wp-content/debug.log"><?php _e( ' Click here to open the debug log file in a new tab', ‘wp-debug-switcher’ ) ?></a>
										<?php } ?>
									</td>
								</tr>
								<tr>
									<td>
										<label for="delete_error_log"><?php _e( 'Delete log file : ', ‘wp-debug-switcher’ ) ?></label>
									</td>
									<td>
										<input name="delete_error_log" type="checkbox" value="delete"><?php _e( 'Delete log file (Can not be undone!)', ‘wp-debug-switcher’ ) ?>
										<?php if( $notification ) { echo $notification; }?><h4>
									</td>
								</tr>
								<tr>
									<td>
										<label for="rename_plugins_directory"><?php _e( 'Rename plugins directory : <br> To quickly see if there is an error in the plugins folder.', ‘wp-debug-switcher’ ) ?></label>
									</td>
									<td>
										<input name="rename_plugins_directory" type="checkbox" value="rename"><?php _e( 'Rename /plugins to /plugins.original - Warning! This will disable all of your plugins. Ensure you have access to rename it back again.', ‘wp-debug-switcher’ ) ?>
										<?php if( $notification_remame_plugins ) { echo $notification_remame_plugins; }?><h4>
									</td>
								</tr>
								<tr>
									<td>
										<label for="wp_admin_bar_option"><?php _e( 'Hide WP Admin bar on the front end, when a user is logged in : ', ‘wp-debug-switcher’ ) ?></label>
									</td>
									<td>
										<input name="wp_admin_bar_option" type="radio" value="show" <?php if ( $wp_admin_bar_option == "show" ) { echo 'checked'; } ?>><?php _e( 'Show (Defalut)', ‘wp-debug-switcher’ ) ?>
									    <br>
									    <input name="wp_admin_bar_option" type="radio" value="hide" <?php if ( $wp_admin_bar_option == "hide" ) { echo 'checked'; } ?>><?php _e( 'Hide', ‘wp-debug-switcher’ ) ?>
									</td>
								</tr>
							</table>
							<p>
								<input class="button-primary" type="submit" name="rwt_debug_switcher_options_class_name_submit" value="<?php _e( 'Save', ‘wp-debug-switcher’ ) ?>" />
							</p>
							</form>
						</div> <!-- /.inside  test-->
					</div> <!-- /.postbox -->
			</div> <!-- /#post-body-content -->
		</div> <!-- /#post -->
	</div> <!-- /.poststuff -->
</div> <!-- /.wrap -->
<div class="clear"></div></div><!-- clear -->
<?php /*
function wp_dev_tool_box_display() {
?>
    <!-- Create a header in the default WordPress 'wrap' container -->
    <div class="wrap">
        <div id="icon-themes" class="icon32"></div>
        <?php settings_errors(); ?>

        <?php
            $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_options';{
                $active_tab = $_GET[ 'tab' ];
            } // end if
        ?>
        <h2 class="nav-tab-wrapper">
            <a href="options-general.php?page=wp_developers_toolbox&tab=display_options" class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>">System Info</a>
            <a href="options-general.php?page=wp_developers_toolbox&tab=tools_options" class="nav-tab <?php echo $active_tab == 'tools_options' ? 'nav-tab-active' : ''; ?>">Tools</a>
        </h2>
        <div class="wrap">
		<?php
			if( $active_tab == 'display_options' ) {
				// Show all information, defaults to INFO_ALL
				//phpinfo();
			} else {
        	echo 'display tools';
        } // end if/else
		?>
    </div><!-- /.wrap -->
<?php
} // end wp_dev_tool_box_display
?>
<?php wp_dev_tool_box_display() */  ?>