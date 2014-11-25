<?php get_header(); ?>

   	<div id="content">
    
    <div class="content-inn">
    
    <?php $wp_query->set('posts_per_page', '30'); $wp_query->get_posts(); 
	
	if (have_posts()) : ?>
    
    <h2 class="leading upperfont"><?php _e('Search Results for','themnific');?> "<?php echo $s; ?>"</h2>
            
            <div class="hrline"><span></span></div>

      		<ul class="archivepost">
          
    			<?php while (have_posts()) : the_post(); ?>
                                              		
            		<?php get_template_part('/includes/post-types/archivepost');?>
                    
   				<?php endwhile; ?>   <!-- end post -->
                    
     		</ul><!-- end latest posts section-->
            
            <div style="clear: both;"></div>
            
					<div class="pagination"><?php tmnf_pagination('&laquo;', '&raquo;'); ?></div>

					<?php else : ?>
                    
						<!-- Not Found Handling -->
                        
                        <div class="hrlineB"><span></span></div>
                        
                        <h3 class="upperfont"><?php _e('Sorry, no posts matched your criteria.','themnific');?></h3>
                        
           				<h4><?php _e('Perhaps You will find something interesting form these lists...','themnific');?></h4>
                        
            			<div class="hrlineB"></div>
                        
						<?php get_template_part('/includes/uni-404-content');?>
                        
                        
					<?php endif; ?>

		</div>
        </div><!-- end #homesingle-->
		
        <div id="sidebar" class="widgetable">
               	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Sidebar") ) : ?>
               	<?php endif; ?>
        </div><!-- #sidebar -->

<?php get_footer(); ?>