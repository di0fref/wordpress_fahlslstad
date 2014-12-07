<?php

if ( !function_exists( 'response_setup' ) ) {
	function response_setup() {
		register_nav_menus( array(
			'main' => 'Main Navigation Menu',
			'footer_menu' => 'Footer Menu',
		) );
	}
}
add_action( 'after_setup_theme', 'response_setup' );

function response_scripts() {
	wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'response_scripts' );
