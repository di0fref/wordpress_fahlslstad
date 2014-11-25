<li <?php post_class(); ?>>
    
    <h2 class="upperfont"><a href="<?php echo get_post_meta($post->ID, 'tmnf_linkss', true); ?>"><?php the_title(); ?></a></h2>
    
	<?php tmnf_meta(); ?>

	<?php if ( has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title();?>" >
        <?php the_post_thumbnail( 'blog',array('title' => "")); ?>
        </a>
    <?php endif; ?>
    
    <div class="entry"> 
        
   		<p><?php echo themnific_excerpt( get_the_excerpt(), '400'); ?></p>
    
    	<p><a href="<?php echo get_post_meta($post->ID, 'tmnf_linkss', true); ?>"><?php _e('[Link Post]','themnific');?> <?php echo tmnf_icon() ?></a></p>
    
    </div>
            
</li>