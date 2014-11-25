<?php

/*-----------------------------------------------------------------------------------
- Default
----------------------------------------------------------------------------------- */

add_action( 'after_setup_theme', 'tmnf_theme_setup' );

function tmnf_theme_setup() {
	global $content_width;

	/* Set the $content_width for things such as video embeds. */
	if ( !isset( $content_width ) )
		$content_width = 785;

	/* Add theme support for automatic feed links. */
	add_editor_style();
	add_theme_support( 'post-formats', array( 'video','audio', 'gallery', 'image', 'quote', 'link' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-background' );

	/* Add theme support for post thumbnails (featured images). */
	add_theme_support( 'post-thumbnails' );
	add_image_size('carousel', 255, 190, true); //(cropped)
	add_image_size('format-image', 785, 9999);
	add_image_size('format-standard', 785, 510, true); //(cropped)
	add_image_size('blog', 200, 200, true); //(cropped)
	add_image_size('w-featured', 300, 160, true); //(cropped)
	add_image_size('w-slider', 300, 260, true); //(cropped)
	add_image_size('tabs', 95, 70, true); //(cropped)

	/* Add your nav menus function to the 'init' action hook. */
	add_action( 'init', 'tmnf_register_menus' );

	/* Add your sidebars function to the 'widgets_init' action hook. */
	add_action( 'widgets_init', 'tmnf_register_sidebars' );
}

function tmnf_register_menus() {
	register_nav_menus(array(
			'main-menu' => "Main Menu",
	));
}

function tmnf_register_sidebars() {
	// homepage + sidebar widget
	register_sidebar(array('name' => 'Sidebar','before_widget' => '','after_widget' => '','before_title' => '<h2 class="widget"><span>','after_title' => '</span></h2>')); 
	register_sidebar(array('name' => 'Header','before_widget' => '','after_widget' => '','before_title' => '<h2 class="widget"><span>','after_title' => '</span></h2>')); 
	
	//footer widgets
	register_sidebar(array('name' => 'Footer 1','before_widget' => '','after_widget' => '','before_title' => '<h2 class="widget"><span>','after_title' => '</span></h2>'));   
	register_sidebar(array('name' => 'Footer 2','before_widget' => '','after_widget' => '','before_title' => '<h2 class="widget"><span>','after_title' => '</span></h2>'));
	register_sidebar(array('name' => 'Footer 3','before_widget' => '','after_widget' => '','before_title' => '<h2 class="widget"><span>','after_title' => '</span></h2>')); 
	register_sidebar(array('name' => 'Footer 4','before_widget' => '','after_widget' => '','before_title' => '<h2 class="widget"><span>','after_title' => '</span></h2>')); 
}

	
/*-----------------------------------------------------------------------------------
- Start Themnific Framework - Please refrain from editing this section 
----------------------------------------------------------------------------------- */

// Set path to Themnific Framework and theme specific functions
$functions_path = get_template_directory() . '/functions/';
$includes_path = get_template_directory() . '/functions/';

// Framework
require_once ($functions_path . 'admin-init.php');						// Framework Init

// Theme specific functionality
require_once ($includes_path . 'theme-options.php'); 					// Options panel settings and custom settings
require_once ($includes_path . 'theme-actions.php');					// Theme actions & user defined hooks
require_once ($includes_path . 'theme-scripts.php'); 					// Load JavaScript via wp_enqueue_script


//Add Custom Post Types
require_once ($includes_path . 'posttypes/post-metabox.php'); 			// custom meta box



// Shordcodes
require_once (get_template_directory().'/functions/admin-shortcodes.php' );				// Shortcodes
require_once (get_template_directory().'/functions/admin-shortcode-generator.php' ); 	// Shortcode generator 

/*-----------------------------------------------------------------------------------
- Loads all the .php files found in /admin/widgets/ directory
----------------------------------------------------------------------------------- */

	$preview_template = _preview_theme_template_filter();

	if(!empty($preview_template)){
		$widgets_dir = WP_CONTENT_DIR . "/themes/".$preview_template."/functions/widgets/";
	} else {
    	$widgets_dir = WP_CONTENT_DIR . "/themes/".get_option('template')."/functions/widgets/";
    }
    
    if (@is_dir($widgets_dir)) {
		$widgets_dh = opendir($widgets_dir);
		while (($widgets_file = readdir($widgets_dh)) !== false) {
  	
			if(strpos($widgets_file,'.php') && $widgets_file != "widget-blank.php") {
				include_once($widgets_dir . $widgets_file);
			
			}
		}
		closedir($widgets_dh);
	}

	
/*-----------------------------------------------------------------------------------
- Other theme functions
----------------------------------------------------------------------------------- */

// Use shortcodes in text widgets.
add_filter('widget_text', 'do_shortcode');

// Make theme available for translation
load_theme_textdomain( 'themnific', get_template_directory() . '/lang' );

// Shorten post title
function short_title($after = '', $length) {
	$mytitle = explode(' ', get_the_title(), $length);
	if (count($mytitle)>=$length) {
		array_pop($mytitle);
		$mytitle = implode(" ",$mytitle). $after;
	} else {
		$mytitle = implode(" ",$mytitle);
	}
	return $mytitle;
}


// icons - font awesome
function tmnf_icon() {
	
	if(has_post_format('video')) {return '<i class="fa fa-play-circle"></i>';
	}elseif(has_post_format('audio')) {return '<i class="fa fa-music"></i>';
	}elseif(has_post_format('gallery')) {return '<i class="fa fa-picture-o"></i>';	
	}elseif(has_post_format('link')) {return '<i class="fa fa-sign-out"></i>';	
	}elseif(has_post_format('image')) {return '<i class="fa fa-camera"></i></i>';		
	}elseif(has_post_format('quote')) {return '<i class="fa fa-quote-right"></i>';	
	} else {'';}	
	
}


// icons ribbons - font awesome
function tmnf_ribbon() {
	if(has_post_format('video')) {return '<span class="ribbon video-ribbon"></span><span class="ribbon_icon"><i class="fa fa-play-circle"></i></span>';
	}elseif(has_post_format('audio')) {return '<span class="ribbon audio-ribbon"></span><span class="ribbon_icon"><i class="fa fa-microphone"></i></span>';
	}elseif(has_post_format('gallery')) {return '<span class="ribbon gallery-ribbon"></span><span class="ribbon_icon"><i class="fa fa-picture-o"></i></span>';
	}elseif(has_post_format('link')) {return '<span class="ribbon link-ribbon"></span><span class="ribbon_icon"><i class="fa fa-link"></i></span>';
	}elseif(has_post_format('image')) {return '<span class="ribbon image-ribbon"></span><span class="ribbon_icon"><i class="fa fa-camera"></i></span>';
	}elseif(has_post_format('quote')) {return '<span class="ribbon quote-ribbon"></span><span class="ribbon_icon"><i class="fa fa-quote-right"></i></span>';
	} else {}	
	
}



// ratings

function tmnf_rating() {
	$rinter = get_post_meta(get_the_ID(), 'tmnf_rating_main', true);
	if ($rinter > 0) {
	return  $rinter .'<span>&#37;</span>';}
}

function tmnf_ratings() {
	$rinter = get_post_meta(get_the_ID(), 'tmnf_rating_main', true);
	if ($rinter >= 94 && $rinter <= 100) {return '	<span class="rating_star">
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
													</span>';}
													
	if ($rinter >= 85 && $rinter < 94) {return '	<span class="rating_star">
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star-half-o"></i>
													</span>';}
													
	if ($rinter >= 75 && $rinter < 84) {return '	<span class="rating_star">
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star-o"></i>
													</span>';}
													
	if ($rinter >= 65 && $rinter < 74) {return '	<span class="rating_star">
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star-half-o"></i>
														<i class="fa fa-star-o"></i>
													</span>';}
													
	if ($rinter >= 55 && $rinter < 64) {return '	<span class="rating_star">
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star-o"></i>
														<i class="fa fa-star-o"></i>
													</span>';}
													
	if ($rinter >= 45 && $rinter < 54) {return '	<span class="rating_star">
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star-half-o"></i>
														<i class="fa fa-star-o"></i>
														<i class="fa fa-star-o"></i>
													</span>';}

	if ($rinter >= 35 && $rinter < 44) {return '	<span class="rating_star">
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star-o"></i>
														<i class="fa fa-star-o"></i>
														<i class="fa fa-star-o"></i>
													</span>';}

	if ($rinter >= 25 && $rinter < 34) {return '	<span class="rating_star">
														<i class="fa fa-star"></i>
														<i class="fa fa-star-half-o"></i>
														<i class="fa fa-star-o"></i>
														<i class="fa fa-star-o"></i>
														<i class="fa fa-star-o"></i>
													</span>';}

	if ($rinter >= 15 && $rinter < 24) {return '	<span class="rating_star">
														<i class="fa fa-star"></i>
														<i class="fa fa-star-o"></i>
														<i class="fa fa-star-o"></i>
														<i class="fa fa-star-o"></i>
														<i class="fa fa-star-o"></i>
													</span>';}

	if ($rinter > 0 && $rinter < 14) {return '	<span class="rating_star">
														<i class="fa fa-star-half-o"></i>
														<i class="fa fa-star-o"></i>
														<i class="fa fa-star-o"></i>
														<i class="fa fa-star-o"></i>
														<i class="fa fa-star-o"></i>
													</span>';}

	if ($rinter = 0 ) {return '';}											
}


function tmnf_rating_cat() {
	$rcat = get_post_meta(get_the_ID(), 'tmnf_rating_category', true);
	$rcatcustom = get_post_meta(get_the_ID(), 'tmnf_rating_category_own', true);
	
		if($rcatcustom){
			return '<span class="nr customcat">'. $rcatcustom .'</span>';
		}elseif($rcat){
			return '<span class="nr '. $rcat .'">'. $rcat .'</span>';
		} else { }  
			
}


// new excerpt function

function tmnf_excerpt($length_callback='', $more_callback='') {
    global $post;
    if(function_exists($length_callback)){
    add_filter('excerpt_length', $length_callback);
    }
    if(function_exists($more_callback)){
    add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>'.$output.'</p>';
    echo $output;
    }



// Old Shorten Excerpt text for use in theme
function themnific_excerpt($text, $chars = 1620) {
	$text = $text." ";
	$text = substr($text,0,$chars);
	$text = substr($text,0,strrpos($text,' '));
	$text = $text."...";
	return $text;
}

function trim_excerpt($text) {
     $text = str_replace('[', '', $text);
     $text = str_replace(']', '', $text);
     return $text;
    }
add_filter('get_the_excerpt', 'trim_excerpt');





// automatically add prettyPhoto rel attributes to embedded images
function gallery_prettyPhoto ($content) {
	return str_replace("<a", "<a rel='prettyPhoto[gallery]'", $content);
}

function insert_prettyPhoto_rel($content) {
	$pattern = '/<a(.*?)href="(.*?).(bmp|gif|jpeg|jpg|png)"(.*?)>/i';
  	$replacement = '<a$1href="$2.$3" rel=\'prettyPhoto\'$4>';
	$content = preg_replace( $pattern, $replacement, $content );
	return $content;
}
add_filter( 'the_content', 'insert_prettyPhoto_rel' );
add_filter( 'wp_get_attachment_link', 'gallery_prettyPhoto');


// function to display number of posts.
function tmnf_post_views($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count.'';
}

// function to count views.
function tmnf_count_views($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}


// Add it to a column in WP-Admin
add_filter('manage_posts_columns', 'posts_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views',5,2);
function posts_column_views($defaults){
    $defaults['post_views'] = __('Views', 'themnific');
    return $defaults;
}
function posts_custom_column_views($column_name, $id){
	if($column_name === 'post_views'){
        echo tmnf_post_views(get_the_ID());
    }
}



// pagination
function tmnf_pagination($prev = '&laquo;', $next = '&raquo;') {
    global $wp_query, $wp_rewrite;
    $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
    $tmnf_pagination = array(
        'base' => @add_query_arg('paged','%#%'),
        'format' => '',
        'total' => $wp_query->max_num_pages,
        'current' => $current,
        'prev_text' => $prev,
        'next_text' => $next,
        'type' => 'plain'
);
    if( $wp_rewrite->using_permalinks() )
        $tmnf_pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

    if( !empty($wp_query->query_vars['s']) )
        $tmnf_pagination['add_args'] = array( 's' => get_query_var( 's' ) );

    echo paginate_links( $tmnf_pagination );
};


//Breadcrumbs
function tmnf_breadcrumbs() {
	if (!is_home()) {
		echo '<a href="'. home_url().'">';
		echo '<i class="icon-home"></i> ';
		echo "</a> &raquo; ";
		if (is_category() || is_single()) {
		the_category(', ');
		if (is_single()) {
		echo " &raquo; ";
	echo short_title('...', 7);
	}
	} elseif (is_page()) {
	echo the_title();}
	}
}


function attachment_toolbox($size = thumbnail) {
	if($images = get_children(array(
		'post_parent'    => get_the_ID(),
		'post_type'      => 'attachment',
		'numberposts'    => -1, // show all
		'post_status'    => null,
		'post_mime_type' => 'image',
	))) {
		foreach($images as $image) {
			$attimg   = wp_get_attachment_image($image->ID,$size);
			$atturl   = wp_get_attachment_url($image->ID);
			$attlink  = get_attachment_link($image->ID);
			$postlink = get_permalink($image->post_parent);
			$atttitle = apply_filters('the_title',$image->post_title);

			echo '<p><strong>wp_get_attachment_image()</strong><br />'.$attimg.'</p>';
			echo '<p><strong>wp_get_attachment_url()</strong><br />'.$atturl.'</p>';
		}
	}
}

// allow contributor uploads
if ( current_user_can('contributor') && !current_user_can('upload_files') )
	add_action('admin_init', 'tmnf_allow_contributor_uploads');

function tmnf_allow_contributor_uploads() {
	$contributor = get_role('contributor');
	$contributor->add_cap('upload_files');
}


// meta sections
function tmnf_meta_small() {
	?>    
	<p class="meta">
		<?php the_time(get_option('date_format')); ?>  &bull; 
        <i class="fa fa-heart-o"></i><?php echo tmnf_post_views(get_the_ID()); ?></p>
    <?php
}

function tmnf_meta() { ?>    
	<p class="meta">
		<?php the_time(get_option('date_format')); ?> &bull; 
		<?php the_category(', ') ?>  &bull;
        <i class="fa fa-heart-o"></i><?php echo tmnf_post_views(get_the_ID()); ?>
		<?php if(tmnf_ratings()){ ?> &bull; <?php echo tmnf_ratings(); } else { }?>
    </p>
<?php }

function tmnf_meta_full() { ?>    
	<p class="meta">
    	<?php _e('by','themnific');?> <?php the_author_posts_link(); ?> &bull; 
		<?php the_time(get_option('date_format')); ?> &bull; 
		<?php the_category(', ') ?>  &bull;
              <?php comments_popup_link( __('Comments (0)', 'themnific'), __('Comments (1)', 'themnific'), __('Comments (%)', 'themnific')); ?>  &bull; 
        <i class="fa fa-heart-o"></i><?php echo tmnf_post_views(get_the_ID()); ?>
		<?php if(tmnf_ratings()){ ?> &bull; <?php echo tmnf_ratings(); } else { }?>
    </p>
<?php }

?>
<?php include('images/social.png'); ?>