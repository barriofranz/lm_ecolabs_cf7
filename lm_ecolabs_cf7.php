<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/barriofranz
 * @since             1.0.0
 * @package           Lm_ecolabs_cf7
 *
 * @wordpress-plugin
 * Plugin Name:       Lm ecolabs CF7
 * Plugin URI:        https://github.com/barriofranz/lm_ecolabs_cf7
 * Description:       Custom plugin for contact form 7 table. Requires "Contact Form 7" and "Contact Form lme_cf7"
 * Version:           1.0.0
 * Author:            Franz Ian Barrio
 * Author URI:        https://github.com/barriofranz
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lm_ecolabs_cf7
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LM_ECOLABS_CF7_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-lm_ecolabs_cf7-activator.php
 */
function activate_lm_ecolabs_cf7() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lm_ecolabs_cf7-activator.php';
	Lm_ecolabs_cf7_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-lm_ecolabs_cf7-deactivator.php
 */
function deactivate_lm_ecolabs_cf7() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lm_ecolabs_cf7-deactivator.php';
	Lm_ecolabs_cf7_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_lm_ecolabs_cf7' );
register_deactivation_hook( __FILE__, 'deactivate_lm_ecolabs_cf7' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-lm_ecolabs_cf7.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_lm_ecolabs_cf7() {

	$plugin = new Lm_ecolabs_cf7();
	$plugin->run();

}
run_lm_ecolabs_cf7();

add_action( 'wpcf7_before_send_mail', 'lm_ecolab_cf7_before_send_mail' );
function lm_ecolab_cf7_before_send_mail( $form_tag ) {

    global $wpdb;
    $cfdb          = apply_filters( 'lme_cf7_database', $wpdb );
    $table_name    = $cfdb->prefix.'lm_ecolabs_cf7_forms';
    $table_name_meta = $cfdb->prefix.'lm_ecolabs_cf7_form_meta';

	$upload_dir    = wp_upload_dir();
    $lme_cf7_dirname = $upload_dir['basedir'].'/lme_cf7_uploads';
    $time_now = time();

    $submission   = WPCF7_Submission::get_instance();
    $contact_form = $submission->get_contact_form();
    $tags_names   = array();
    $strict_keys  = apply_filters('lme_cf7_strict_keys', false);

    if ( $submission ) {

        $allowed_tags = array();

        if( $strict_keys ){
            $tags  = $contact_form->scan_form_tags();
            foreach( $tags as $tag ){
                if( ! empty($tag->name) ) $tags_names[] = $tag->name;
            }
            $allowed_tags = $tags_names;
        }

        $not_allowed_tags = apply_filters( 'lme_cf7_not_allowed_tags', array( 'g-recaptcha-response' ) );
        $allowed_tags     = apply_filters( 'lme_cf7_allowed_tags', $allowed_tags );
        $data             = $submission->get_posted_data();
        $files            = $submission->uploaded_files();
        $uploaded_files   = array();


        foreach ($_FILES as $file_key => $file) {
            array_push($uploaded_files, $file_key);
        }
        foreach ($files as $file_key => $file) {
            $file = is_array( $file ) ? reset( $file ) : $file;
            if( empty($file) ) continue;
            copy($file, $lme_cf7_dirname.'/'.$time_now.'-'.$file_key.'-'.basename($file));
        }

        $form_data   = array();

        // $form_data['lme_cf7_status'] = 'unread';
        foreach ($data as $key => $d) {

            if( $strict_keys && !in_array($key, $allowed_tags) ) continue;

            if ( !in_array($key, $not_allowed_tags ) && !in_array($key, $uploaded_files )  ) {

                $tmpD = $d;

                if ( ! is_array($d) ){
                    $bl   = array('\"',"\'",'/','\\','"',"'");
                    $wl   = array('&quot;','&#039;','&#047;', '&#092;','&quot;','&#039;');
                    $tmpD = str_replace($bl, $wl, $tmpD );
                }

                $form_data[$key] = $tmpD;
            }
            if ( in_array($key, $uploaded_files ) ) {
                $file = is_array( $files[ $key ] ) ? reset( $files[ $key ] ) : $files[ $key ];
                $file_name = empty( $file ) ? '' : $time_now.'-'.$key.'-'.basename( $file );
                $form_data[$key.'lme_cf7_file'] = $file_name;
            }
        }

        /* lme_cf7 before save data. */
        $form_data = apply_filters('lme_cf7_before_save_data', $form_data);

        do_action( 'lme_cf7_before_save', $form_data );

        $form_post_id = $form_tag->id();
        $form_value   = serialize( $form_data );
        $form_date    = current_time('Y-m-d H:i:s');

        $cfdb->insert( $table_name, array(
            'form_post_id' => $form_post_id,
            'form_date_created' => $form_date,
        ) );

        /* lme_cf7 after save data */
        $insert_id = $cfdb->insert_id;
        do_action( 'lme_cf7_after_save_data', $insert_id );

		foreach ( $form_data as $formK => $formD ){
			$cfdb->insert( $table_name_meta, array(
	            'form_meta_form_id' => $insert_id,
	            'form_meta_key' => $formK,
	            'form_meta_value' => $formD,
	        ) );
		}

    }

}

add_action("wp_ajax_getLeadList", "getLeadList");
function getLeadList()
{
	$limit = 50;
	$page = isset($_POST['page']) ? (int)$_POST['page']-1 : 0;

	$order  = [
		'col'=>'form_id',
		'dir'=>'desc',
	];

	$where = [
		'form_post_id' => $_POST['lead_type'],
		'from_date' => $_POST['from_date'],
		'to_date' => $_POST['to_date'],
		'region' => $_POST['region'],
	];
	$fbModel = new Lm_ecolabs_cf7;
	$count = $fbModel->getLeadList(true, $where);

	$count = $count[0]->count;
	$rows = $fbModel->getLeadList(false, $where, $page, $limit, $order);

	foreach ( $rows as &$row ) {
		$rowsMeta = $fbModel->getLeadMeta($row->form_id);
		$metaData = [];

		foreach ( $rowsMeta as $metaD ) {
			$metaData[$metaD->form_meta_key] = $metaD->form_meta_value;
		}

		$row->details = json_encode($metaData);

	}
	include_once __DIR__ . '/public/partials/lead_list_table.php';

	die();

}

add_action( 'admin_post_leadlistprintcsv', 'print_csv' );
function print_csv()
{
	$where = [
		'form_post_id' => $_GET['lead_type'],
		'from_date' => $_GET['from_date'],
		'to_date' => $_GET['to_date'],
		'region' => $_GET['region'],
	];
	$fbModel = new Lm_ecolabs_cf7;
	$rows = $fbModel->getLeadList(false, $where, false, false, false);


	$output = fopen("php://output",'w') or die("Can't open php://output");
	header("Content-Type:application/csv");
	header("Content-Disposition:attachment;filename=".strtotime(date('Y-m-d H:i:s')).".csv");
	fputcsv($output, array('Lead Type','Lead Details','Date','Region'));
    foreach ($rows as $row) {

		$rowsMeta = $fbModel->getLeadMeta($row->form_id);
		$metaData = "";

		foreach ( $rowsMeta as $metaD ) {
			$metaData .= $metaD->form_meta_key .": ".$metaD->form_meta_value."\r\n";
		}

		$row->details = $metaData;

		$data = [];
		$data[] = $row->post_title;
		$data[] = $row->details;
		$data[] = $row->form_date_created;


        fputcsv($output, $data);
    }
	fclose($output);


	die();
}
