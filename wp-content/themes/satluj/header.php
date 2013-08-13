<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-likebox">
<div class="fb-like" data-href="https://www.facebook.com/pages/Satluj-Public-School-Sectors-2-4-Panchkula/308464482585822" data-send="false" data-layout="count" data-width="100" data-show-faces="false"></div>
</div>


<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<div id="twitter_like">
           <a href="https://twitter.com/SatlujSchool" class="twitter-follow-button" data-dnt="true" data-count="vertical">Follow</a>
</div>

	<div id="headerarea">
    
    
    	<div class="topgreenbar">
        	<div class="topnav">
            	<?php wp_nav_menu(array('menu' => 'top-menu-1', 'container' => false)); ?>
            </div><!--topnav ends-->
            <div class="searcharea">
            	<span>Search:</span>
                <form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
                    <input type="text" value="" name="s" id="s" />
                    <input type="submit" id="searchsubmit" value=" " />
                </form>
            </div><!--searcharea ends-->
        </div><!--topgreenbar ends-->
        
        <div class="whitearea">
        	<div class="logo"><a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo(template_url); ?>/images/logo.png" alt="" /></a></div>
            <div class="white-rightarea">
            	<?php if(get_field('top_read_button_title', 'options')): ?>
            	<div class="reg-box"><a href="<?php the_field('red_button_link', 'options'); ?>"><?php the_field('top_read_button_title', 'options'); ?></a> </div>

<!--<div class="holiday-box"><a href="<?php bloginfo(template_url); ?>/images/SUMMER-VACATION-HOME-WORK-2013-14-1.pdf" target="_blank"> Summer Vacation HW 2013</a> </div> -->

                <?php endif; ?>
                <div class="clear"></div>
                <div class="nav">
	            	<?php wp_nav_menu(array('menu' => 'top-menu-2', 'container' => false)); ?>
                </div><!--nav ends-->
            </div><!--white-rightarea ends-->

        </div>
        <!------------whitearea ends------------>
        
    </div><!--headerarea ends-->