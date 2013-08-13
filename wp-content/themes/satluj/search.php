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
            <h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentytwelve' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			<?php while(have_posts()): the_post(); ?>
				<div class="news-block">
                	<h2 class="main-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
                    <a href="<?php the_permalink(); ?>">Read more...</a>
                </div><!-- news-box -->
				<?php endwhile; wp_pagenavi(); else: echo 'No results found'; endif; ?> 
            </div><!--right-wrap ends-->
        </div><!--content-inner-wrap ends-->
            
       
		<?php include(TEMPLATEPATH . '/social.php'); ?>
        
    </div><!--contentarea ends-->
<?php get_footer(); ?>