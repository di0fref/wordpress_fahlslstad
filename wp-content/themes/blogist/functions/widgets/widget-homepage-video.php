<?php
/*---------------------------------------------------------------------------------*/
/* Featured_Tabs Widget */
/*---------------------------------------------------------------------------------*/
add_action( 'widgets_init', 'video_tabs_widget' );

/*
 * Register widget.
 */
function video_tabs_widget() {
	register_widget( 'Featured_Tabs_Widget' );
}

/*
 * Widget class.
 */
class featured_tabs_widget extends WP_Widget {

	/* ---------------------------- */
	/* -------- Widget setup -------- */
	/* ---------------------------- */

	function Featured_Tabs_Widget() {

		/* Widget settings. */
		$widget_ops = array( 'classname' => 'featured_tabs-post-widget', 'description' => __('A video widget.', 'themnific') );

		
		/* Create the widget. */
		$this->WP_Widget( 'featured_tabs_widget', __('Themnific - Video', 'themnific'), $widget_ops );

		
	}

	/* ---------------------------- */
	/* ------- Display Widget -------- */
	/* ---------------------------- */

	function widget($args, $instance)
	{
		extract($args);
		
		$title = $instance['title'];
		$post_type = 'all';
		$categories = $instance['categories'];
		$posts = $instance['posts'];
		
		echo $before_widget;
		?>
		
		<?php
		$post_types = get_post_types();
		unset($post_types['page'], $post_types['attachment'], $post_types['revision'], $post_types['nav_menu_item']);
		
		if($post_type == 'all') {
			$post_type_array = $post_types;
		} else {
			$post_type_array = $post_type;
		}
		?>

			<h2 class="widget widget_spec"><a href="<?php echo get_category_link($categories); ?>"><span><i class="icon-youtube-play"></i> <?php echo $title; ?></span></a></h2>
			
			<?php
			$recent_posts = new WP_Query(array(
				'showposts' => $posts,
				'cat' => $categories,
			));
			?>	
                     
            <ul class="tab-container">
  
				<?php $counter = 1; while($recent_posts->have_posts()): $recent_posts->the_post(); if($counter == 1): ?>
                
  						<li id="tabbs-<?php the_ID(); ?>" class="tabitem">

								<?php $video_input = get_post_meta(get_the_ID(), 'tmnf_video', true);?>
                                <?php if($video_input) {?>
                                        <?php echo ($video_input); ?>
                                <?php } else {?>
                            
    
                        
									<?php if ( has_post_thumbnail()) : ?>
                                         <a href="<?php the_permalink(); ?>" title="<?php the_title();?>" >
                                         <?php the_post_thumbnail( 'videotabs',array('title' => "")); ?>
                                         </a>
                                    <?php endif; ?>
                                    
                                <?php }?> 
                                <div class="clearfix"></div>

  						</li>

				<?php else: ?>
  
                        <li class="tab">
                            <a href="<?php the_permalink(); ?>">
                            	<i class="fa fa-youtube-play"></i>
                                <?php if ( has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail( 'blog',array('title' => "")); ?>
                                <?php endif; ?>
                            </a>
                        </li>
				
                <?php endif; $counter++; endwhile; ?>
                
			</ul><!-- end .tab-container -->


		
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title'] = $new_instance['title'];
		$instance['post_type'] = 'all';
		$instance['categories'] = $new_instance['categories'];
		$instance['posts'] = $new_instance['posts'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Latest Video', 'post_type' => 'all', 'categories' => 'all', 'posts' => 4, 'show_excerpt' => null);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('categories'); ?>">Filter by Category:</label> 
			<select id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>" class="widefat categories" style="width:100%;">
				<option value='all' <?php if ('all' == $instance['categories']) echo 'selected="selected"'; ?>>all categories</option>
				<?php $categories = get_categories('hide_empty=0&depth=1&type=post'); ?>
				<?php foreach($categories as $category) { ?>
				<option value='<?php echo $category->term_id; ?>' <?php if ($category->term_id == $instance['categories']) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option>
				<?php } ?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('posts'); ?>">Number of posts:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo $instance['posts']; ?>" />
		</p>
		

	<?php }
}
?>