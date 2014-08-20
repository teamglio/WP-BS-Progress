<?php
/**
 * @package WP_BS_Progress
 * @version 1.0
 */
/*
Plugin Name: Bootstrap Progress Widget
Plugin URI: 
Description: Adds a Bootstrap styled progress widget.
Author: Stephanus van Vuuren
Version: 1.0
Author URI: http://glio.co.za
*/
function wp_progress_load_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'progress_bootstrap', $plugin_url . 'assets/css/bootstrap.min.css' );
    wp_enqueue_style( 'progress_bootstrap_theme', $plugin_url . 'assets/css/bootstrap-theme.min.css' );
}
add_action( 'wp_enqueue_scripts', 'wp_progress_load_plugin_css' );

function percent($number){
    return $number * 100;
}
/////////////////////
//////progress widget
/////////////////////
class ProgressWidget extends WP_Widget {

	function ProgressWidget() {
			$widget_ops = array('description' => 'Progress bar widget');
			$this->WP_Widget('ProgressWidget', 'Progress Widget', $widget_ops);
	}

	function form( $instance ) {
		// Output admin widget options form
		$title = null;
		if (! empty( $instance['title'] ) ) { 
			$title = apply_filters('widget_title', $instance['title'] );
		};
		$description = null;
		if (! empty( $instance['description'] ) ) { 
			$description = $instance['description'];
		};
		$prefix = null;
		if (! empty( $instance['prefix'] ) ) { 
			$prefix = $instance['prefix'];
		};
		$target = null;
		if (! empty( $instance['target'] ) ) { 
			$target = $instance['target'];
		};
		$progress = null;
		if (! empty( $instance['progress'] ) ) { 
			$progress = $instance['progress'];
		};
		$style = null;
		if (! empty( $instance['style'] ) ) { 
			$style = $instance['style'];
		};
		$animate = null;
		if (! empty( $instance['animate'] ) ) { 
			$animate = $instance['animate'];
		};
		$showpercentage = null;
		if (! empty( $instance['showpercentage'] ) ) { 
			$showpercentage = $instance['showpercentage'];
		};
		$showamounts = null;
		if (! empty( $instance['showamounts'] ) ) { 
			$showamounts = $instance['showamounts'];
		};
		$morelink = null;
		if (! empty( $instance['morelink'] ) ) { 
			$morelink = $instance['morelink'];
		};			
			
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php if (!empty ($title)) { echo $title; } ?>" />
				<label for="<?php echo $this->get_field_id('description'); ?>">Description:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="text" value="<?php echo $description; ?>" />
				<label for="<?php echo $this->get_field_id('morelink'); ?>">More info link: (with http://)</label>
				<input class="widefat" id="<?php echo $this->get_field_id('morelink'); ?>" name="<?php echo $this->get_field_name('morelink'); ?>" type="text" value="<?php echo $morelink; ?>" />
				<label for="<?php echo $this->get_field_id('prefix'); ?>">Prefix:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('prefix'); ?>" name="<?php echo $this->get_field_name('prefix'); ?>" type="text" value="<?php echo $prefix; ?>" />
                <label for="<?php echo $this->get_field_id('target'); ?>">Target:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('target'); ?>" name="<?php echo $this->get_field_name('target'); ?>" type="number" value="<?php echo $target; ?>" />
				<label for="<?php echo $this->get_field_id('progress'); ?>">Progress:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('progress'); ?>" name="<?php echo $this->get_field_name('progress'); ?>" type="number" value="<?php echo $progress; ?>" />
				<label for="<?php echo $this->get_field_id('style'); ?>">Style:</label>
				<select class="widefat" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" type="number" value="<?php echo $style; ?>">
					<option value="progress-bar-primary">Default</option>
					<option value="progress-bar-success" <?php if ($style == 'progress-bar-success') { echo 'selected' ;} ?> >Success</option>
					<option value="progress-bar-info" <?php if ($style == 'progress-bar-info') { echo 'selected' ;} ?> >Info</option>
					<option value="progress-bar-warning" <?php if ($style == 'progress-bar-warning') { echo 'selected' ;} ?> >Warning</option>
					<option value="progress-bar-danger" <?php if ($style == 'progress-bar-danger') { echo 'selected' ;} ?> >Danger</option>
				</select>
				
				<input class="widefat" id="<?php echo $this->get_field_id('animate'); ?>" name="<?php echo $this->get_field_name('animate'); ?>" type="checkbox" value="1" <?php checked( '1', $animate ); ?> />
				<label for="<?php echo $this->get_field_id('animate'); ?>"> Animate?</label></br>
				<input class="widefat" id="<?php echo $this->get_field_id('showpercentage'); ?>" name="<?php echo $this->get_field_name('showpercentage'); ?>" type="checkbox" value="1" <?php checked( '1', $showpercentage ); ?> />
				<label for="<?php echo $this->get_field_id('showpercentage'); ?>"> Show Percentage?</label></br>
			    <input class="widefat" id="<?php echo $this->get_field_id('showamounts'); ?>" name="<?php echo $this->get_field_name('showamounts'); ?>" type="checkbox" value="1" <?php checked( '1', $showamounts ); ?> />
                <label for="<?php echo $this->get_field_id('showamounts'); ?>"> Show Amounts?</label>
            </p>
			<?php

	} //ending form creation

	function update( $new_instance, $old_instance ) {
		// Save widget options
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['description'] = strip_tags($new_instance['description']);
            $instance['prefix'] = strip_tags($new_instance['prefix']);
			$instance['target'] = strip_tags($new_instance['target']);
			$instance['progress'] = strip_tags($new_instance['progress']);
			$instance['style'] = strip_tags($new_instance['style']);
			$instance['animate'] = strip_tags($new_instance['animate']);
			$instance['showpercentage'] = strip_tags($new_instance['showpercentage']);
            $instance['showamounts'] = strip_tags($new_instance['showamounts']);
			$instance['morelink'] = strip_tags($new_instance['morelink']);
			return $instance;
	} //ending update


	function widget( $args, $instance ){
		// Widget output
			extract($args, EXTR_SKIP);
			$title = $instance['title'];
			$description = $instance['description'];
            $prefix = $instance['prefix'];
			$target = $instance['target'];
			$progress = $instance['progress'];
			$style = $instance['style'];
			$animate = $instance['animate'];
			$showpercentage = $instance['showpercentage'];
            $showamounts = $instance['showamounts'];
			$morelink = $instance['morelink'];
			echo $before_widget;
			echo $before_title;
			echo $title;
			echo $after_title;
			$diff = ($progress / $target);
			$percentage = percent($diff);
			?>
			<p> <?php echo $description; ?></p>
			<div class="progress progress-striped <?php if ($animate == 1) { echo 'active'; } ?>">
			  <div class="progress-bar <?php echo $style; ?>" role="progressbar" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="<?php echo $target; ?>" style="width: <?php echo round($percentage) . '%'; ?>">
			    <?php 
			    if ($showpercentage == 1) {
			    	echo round($percentage) . '%';
				}
			    ?>			    
			  </div>
              <?php if ($showamounts == 1) {  ?>
              <span class="pg-bar-progress"><i class="fa fa-angle-double-left"></i> <?php if (!empty($prefix)) { echo $prefix; } echo number_format($progress); ?></span>
              <?php } ?>
			</div>
			<?php if ($showamounts == 1) {  ?>
              <div class="pg-bar-target"><?php if (!empty($prefix)) { echo $prefix; } echo number_format($target);?></div>
              <?php } ?>
			<?php if (!empty($morelink)) { ?> 
			<div class="text-right">
				<a href="<?php echo $morelink; ?>">More info <i class="fa fa-info-circle"></i></a>
			</div>
			<?php } ?>
			<?php
			echo $after_widget;
	} //ending widget display

}
//////////////////////////////////
function register_rghs_custom_widgets() {
	register_widget( 'ProgressWidget' );
}
add_action( 'widgets_init', 'register_rghs_custom_widgets' );


?>