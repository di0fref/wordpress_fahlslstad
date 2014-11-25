<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="utf-8" />
<title><?php global $page, $paged; wp_title( '|', true, 'right' ); bloginfo( 'name' ); $site_description = get_bloginfo( 'description', 'display' ); echo " | $site_description"; if ( $paged >= 2 || $page >= 2 ) echo ' | ' . sprintf( __( 'Page %s','themnific'), max( $paged, $page ) ); ?></title>

<!-- Set the viewport width to device width for mobile -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php themnific_head(); ?>

<?php wp_head(); ?>

</head>

     
<body <?php body_class(); ?>>
    
<div class="container <?php if (get_option('themnific_upper') == 'false' ); else echo 'upper'; ?> <?php if (get_option('themnific_logoposition') == 'false' ); else echo 'absol'; ?>"> 

    
    <a id="navtrigger" href="#"><?php _e('MENU','themnific');?></a>
        
    <nav id="navigation"> 
    
        <?php get_template_part('/includes/uni-navigation');?>
                        
    </nav>
    
	<div id="content_bg" class="<?php if (get_option('themnific_cont_shadow') == 'false' ); else echo 'container_shadow'; ?>"></div>

    <div id="header">
    
        <h1 class="<?php if (get_option('themnific_logoposition') == 'false' ); else echo 'absol'; ?>">
        
            <?php if(get_option('themnific_logo')) { ?>
                            
                <a href="<?php echo home_url(); ?>/">
                
                    <img class="logo" src="<?php echo esc_url(get_option('themnific_logo'));?>" alt="<?php bloginfo('name'); ?>"/>
                        
                </a>
                    
            <?php } 
                    
                else { ?> <a href="<?php echo home_url(); ?>/"><?php bloginfo('name');?></a>
                    
            <?php } ?>	
        
        </h1>                
        
		<div class="widgetable">
               	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Header") ) : ?>
               	<?php endif; ?>
        </div>

    </div> 

 <div class="clearfix"></div>