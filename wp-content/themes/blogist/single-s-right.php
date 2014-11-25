<div id="content">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        	<div <?php post_class('twinsbox'); ?>> 
 
			<?php 
			$video_input = get_post_meta($post->ID, 'tmnf_video', true);
			$audio_input = get_post_meta($post->ID, 'tmnf_audio', true);
			$rating = get_post_meta($post->ID, 'tmnf_rating_main', true);
			?>

			<?php 	if(has_post_format('video')){
                            echo ($video_input);
                    }elseif(has_post_format('audio')){
                            echo ($audio_input);
                    }elseif(has_post_format('gallery')){
						if (get_option('themnific_post_gallery_dis') == 'true' );
						else
                            echo get_template_part( '/includes/post-types/gallery-slider' );
                    } else {
						   
							if ( has_post_thumbnail()){ ?>
      
           						<a href="<?php the_permalink(); ?>" title="<?php the_title();?>" >
           
           							<?php the_post_thumbnail('format-standard', array('class' => 'main-single'));   ?>
                
           					</a>
           
      						<?php } else {?> 
                            
                            	<img class="replacement" src="<?php echo esc_url(get_option('themnific_blog_image'));?>"> 
                                
							<?php }; 
                                
            }?>
			
            <div class="clearfix"></div>

            <?php tmnf_meta_full(); ?>
            
            <h1 class="heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            
            <div class="entry">
            	
                <?php if($rating) {?>

                    <div class="ratingblock">
                    
                        <?php get_template_part( '/includes/mag-rating' ); ?>
                        
                    </div>
				
                <?php } else { }?>
            
            	<?php the_content(); ?>
            
            	<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:','themnific') . '</span>', 'after' => '</div>' ) ); ?>
                
                <?php the_tags( '<div class="taggs">  ',' ',  '</div>'); ?>
                    
                <?php 
                    if (get_option('themnific_post_related_dis') == 'true' );
                    else 
                    get_template_part('/includes/mag-relatedposts');
                ?>
    
                <?php if (get_option('themnific_post_bread_dis') == 'true' );
                else { ?> 
                           	
                    <div class="clearfix"></div>
        
                        <div class="postinfo">
    
                        <?php get_template_part('/includes/mag-nextprev'); ?>
                    
                        <div class="clearfix"></div> 
                        
                    </div>
                    
                <?php }?>
                                
                <?php 
                    if (get_option('themnific_post_author_dis') == 'true' );
                    else
                    get_template_part('/includes/mag-authorinfo');
                ?>
            
                <div class="clearfix"></div>
            
                <?php comments_template(); ?>

			</div><!-- end .entry -->
        
        </div><!-- end .post -->

	<?php endwhile; else: ?>

		<p><?php _e('Sorry, no posts matched your criteria','themnific');?>.</p>

	<?php endif; ?>

    <div class="clearfix"></div>

</div><!-- #homecontent -->

<div id="sidebar" class="widgetable">
        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Sidebar") ) : ?>
        <?php endif; ?>
</div><!-- #sidebar -->