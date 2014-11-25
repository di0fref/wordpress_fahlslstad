<li>

			<?php if ( has_post_thumbnail()) : ?>
                <a href="<?php the_permalink(); ?>" title="<?php the_title();?>" >
                <?php the_post_thumbnail( 'blog',array('title' => "")); ?>
                </a>
            <?php endif; ?>
            
            <p class="meta"><?php the_time(get_option('date_format')); ?></p>

            <h2 class="upperfont"><a href="<?php the_permalink(); ?>"><?php the_title(); ?> <?php echo tmnf_icon() ?> </a></h2>
             
            
</li>