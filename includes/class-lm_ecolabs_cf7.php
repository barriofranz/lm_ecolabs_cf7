<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/barriofranz
 * @since      1.0.0
 *
 * @package    Lm_ecolabs_cf7
 * @subpackage Lm_ecolabs_cf7/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Lm_ecolabs_cf7
 * @subpackage Lm_ecolabs_cf7/includes
 * @author     Franz Ian Barrio <barriofranz@gmail.com>
 */
class Lm_ecolabs_cf7 {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Lm_ecolabs_cf7_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'LM_ECOLABS_CF7_VERSION' ) ) {
			$this->version = LM_ECOLABS_CF7_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'lm_ecolabs_cf7';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		if ( class_exists( 'WPCF7_ContactForm' ) || class_exists( 'CFDB7_Wp_Main_Page' ) ) {
			// add_action( 'admin_init', array( $this, 'init' ), 20 );
			//
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_chosen_scripts' ), 20 );
			add_action( 'admin_menu', array( $this, 'add_menu_item' ) );
		}

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Lm_ecolabs_cf7_Loader. Orchestrates the hooks of the plugin.
	 * - Lm_ecolabs_cf7_i18n. Defines internationalization functionality.
	 * - Lm_ecolabs_cf7_Admin. Defines all hooks for the admin area.
	 * - Lm_ecolabs_cf7_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-lm_ecolabs_cf7-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-lm_ecolabs_cf7-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-lm_ecolabs_cf7-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-lm_ecolabs_cf7-public.php';

		$this->loader = new Lm_ecolabs_cf7_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Lm_ecolabs_cf7_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Lm_ecolabs_cf7_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Lm_ecolabs_cf7_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Lm_ecolabs_cf7_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Lm_ecolabs_cf7_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function init() {
		// Make sure this processing runs on the right page.
		if ( isset( $_GET['page'] ) && 'lm_ecolabs_cf7' === $_GET['page'] ) {

		}

	}

	public function enqueue_chosen_scripts() {
		global $current_screen;
		global $woocommerce;


		if ( 'contact_page_lm_ecolabs_cf7' === $current_screen->base ) {

			$css = plugins_url('lm_ecolabs_cf7/public/css/lm_ecolabs_cf7-public.css');
			wp_enqueue_style( 'lm_ecolabs_cf7_css', $css, array(), LM_ECOLABS_CF7_VERSION );

			$js = plugins_url('lm_ecolabs_cf7/public/js/lm_ecolabs_cf7-public.js');
			wp_enqueue_script( 'lm_ecolabs_cf7_js', $js, array(), LM_ECOLABS_CF7_VERSION );
			wp_localize_script( 'lm_ecolabs_cf7_js', 'ajaxArr', array( 'ajaxDatasource' => admin_url( 'admin-ajax.php' )));

			// wp_enqueue_script( 'apexcharts', 'https://www.gstatic.com/charts/loader.js', array(), LM_ECOLABS_CF7_VERSION );


		}
	}

	public function add_menu_item() {
		if ( is_admin() ) {
			add_submenu_page(
				'wpcf7',
				__( 'Leads list', 'lm-ecolabs-cf7' ),
				__( 'Leads list', 'lm-ecolabs-cf7' ),
				'edit_posts',
				'lm_ecolabs_cf7',
				array( $this, 'display_page' ),
			);
		}
		return false;
	}

	public function display_page() {
		$formtypes = $this->getFormTypes();
		$exporturl = admin_url( 'admin-post.php?action=leadlistprintcsv' );
		// $shift_types = $this->getShiftTypes();
		//
		// $fb_sc_emailfrom = get_option( 'fb_sc_emailfrom' );
		// $fb_sc_calendarpw = get_option( 'fb_sc_calendarpw' );

		include_once __DIR__ . '/../public/lead_lists.php';
	}

	public function getFormTypes()
	{
		global $wpdb;
		$sql = "

		SELECT ID, post_title
		FROM ".$wpdb->prefix."posts

		WHERE post_type = 'wpcf7_contact_form'
		and post_status = 'publish'

		";
		// echo "<pre>";print_r($sql);echo "</pre>";die();
		return $wpdb->get_results( $sql );
	}

	public function getLeadMeta($form_meta_form_id)
	{
		global $wpdb;
		$sql = "

		SELECT *
		FROM ".$wpdb->prefix."lm_ecolabs_cf7_form_meta

		WHERE form_meta_form_id = '". $form_meta_form_id ."'

		";
		// echo "<pre>";print_r($sql);echo "</pre>";die();
		return $wpdb->get_results( $sql );
	}

	public function getLeadList($count = false, $where = [], $page = false, $limit = false, $order = false)
	{
		global $wpdb;

		$fields = $count == true ? 'COUNT(DISTINCT(form_id)) AS count' : 't1.*, t3.post_title' ;
		$groupQ = $count == false ? 'GROUP BY form_id' : '' ;

		$limitQ = '';
		if ( $page!==false && $limit!==false ) {

			$offset = $page*$limit;
			$limitQ = "limit ".$offset.",".$limit."";
		}
		$orderQ = '';
		if ( $order!==false ) {
			$orderQ = ' order by ' . $order['col'] . ' ' . $order['dir'];
		}
		$whereQ = '';
		if ( isset($where['form_post_id']) && $where['form_post_id'] != 0 ) {
			$whereQ .= ' AND form_post_id = "'.$where['form_post_id'].'"';
		}
		if ( isset($where['from_date']) && $where['from_date'] !='' ) {
			$whereQ .= ' AND form_date_created >= "'.$where['from_date'].'"';
		}
		if ( isset($where['to_date']) && $where['to_date'] !='' ) {
			$whereQ .= ' AND form_date_created <= "'.$where['to_date'].'"';
		}

		$sql = "

		SELECT " . $fields . "
		FROM ".$wpdb->prefix."lm_ecolabs_cf7_forms t1
		LEFT JOIN ".$wpdb->prefix."lm_ecolabs_cf7_form_meta t2 ON t1.form_id = t2.form_meta_form_id
		LEFT JOIN " . $wpdb->prefix . "posts t3 ON t1.form_post_id = t3.ID

		WHERE t2.form_meta_id IS NOT NULL

		" . $whereQ . "
		" . $groupQ . "
		" . $orderQ . "
		" . $limitQ . "

		";
		// echo "<pre>";print_r($sql);echo "</pre>";die();
		return $wpdb->get_results( $sql );
	}
}
