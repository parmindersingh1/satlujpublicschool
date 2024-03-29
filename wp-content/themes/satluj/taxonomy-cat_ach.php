<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
get_header(); ?>
	<div id="contentarea-inner">
    
    	<div class="content-inner-wrap">
        	<?php get_sidebar(); ?>
            
            <div class="right-wrap dark-brown">
            <?php if(have_posts()): ?>
				<h1 class="page-title"><?php printf( __( 'Achievement Archives: %s', 'twentytwelve' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>
                <?php while(have_posts()): the_post(); ?>
                <div class="news-block">
                	<h2 class="main-title"><?php the_title(); ?></h2>
                    <div class="term-cats">
                    	<?php echo get_the_term_list( $post->ID, 'cat_ach', 'Categories: ', ', ', '' ); ?>
                    </div>
					<?php the_content(); ?>
                </div><!-- news-box -->
				<?php endwhile; wp_pagenavi(); endif; ?> 
            </div><!--right-wrap ends-->
        </div><!--content-inner-wrap ends-->
            
       
		<?php include(TEMPLATEPATH . '/social.php'); ?>
        
    </div><!--contentarea ends-->
<?php get_footer(); ?>