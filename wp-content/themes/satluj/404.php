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
            
				<div class="news-block">
                	<h2 class="main-title">404 Error!!!</h2>
                    <p>Page not found!</p>
                    
                </div><!-- news-box -->
				
            </div><!--right-wrap ends-->
        </div><!--content-inner-wrap ends-->
            
       
		<?php include(TEMPLATEPATH . '/social.php'); ?>
        
    </div><!--contentarea ends-->
<?php get_footer(); ?>
