<?php
global $wpdb;

// Check if directory is already created and has the proper permissions
$backup_path = WP_CONTENT_DIR . '/BACKUP_DIR';

if (!file_exists($backup_path)) mkdir($backup_path , 0755);

if (!is_writable($backup_path)) chmod($backup_path , 0755);

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

foreach($tables as $table => $value) {
    foreach($value as $table_name) {
	    $schema = $wpdb->get_row('SHOW CREATE TABLE ' . $table_name, ARRAY_A);
	    fwrite($file, 'DROP TABLE IF EXISTS ' . $table_name . ' ; ');
	    fwrite($file, $schema['Create Table'] . ';' . PHP_EOL);

	    $rows = $wpdb->get_results('SELECT * FROM ' . $table_name, ARRAY_A);

	    if( $rows ) {
	        fwrite($file, 'INSERT INTO ' . $table_name . ' VALUES ');

	        $total_rows = count($rows);
	        $counter = 1;
	        foreach ($rows as $row => $fields)
	        {
	            $line = '';
	            foreach ($fields as $key => $value)
	            {
	                $value = addslashes($value);
	                $line .= '"' . $value . '",';
	            }

	            $line = '(' . rtrim($line, ',') . ')';

	            if ($counter != $total_rows)
	            {
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
<a href="<?php echo $file_url; ?>" download>Download</a>
<h2>Download is disabled with .htaccess for security - please use FTP / SSH to download</h2>
<h3>Url Path : <?php echo $file_url; ?></h3>
<h3>File Path : <?php echo $file_path; ?></h3>
