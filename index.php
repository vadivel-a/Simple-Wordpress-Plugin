<?php
/**
 * @package Custom Form_Plugin
 * @version 1.0.0
 */
/*
Plugin Name: CustomForm Slider
Plugin URI: http://domain.in
Description: Custom Form
Author: Vadivel
Version: 1.0.0
Author URI: http://domain.in
License: GPL V2 or Later
*/

defined( 'ABSPATH' ) or die( 'Hey, you can\t access this filr, you silly human!' );

if( !class_exists( 'mwCustomForm' ) ){
class mwCustomForm
{

	public $plugin;
	protected $PLUGIN_VERSION;
	function __construct(){
		$this->plugin = plugin_basename( __FILE__ );
		$this->$PLUGIN_VERSION = '1.0';
	}

	function register(){
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
		add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link') );
	}

	public function settings_link( $links ){
		$settings_link = '<a href="admin.php?page=mw_custom_form">Settings</a>';
		array_push($links, $settings_link);
		return $links;
	}

  function add_admin_pages(){
		add_menu_page( 'Custom Form Plugin',
										'Custom Form',
										'manage_options', 'mw_custom_form', array( $this, 'admin_template_page' ),
										'dashicons-store', null );
	}

	function admin_template_page(){
		require_once plugin_dir_path( __FILE__ ). 'inc/templates/admin.php';
		//mwCustomFormPluginShortcodeGenerate::genShortcode();
	}

	function front_enqueue(){
		wp_enqueue_script( 'mwcustomform-pluignscript', plugins_url( '/assets/js/script.js', __FILE__ ), array('jquery'), $this->$PLUGIN_VERSION, 'footer');
		wp_enqueue_style( 'mwcustomform-pluignstyle', plugins_url( '/assets/css/front-style.css', __FILE__ ),array(), $this->$PLUGIN_VERSION, 'all');
	}

	function admin_enqueue(){
		wp_enqueue_style( 'admin-style', plugins_url( '/assets/css/admin-style.css', __FILE__ ),array(), $this->$PLUGIN_VERSION, 'all');
	}

	function activate(){
		require_once plugin_dir_path( __FILE__ ). 'inc/plugin-activate.php';
		mwCustomFormPluginActivate::activate();
		mw_customform_plugin_create_db();
	}

}

	$mwCustomForm = new mwCustomForm();
	$mwCustomForm->register();


	//activate
	register_activation_hook( __FILE__, array($mwCustomForm, 'activate') );

	//deactivate
	require_once plugin_dir_path( __FILE__ ). 'inc/plugin-deactivate.php';
	register_deactivation_hook( __FILE__, array('mwCustomFormPluginDeactivate', 'deactivate') );

	function ajax_mwcustom_form_init(){
		wp_register_script('ajax-customform-script', plugins_url( '/assets/js/ajax-customform-script.js', __FILE__ ),array('jquery'), '1.0', 'footer');
		wp_enqueue_script('ajax-customform-script');
		
		wp_localize_script( 'ajax-customform-script',
							'ajax_customform_object',
							array(
							'ajaxurl' => admin_url( 'admin-ajax.php' ),
							'redirecturl' => site_url('/'),
							'loadingmessage' => __('Sending user info, please wait...')
							));
		add_action( 'wp_ajax_nopriv_ajaxmwcustom_form', 'ajaxmwcustom_form' );
		add_action( 'wp_ajax_ajaxmwcustom_form', 'ajaxmwcustom_form' );
	}
	add_action('init', 'ajax_mwcustom_form_init');	
	
	function ajaxmwcustom_form(){
		global $wpdb;
		check_ajax_referer( 'ajax-customform-nonce', 'security' );
		$table = $wpdb->prefix.'mwcustomform';
		$data = array(
			"name" => $_POST['name'], 
			"email" => $_POST['email'], 
			"phone" => $_POST['phone'],
			"time" => date( 'Y-m-d H:i:s', current_time( 'timestamp', 1 )));
		$format = array('%s','%s','%s','%s');
		$ret = $wpdb->insert($table,$data,$format);
		if ( !$ret){
			echo json_encode(array('status'=>false, 'message'=>__('Missing some field')));
		} else {
			echo json_encode(array('staatus'=>true, 'message'=>__('Successfully data submittedd')));
		}
		die();
	}	

	add_shortcode( 'mwcustomform', 'mw_custom_form' );
	function mw_custom_form( $atts ) {
	  ob_start();
		$html = '';
		$html .= '<form id="mw_customform" class="mw_customform" method="post">
			 <p class="status"></p>
			 <p><input id="name" type="text" placeholder="Email Address*" name="name" required></p>
			 <p><input id="email" type="email" placeholder="Email*" name="email" required></p>
			 <p><input id="phone" type="tel" placeholder="Phone*" name="phone" required></p>
			 <p><input class="submit_button general_button" type="submit" value="submit" name="submit"></p>
			 '.wp_nonce_field( 'ajax-customform-nonce', 'security' ).'
		</form>';
	  ob_end_clean();
	  return $html;
	}	

	function mw_customform_plugin_create_db() {

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'mwcustomform';

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			name varchar(20) NOT NULL,
			email varchar(20) NOT NULL,
			phone varchar(20) NOT NULL,
			PRIMARY KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}	

}
