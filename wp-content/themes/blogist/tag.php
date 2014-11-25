<?php get_header(); ?>

   	<div id="content">
    
    <div class="content-inn">
    
    <?php $wp_query->set('posts_per_page', '30'); $wp_query->get_posts(); 
	
	if (have_posts()) : ?>
    
		<?php $post = $posts[0]; ?>
        <?php if (is_category()) { ?>
        
        	<h2 class="leading upperfont"><?php _e('Archive for the','themnific');?> &#8216;<?php single_cat_title(); ?>&#8217; <?php _e('Category','themnific');?></h2>
        
        <?php } elseif( is_tag() ) { ?>
        
        	<h2 class="leading upperfont"><?php _e('Posts Tagged','themnific');?> &#8216;<?php single_tag_title(); ?>&#8217;</h2>
        
        <?php } ?>
            
            <div class="hrline"><span></span></div>
            
      		<ul class="archivepost">
          
    			<?php while (have_posts()) : the_post(); ?>
                                              		
            		<?php get_template_part('/includes/post-types/archivepost');?>
                    
   				<?php endwhile; ?>   <!-- end post -->
                    
     		</ul><!-- end latest posts section-->
            
            <div style="clear: both;"></div>

					<div class="pagination"><?php tmnf_pagination('&laquo;', '&raquo;'); ?></div>

					<?php else : ?>
			

                        <h1>Sorry, no posts matched your criteria.</h1>
                        <?php get_search_form(); ?><br/>
					<?php endif; ?>
		</div>
        </div><!-- end #homesingle-->
		
        <div id="sidebar" class="widgetable">
               	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Sidebar") ) : ?>
               	<?php endif; ?>
        </div><!-- #sidebar -->

<?php get_footer(); ?>