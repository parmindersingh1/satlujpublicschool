<?php get_header(); ?>
	<div id="contentarea-inner">
    	<div class="content-inner-wrap">
        	<?php get_sidebar(); ?>
            <div class="right-wrap dark-brown">
            <?php if(have_posts()): while(have_posts()): the_post(); ?>
				<h1 class="main-title"><?php the_title(); ?></h1>
				<?php $images = get_field('add_images_to_gallery'); ?>
                <?php if($images): ?>
                <div class="single-gallery">
                	<?php foreach($images as $image): ?>
                	<div class="image">
                        <a href="<?php echo $image['sizes']['large']; ?>" data-fancybox-group="gallery" class="fancybox">
                            <img src="<?php echo $image['sizes']['thumbnail']; ?>" />
                        </a>
                    </div><!-- image -->
                    <?php endforeach; ?>
                </div><!-- single-gallery -->
                <?php endif; ?>
			<?php endwhile; endif; ?> 
            </div><!--right-wrap ends-->
        </div><!--content-inner-wrap ends-->
       
       <?php include(TEMPLATEPATH . '/social.php'); ?>
        
    </div><!--contentarea ends-->
<?php get_footer(); ?>