<?php
/*
	Plugin Name: WP-Forum 3
	Plugin Author: Fredrik Fahlstad
	Plugin URI: http://www.fahlstad.se
	Author URI: http://www.fahlstad.se
	Version: 3.0
*/

//$plugin_dir = basename(dirname(__FILE__));
//load_plugin_textdomain( 'wpforum', ABSPATH.'wp-content/plugins/'. $plugin_dir.'/', $plugin_dir.'/' );
include_once("AppBase.php");

// Short and sweet :)
$appBase = new AppBase();

// Activating?
register_activation_hook(__FILE__ ,array(&$appBase,'install'));
add_action("the_content", array(&$appBase, "main"));
add_action("wp_head", array(&$appBase, "head"));
add_action("wp_enqueue_scripts", array(&$appBase, "enqueue_scripts"));
/*
add_action('init', array(&$wpforum,'set_cookie'));
add_filter("wp_title", array(&$wpforum, "set_pagetitle"));
function latest_activity($num = 5){
	global $wpforum;
	return $wpforum->latest_activity($num);
}
*/
?>