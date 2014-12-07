<?php get_header(); ?>
<?php if (have_posts()): while(have_posts()): the_post();?>
	<article>
		<aside class="meta">
			<ul>
				<li class="date"><?php the_time('F jS, Y') ?></li>
				<li class="comment"><a href="<?php comments_link();?>"><?php comments_number(__('No Comment'), __('1 Comment'), __('% Comments')); ?></a></li>
			</ul>
		</aside>
		<h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
		<div class="content">
			<?php the_content("<p class='more_link'>Read More &raquo;</p>");?>
		</div>
	</article>
<?php endwhile;?>
<?php endif;?>
<?php get_sidebar();?>
<?php get_footer();?>