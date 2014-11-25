<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div id="content">
        
        	<div <?php post_class(); ?>>
            
                <h1 class="heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    
                <div class="entry">
                
                        <?php the_content(); ?>
                        
                        <?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:','themnific') . '</span>', 'after' => '</div>' ) ); ?>
                        
                        <?php the_tags( '<p class="tagssingle">','',  '</p>'); ?>
                                
                		<div style="clear: both;"></div>
                          
                		<?php comments_template(); ?>
                        
                </div>       

            </div>



	<?php endwhile; else: ?>

		<p><?php _e('Sorry, no posts matched your criteria','themnific');?>.</p>

	<?php endif; ?>

                <div style="clear: both;"></div>

        </div><!-- #homecontent -->
		
        <div id="sidebar" class="widgetable">
               	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Sidebar") ) : ?>
               	<?php endif; ?>
        </div><!-- #sidebar -->

<?php get_footer(); ?>