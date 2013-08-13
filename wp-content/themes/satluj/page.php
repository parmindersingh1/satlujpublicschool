<?php get_header(); ?>
	<div id="contentarea-inner">
    
    	<div class="content-inner-wrap">

        	<?php get_sidebar(); ?>
            
            <div class="right-wrap dark-brown">
            <?php if(have_posts()): while(have_posts()): the_post(); ?>
				<h1 class="main-title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
				<?php endwhile; endif; ?> 
            </div><!--right-wrap ends-->
        </div><!--content-inner-wrap ends-->
        
       
            
       
       <?php include(TEMPLATEPATH . '/social.php'); ?>

        
    </div><!--contentarea ends-->
<?php get_footer(); ?>