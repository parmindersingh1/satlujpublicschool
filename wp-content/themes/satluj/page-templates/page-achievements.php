<?php 
// Template Name: Achievements
get_header(); ?>

	<div id="contentarea-inner">
    
    	<div class="content-inner-wrap">
        	<?php get_sidebar(); ?>
            
            <div class="right-wrap dark-brown">
            <h1 class="page-title">Achievements</h1>
            <?php if (get_query_var('paged')){ 
									$paged = get_query_var('paged'); 
								} elseif (get_query_var('page')){ 
									$paged = get_query_var('page'); 
								} else { 
									$paged = 1; 
								} ?>
            <?php query_posts(array('post_type' => 'post_achievement', 'paged' => $paged )); ?>
			<?php if(have_posts()): while(have_posts()): the_post(); ?>
				<div class="news-block">
                	<h2 class="main-title"><?php the_title(); ?></h2>
                    <div class="term-cats">
                    	<?php echo get_the_term_list( $post->ID, 'cat_ach', 'Categories: ', ', ', '' ); ?>
                    </div>
					<?php the_content(); ?>
                </div><!-- news-box -->
				<?php endwhile; wp_pagenavi(); wp_reset_query();  endif; ?> 
            </div><!--right-wrap ends-->
        </div><!--content-inner-wrap ends-->
            
       
		<?php include(TEMPLATEPATH . '/social.php'); ?>
        
    </div><!--contentarea ends-->
<?php get_footer(); ?>