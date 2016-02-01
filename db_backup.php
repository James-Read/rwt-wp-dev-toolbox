<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}
function rwt_export_db()
{
    global $wpdb;
// Check if directory is already created and has the proper permissions
    $backup_path = WP_CONTENT_DIR . '/BACKUP_DIR';
    if (!file_exists($backup_path)) {
        mkdir($backup_path, 0755);
    }
    if (!is_writable($backup_path)) {
        chmod($backup_path, 0755);
    }
    // Create a ".htaccess" file, to restrict direct accss to the backup-directory
    $content = 'deny from all';
    $file = new SplFileObject($backup_path . '/.htaccess', "w");
    // Create the .sql file
    $file->fwrite($content);
    $file_name = '/database-' . time() . '.sql';
    $file_path = $backup_path . $file_name;
    $file = fopen($file_path, 'w');
    // Get a list of the tables
    $sql = "SHOW TABLES LIKE '%'";
    $tables = $wpdb->get_results($sql);
    // Loop through tables and write them to the export file
    foreach ($tables as $table => $value) {
        foreach ($value as $table_name) {
            $schema = $wpdb->get_row('SHOW CREATE TABLE ' . $table_name, ARRAY_A);
            fwrite($file, 'DROP TABLE IF EXISTS ' . $table_name . ' ; ');
            fwrite($file, $schema['Create Table'] . ';' . PHP_EOL);
            $rows = $wpdb->get_results('SELECT * FROM ' . $table_name, ARRAY_A);
            if ($rows) {
                fwrite($file, 'INSERT INTO ' . $table_name . ' VALUES ');
                $total_rows = count($rows);
                $counter = 1;
                foreach ($rows as $row => $fields) {
                    $line = '';
                    foreach ($fields as $key => $value) {
                        $value = addslashes($value);
                        $line .= '"' . $value . '",';
                    }
                    $line = '(' . rtrim($line, ',') . ')';
                    if ($counter != $total_rows) {
                        $line .= ',' . PHP_EOL;
                    }
                    fwrite($file, $line);
                    $counter++;
                }
                fwrite($file, '; ' . PHP_EOL);
            }
            echo '<pre>Table exported : ' . $table_name . '<br>';
        }
    }
    fclose($file);
    $file_url = site_url() . '/wp-content/BACKUP_DIR'  . $file_name; ?>
<h2><?php _e('Export complete!', ‘wp-debug-switcher’) ?></h2>
<h3><?php _e('File Path : ', ‘wp-debug-switcher’) ?><?php echo $file_path; ?></h3>
<h4><?php _e('(Direct access is disabled with .htaccess for security - please use FTP / SSH to download)', ‘wp-debug-switcher’) ?></h4>
<?php /*  <h3>Url Path : <?php echo $file_url; ?></h3> */ ?>
<?php /* <a href="<?php echo $file_url; ?>" download>Download</a> */ ?>
<?php } // rwt_export_db ?>
<form name="rwt_export_db" method="post" action="">
<?php wp_nonce_field('rwt-export-db_'); ?>
<input type="hidden" name="rwt_export_db_form_submitted" value="Y">
<p><?php _e('Click the Export button below to export every table in the current database, to a .sql file.', ‘wp-debug-switcher’) ?><br><?php _e(' The file also includes “DROP TABLE IF EXISTS” for quick importing.', ‘wp-debug-switcher’) ?></p>
<p>
<input class="button-primary" type="submit" name="rwt_export_db_submit" value="<?php _e('Export', ‘wp-debug-switcher’) ?>" />
</p>
</form>
<?php if (isset($_POST['rwt_export_db_form_submitted'])) {
    $hidden_field = sanitize_text_field($_POST['rwt_export_db_form_submitted']);
    if ($hidden_field == 'Y') {
        check_admin_referer('rwt-export-db_');
        rwt_export_db();
    }
} // rwt_export_db_form_submitted ?>
