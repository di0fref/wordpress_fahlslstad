<li <?php post_class(); ?>>
   
		<?php
        $large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' ); 
        $large_image = $large_image[0]; 
        $another_image_1 = get_post_meta($post->ID, 'themnific_image_1_url', true);
        ?>

		<?php echo tmnf_ribbon() ?>

		<div class="imageformat">
        
            <a rel="prettyPhoto[gallery]"  href="<?php echo $large_image; ?>">  
                     <?php the_post_thumbnail('format-image'); ?>
            </a>
           
        </div>
    
   		<div style="clear: both;"></div> 

            <p class="meta"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
            
</li>