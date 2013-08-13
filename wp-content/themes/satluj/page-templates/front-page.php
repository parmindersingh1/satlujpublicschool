<?php
/**
 * Template Name: Front Page Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
get_header(); ?>
	 <div id="bannerarea">
    	 <div id="bannerarea-inner">
              <div class="flexslider">
                      <ul class="slides">
                        <li><a href="http://daljeetkclients.com/anupam/satluj/about-satluj/"><img src="<?php bloginfo('template_url'); ?>/images/slide1.jpg" /></a></li>
                        <li><a href="http://daljeetkclients.com/anupam/satluj/senior-wing/"><img src="<?php bloginfo('template_url'); ?>/images/slide2.jpg" /><a></li>
                        <li><a href="http://daljeetkclients.com/anupam/satluj/junior-wing/"><img src="<?php bloginfo('template_url'); ?>/images/slide3.jpg" /><a></li>                        
                      </ul>
                    </div><!--flexslider ends-->
  </div><!--bannerarea-inner ends-->
    </div><!--bannerarea ends-->
    
    
    <div id="contentarea">
    
    	<div class="newsarea">
        	<div class="news-titlebox">Latest<br><span class="news-cap">NEWS</span><br><span class="news-link"><a href="<?php echo get_permalink(76); ?>">MORE NEWS >></a></span></div>
            <?php query_posts(array('post_type' => 'post', 'posts_per_page' => 3)); ?>
            <?php while(have_posts()): the_post(); ?>
            <div class="news-box">
            	<h2 class="news-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <p class="news-date"><?php the_time('F j, Y'); ?></p>
            </div><!--news-box ends-->
            <?php endwhile; wp_reset_query(); ?>
        </div>
        
        
        <!------------newsarea ends------------>
        
       <?php $blocks = get_field('order_home_pages','options'); ?> 
       
       <div class="box-wrap">
		<?php if($blocks): ?>
        <?php $i = 1; ?>
        <?php foreach($blocks as $post): setup_postdata($post); ?>
        <?php if($i%3==0): $last_block = 'last-block'; else : $last_block = ''; endif;?>
       	<div class="content-box blue-bor <?php the_field('block-border'); ?> <?php echo $last_block; ?>">
        	<?php if(get_the_post_thumbnail()): ?>
            <div class="box-photo">
            	<?php the_post_thumbnail(array(92, 92)); ?>
            </div><!-- box-photo -->
            <?php endif; ?>
            <div class="box-text">
            	<h3 class="box-heading"><?php the_title(); ?></h3>
                <p><?php the_field('home_intro'); ?></p>
                <div class="read-link"><a href="<?php the_permalink(); ?>">Read more...</a></div>
            </div>
        </div><!--content-box ends-->
        <?php if($i%3 == 0): ?>
        	<div class="clear"></div>
        <?php endif; $i++ ?>
        <?php endforeach; wp_reset_postdata(); ?>
        <?php endif; ?>
        
        
       </div>
       <!------------box-wrap ends------------>
       
       
       <?php include(TEMPLATEPATH . '/social.php'); ?>
       
       
        
    </div><!--contentarea ends-->
<?php get_footer(); ?>