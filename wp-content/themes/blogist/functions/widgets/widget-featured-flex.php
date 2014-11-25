<?php
add_action('widgets_init', 'tmnf_featured_flex_widget');

function tmnf_featured_flex_widget()
{
	register_widget('tmnf_featured_flex_widget');
}

class tmnf_featured_flex_widget extends WP_Widget {
	
	function tmnf_featured_flex_widget()
	{
		$widget_ops = array('classname' => 'tmnf_featured_flex_widget', 'description' => 'Featured posts widget.');

		$control_ops = array('id_base' => 'tmnf_featured_flex_widget');

		$this->WP_Widget('tmnf_featured_flex_widget', 'Themnific - Slider', $widget_ops, $control_ops);
		
		function check_widget_flex() {
    		if( is_active_widget( '', '', 'tmnf_featured_flex_widget' ) ) { // check if flex carousel widget is used
				wp_enqueue_script('jquery.flexslider-min', get_template_directory_uri() .'/js/jquery.flexslider-min.js','','', true);
        		wp_enqueue_script('jquery.flexslider.start.widget', get_template_directory_uri() .'/js/jquery.flexslider.start.widget.js','','', true);
    }
}

add_action( 'init', 'check_widget_flex' );
    }
	
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
			<?php
			$recent_posts = new WP_Query(array(
				'showposts' => $posts,
				'cat' => $categories,
			));
			?>
            <div class="flexslider widgetflexslider">
            <ul class="slides">
			<?php  while($recent_posts->have_posts()): $recent_posts->the_post(); ?>

			<li class="body2">
                    
					<?php if ( has_post_thumbnail()) : ?>
                         <a href="<?php the_permalink(); ?>" title="<?php the_title();?>" >
                         <?php the_post_thumbnail( 'w-slider',array('title' => "")); ?>
                         </a>
                    <?php endif; ?>
                    
                    <div class="inpost">
                    
                    <h2 class="upperfont"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php echo short_title('...', 16); ?></a></h2>
                    
                    </div>
                        
			</li>

			<?php  endwhile; ?>
			</ul>
            </div>

		
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
		$defaults = array('title' => 'Recent Posts', 'post_type' => 'all', 'categories' => 'all', 'posts' => 6, 'show_excerpt' => null);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
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
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo esc_attr($instance['posts']); ?>" />
		</p>
		

	<?php }
}
?>