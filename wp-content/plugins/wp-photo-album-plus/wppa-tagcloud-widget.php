<?php
/* wppa-tagcloud-widget.php
* Package: wp-photo-album-plus
*
* display the tagcloud widget
* Version 4.9.14
*
*/

class TagcloudPhotos extends WP_Widget {
    /** constructor */
    function TagcloudPhotos() {
        parent::WP_Widget(false, $name = 'Tagcloud Photos');	
		$widget_ops = array('classname' => 'wppa_tagcloud_photos', 'description' => __( 'WPPA+ Photo Tags', 'wppa') );	//
		$this->WP_Widget('wppa_tagcloud_photos', __('Photo Tag Cloud', 'wppa'), $widget_ops);															//
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {		
		global $widget_content;
		global $wppa;
		global $wppa_opt;

        extract( $args );

		$instance = wp_parse_args( (array) $instance, array( 'title' => __('Photo Tags', 'wppa') ) );
        
 		$widget_title = apply_filters('widget_title', $instance['title']);

		// Display the widget
		echo $before_widget;
			
		if ( !empty( $widget_title ) ) { echo $before_title . $widget_title . $after_title; }
		
		echo '<div class="wppa-tagcloud" >'.wppa_get_tagcloud_html().'</div>';
		echo '<div style="clear:both"></div>';
		echo $after_widget;
    }
	
    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => __('Photo Tags', 'wppa') ) );
		$title = $instance['title'];
		
		echo '<p><label for="' . $this->get_field_id('title') . '">' . __('Title:', 'wppa') . '</label><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></p>';

    }

} // class TagcloudPhotos

// register Photo Tags widget
add_action('widgets_init', create_function('', 'return register_widget("TagcloudPhotos");'));

