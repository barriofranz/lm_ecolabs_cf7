<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/barriofranz
 * @since      1.0.0
 *
 * @package    Lm_ecolabs_cf7
 * @subpackage Lm_ecolabs_cf7/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Lm_ecolabs_cf7
 * @subpackage Lm_ecolabs_cf7/includes
 * @author     Franz Ian Barrio <barriofranz@gmail.com>
 */
class Lm_ecolabs_cf7_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		self::fb_sc_install();
		self::cfdb7_upgrade_function();
	}

	public static function fb_sc_install() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		global $wpdb;
		global $jal_db_version;

		// INSERT INTO " . $table_name . " values (null,'RN', '0275d8');
		// INSERT INTO " . $table_name . " values (null,'CNA', '5cb85c');
		// INSERT INTO " . $table_name . " values (null,'LPN', '5bc0de');
		// INSERT INTO " . $table_name . " values (null,'PSA', 'f0ad4e');

		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . 'lm_ecolabs_cf7_forms';
		$sql = "CREATE TABLE " . $table_name . " (
			form_id INT(11) NOT NULL AUTO_INCREMENT  PRIMARY KEY,
			form_post_id int(11),
			form_date_created datetime
		) $charset_collate;
		";
		dbDelta( $sql );


		$table_name = $wpdb->prefix . 'lm_ecolabs_cf7_form_meta';
		$sql = "CREATE TABLE " . $table_name . " (
			form_meta_id INT(11) NOT NULL AUTO_INCREMENT  PRIMARY KEY,
			form_meta_form_id int(11),
			form_meta_key varchar(255),
			form_meta_value varchar(255)
		) $charset_collate;
		";
		dbDelta( $sql );

	}

	public static function cfdb7_upgrade_function() {

		$upload_dir    = wp_upload_dir();
	    $cfdb7_dirname = $upload_dir['basedir'].'/lme_cf7_uploads';
	    if ( ! file_exists( $cfdb7_dirname ) ) {
	        wp_mkdir_p( $cfdb7_dirname );
	        $fp = fopen( $cfdb7_dirname.'/index.php', 'w');
	        fwrite($fp, "<?php \n\t // Silence is golden.");
	        fclose( $fp );
	    }

	}
}
