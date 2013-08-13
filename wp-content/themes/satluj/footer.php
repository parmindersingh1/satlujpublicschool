<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	<div id="green-footer">
    	<div id="green-footer-inner">
        	<div class="footer-greenleft"><img src="<?php bloginfo(template_url); ?>/images/map.jpg" alt="" /></div>
            <div class="footer-greenright">
            
            	<?php if(get_field('text_blocks', 'options')): ?>
					<?php while(has_sub_field('text_blocks', 'options')): ?>
                        <div class="footer-box">
                            <h4 class="footer-title"><?php the_sub_field('text_block_title') ?></h4>
                            <p><?php the_sub_field('text_block_content') ?></p>
                        </div><!--footer-box ends-->
                    <?php endwhile; ?>
                <?php endif; ?>
                
            </div><!-- footer-greenright -->
        </div><!--green-footer-inner ends-->
    </div><!--green-footer ends-->
    
    
    
    <div id="grey-footer">
    	<div id="grey-footer-inner">
        	<div class="grey-footer-left"><a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo(template_url); ?>/images/footer-logo.jpg" alt="" /></a></div>
            <div class="grey-footer-right">All rights reserved. Copyright © <?php the_time('Y'); ?> by Satluj Public School. <br> 	
</div>
        </div>
    </div><!--grey-footer ends-->
    
    

<?php wp_footer(); ?>
</body>
</html>