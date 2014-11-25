<li <?php post_class(); ?>>

		<?php echo tmnf_ribbon() ?>

			<?php 
			$video_input = get_post_meta($post->ID, 'tmnf_video', true);
			$audio_input = get_post_meta($post->ID, 'tmnf_audio', true);
			?>
            
            <div class="clearfix"></div>

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

            <?php tmnf_meta(); ?>

            <h2 class="upperfont"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

    		<div class="entry">   

				<?php global $more; $more = 0; ?>
                
                <?php the_content('Continue Reading'); ?> 
                
                <a class="mainbutton" href="<?php the_permalink(); ?>"><?php _e('Read More','themnific');?> &#187;</a>
                  
           	</div>
                  
</li>