<?php 
// Template Name: Galleries
get_header(); ?>

	<div id="contentarea-inner">
    
    	<div class="content-inner-wrap">
        	<?php get_sidebar(); ?>
            
            <div class="right-wrap dark-brown">
            <h1 class="page-title">Photo Gallery</h1>
            <?php if (get_query_var('paged')){ 
									$paged = get_query_var('paged'); 
								} elseif (get_query_var('page')){ 
									$paged = get_query_var('page'); 
								} else { 
									$paged = 1; 
								} ?>
            <?php query_posts(array('post_type' => 'post_gallery', 'paged' => $paged, 'posts_per_page' => 20 )); ?>
            <?php $i = 1; ?>
			<?php if(have_posts()): while(have_posts()): the_post(); ?>
            <?php if($i%3 == 0){ $last = 'gallery-block-last'; $clear = '<div class="clear"></div>'; } else {$last = ''; $clear = '';} ?>
					<div id="gallery">
                    <div class="gallery-block <?php echo $last; ?>">
                    	<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail(array(227, 227)); ?>
                            <span><?php the_title(); ?></span>
                        </a>
                    </div><!-- gallery-block -->
                    </div>
                    <?php echo $clear; ?>
				<?php $i++; endwhile; wp_pagenavi(); wp_reset_query();  endif; ?> 
            </div><!--right-wrap ends-->
        </div><!--content-inner-wrap ends-->
            
       
		<?php include(TEMPLATEPATH . '/social.php'); ?>
        
    </div><!--contentarea ends-->
<?php get_footer(); ?>