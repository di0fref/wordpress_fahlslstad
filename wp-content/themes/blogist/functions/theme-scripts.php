<?php
if ( ! is_admin() ) { add_action( 'wp_print_scripts', 'themnific_add_javascript' ); }

if ( ! function_exists( 'themnific_add_javascript' ) ) {
	function themnific_add_javascript() {

		// Load Common scripts	
		wp_enqueue_script('jquery');
		wp_enqueue_script('css3-mediaqueries', get_template_directory_uri().'/js/css3-mediaqueries.js');
		wp_enqueue_script('superfish', get_template_directory_uri().'/js/superfish.js','','', true);
		wp_enqueue_script('jquery.hoverIntent.minified', get_template_directory_uri().'/js/jquery.hoverIntent.minified.js','','', true);
		wp_enqueue_script('prettyPhoto', get_template_directory_uri() . '/js/jquery.prettyPhoto.js','','', true);
		wp_enqueue_script('jquery.flexslider-min', get_template_directory_uri() .'/js/jquery.flexslider-min.js','','', true);
		wp_enqueue_script('jquery.flexslider.start.main', get_template_directory_uri() .'/js/jquery.flexslider.start.main.js','','', true);
		wp_enqueue_script('ownScript', get_template_directory_uri() .'/js/ownScript.js','','', true);
		
		if ( is_singular()){
		wp_enqueue_script( 'comment-reply' );
		}
		
	}
}
?>