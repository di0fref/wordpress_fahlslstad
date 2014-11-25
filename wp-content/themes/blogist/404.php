<?php get_header(); ?>

    <div id="content">
    
        <div class="content-inn">
    
            <h2 class="upperfont"><?php _e('Nothing found here','themnific');?></h2>
            
            <h4><?php _e('Perhaps You will find something interesting form these lists...','themnific');?></h4>
            
            <div class="entry">
            
                <?php get_template_part('/includes/uni-404-content');?>
            
            </div>
        
        </div>
        
    </div><!-- #homecontent -->
    
    <div id="sidebar" class="widgetable">
    
            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Sidebar") ) : ?>
            <?php endif; ?>
            
    </div><!-- #sidebar -->
        
<?php get_footer(); ?>
