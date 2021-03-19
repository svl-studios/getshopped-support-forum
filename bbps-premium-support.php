<?php
/*
Plugin Name: GetShopped Support Froum Plugin
Plugin URI: http://getshopped.org
Description: Turn your new bb-Press 2.0 forums into support forums
Author: Mychelle, GetShopped, mufasa
Version: 1.0 Beta1
*/

//////
// Activate and Deactive functions
/////
function bbps_activate() {
	register_uninstall_hook( __FILE__, 'bbps_uninstall' );
	
	//include the options page now so we can save the options on activation.
	include_once( plugin_dir_path(__FILE__).'includes/bbps-core-options.php' );
	do_action( 'bbps-activation' );
}
	register_activation_hook( __FILE__ , 'bbps_activate' );


//want toi remove the options etc TO DO
function bbps_uninstall(){

}


////////
// BBPS Main plugin Setup
///////

/*
function bbps_setup
called by the init hook.
uses:
@function bbps_define_constants
@function bbps_includes
@function bbps_load_css
add any additional setup function calls into here
*/
function bbps_setup(){
	bbps_define_constants(); // defines all constants file paths etc
	bbps_includes(); // includes all plugin files
	bbps_load_css(); //load the css
}
add_action( 'init', 'bbps_setup');


/*
function bbps_define_constants
simply defines the contants
@return: nothing
*/
function bbps_define_constants(){
	define("BBPS_PATH", plugin_dir_path(__FILE__));
	define("BBPS_ADMIN_PATH", BBPS_PATH.'admin/');
	define("BBPS_TEMPLATE_PATH", BBPS_PATH.'templates/');
	define("BBPS_INCLUDES_PATH", BBPS_PATH.'includes/');
	define("BBPS_TEMPLATES_URL", WP_PLUGIN_URL . "/premium-support-forum/templates");
}

/*
function bbps_includes
includes all the files to add more files simply 
add the file name to the correct array
@return: nothing
*/
function bbps_includes(){

	//admin folder
	if ( is_admin() ){
		$admin_files = array(
				'bbps-admin',	//meta box and save functions
				'bbps-settings', //Settings section content prints out under the bb-press forum settings    
				);
				
		foreach ($admin_files as $file){
			include(BBPS_ADMIN_PATH . $file .'.php');
		}
	}
	
	//includes folder
	$include_files = array(
			'bbps-common-functions',		// common functions used through the plugin
			'bbps-support-functions',		//functions relating the the update and edit of the topic status  
			'bbps-core-options',			//sets up the core options
			'bbps-user-ranking-functions', // contains functions relating to the user ranking
			'bbps-premium-forum'			//functions relating to the premium restricted forums
			);
			
	foreach ($include_files as $file){
		include_once(BBPS_INCLUDES_PATH.$file. '.php');
	}
	
}

/*
Load the CSS here
not sure if we need both hooks and functions?
*/
function bbps_load_css(){
	wp_enqueue_style( 'featured.css', BBPS_TEMPLATES_URL.'/css/bbps-style.css');
}
add_action('wp_head','bbps_load_css');


function bbps_stylesheet() {
	// Bail out now if in admin panel or on login page
	if ( is_admin() OR strstr( $_SERVER['REQUEST_URI'], 'wp-login.php' ) )
		return;
	// Load theme stylesheet
	wp_enqueue_style( 'featured', BBPS_TEMPLATES_URL.'/css/bbps-style.css' , false, '', 'screen' );
}
add_action( 'wp_print_styles', 'bbps_stylesheet' );
