<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	//check if is admin and verify the nonce
	if( is_admin() && wp_verify_nonce( $nonce, 'rename-plugin-dir' ) ) {
		// rename plugins folder if /plugins exists and /plugins.origial does not
		if (file_exists( WP_CONTENT_DIR . '/plugins' ) && !file_exists( WP_CONTENT_DIR . '/plugins.original' )) {
	            rename( WP_CONTENT_DIR . '/plugins' , WP_CONTENT_DIR . '/plugins.original' );
?>
<script type="text/javascript">
		window.location = "<?php echo admin_url(); ?>"
</script>
<?php } else wp_die( 'There is already a /plugins.original directory', 'Error'); // if directory should be renamed
} else wp_die( 'You do not have permission to do this', 'Error'); // if is admin
?>
