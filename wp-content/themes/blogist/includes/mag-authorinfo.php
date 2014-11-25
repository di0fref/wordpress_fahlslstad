		<div class="postauthor postinfo">
        	<?php _e('about the author','themnific');?>: <?php the_author_posts_link(); ?>
			<?php  echo get_avatar( get_the_author_meta('ID'), '75' );   ?>
 			<div class="authordesc"><?php the_author_meta('description'); ?></div>
		</div>