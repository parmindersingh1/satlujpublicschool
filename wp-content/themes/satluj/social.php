
<div class="social-box">
    <ul>
        <li class="social-heading">Connect With<br><span class="social-bigtxt">SATLUJ</span><br><span class="social-smltxt">VIA</span></li>
        <?php if(get_field('social_area', 'options')): ?>
        <?php while(has_sub_field('social_area', 'options')): ?>
        <li class="fb"><a href="<?php the_sub_field('socia_url'); ?>" target="_blank"><img src="<?php the_sub_field('socia_icon'); ?>" alt="<?php the_sub_field('socia_title'); ?>" /><?php the_sub_field('socia_title'); ?></a></li>
        <?php endwhile; endif; ?>
    </ul>
</div><!--social-box ends-->