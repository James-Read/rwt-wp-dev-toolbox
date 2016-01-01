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
	<h2>WP Developers Toolbox Plugin</h2>
	<h3></h3>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<!-- /.main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h3><span>Read Web Technology - WordPress developer's toolbox</span></h3>
						<div class="inside">
							<form name="rwt_debug_switcher_options_class_form" method="post" action="">
							<input type="hidden" name="debug_switcher_form_submitted" value="Y">
							<table class="form-table">
								<tr>
									<td>
										<label for="switch_option_on">Debug Mode - display errors : </label>
									</td>
									<td>
										<input name="switch_option_on" type="radio" value="on" <?php if ( $switch_option_on == "on" ) { echo 'checked'; } ?>>On
									    <br>
									    <input name="switch_option_on" type="radio" value="off" <?php if ( $switch_option_on == "off" ) { echo 'checked'; } ?>>Off
									</td>
								</tr>
								<tr>
									<td>
										<label for="admin_option">Display errors to logged in admin users only : </label>
									</td>
									<td>
										<input name="admin_option" type="radio" value="on" <?php if ( $admin_option == "on" ) { echo 'checked'; } ?>>On
									    <br>
									    <input name="admin_option" type="radio" value="off" <?php if ( $admin_option == "off" ) { echo 'checked'; } ?>>Off !Not recomended
									</td>
								</tr>
								<tr>
									<td>
										<label for="debug_switcher_log">Print errors to a log file : </label>
										<p>/wp-content/debug.log</p>
									</td>
									<td>
										<input name="debug_switcher_log" type="radio" value="on" <?php if ( $debug_switcher_log == "on" ) { echo 'checked'; } ?>>On
									    <br>
									    <input name="debug_switcher_log" type="radio" value="off" <?php if ( $debug_switcher_log == "off" ) { echo 'checked'; } ?>>Off
									</td>
								</tr>
								<tr>
									<td>
										<label for="delete_error_log">Delete log file : </label>
									</td>
									<td>
										<input name="delete_error_log" type="checkbox" value="delete">Delete log file (cannot be undone!)
										<?php if( $notification ) { echo $notification; }?><h4>
									</td>
								</tr>
								<tr>
									<td>
										<label for="rename_plugins_directory">Rename plugins directory : </label>
									</td>
									<td>
										<input name="rename_plugins_directory" type="checkbox" value="rename">Rename - to quickly see if your error is in the plugins folder
										<?php if( $notification_remame_plugins ) { echo $notification_remame_plugins; }?><h4>
									</td>
								</tr>
								<tr>
									<td>
										<label for="wp_admin_bar_option">Hide WP Admin bar on the front end, when a user is logged in : </label>
									</td>
									<td>
										<input name="wp_admin_bar_option" type="radio" value="show" <?php if ( $wp_admin_bar_option == "show" ) { echo 'checked'; } ?>>Show (Defalut)
									    <br>
									    <input name="wp_admin_bar_option" type="radio" value="hide" <?php if ( $wp_admin_bar_option == "hide" ) { echo 'checked'; } ?>>Hide
									</td>
								</tr>
							</table>
							<p>
								<input class="button-primary" type="submit" name="rwt_debug_switcher_options_class_name_submit" value="Save" />
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