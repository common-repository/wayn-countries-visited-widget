<?php
/*
Plugin Name: WAYN Countries Visited Widget
Plugin URI: http://hebeisenconsulting.com/wayn-countries-visited-widget/
Description: WAYN Countries Visited Widget is an awesome sidebar widget which display your visited countries straight from your WAYN.com profile.
Version: 1.0.1
Author: Hebeisen Consulting - R Bueno
Author URI: http://www.hebeisenconsulting.com
License: A "Slug" license name e.g. GPL2

   Copyright 2011 Hebeisen Consulting

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
    Powered by PHP Simple HTML DOM Parser.
*/
define('PLUGINPATH', ABSPATH . 'wp-content/plugins/WAYN');
include( PLUGINPATH . '/domp/simple_html_dom.php');

class WAYN_Countries_Visited_Widget extends WP_Widget {
	/** constructor */
	function __construct() {
		parent::WP_Widget( /* Base ID */'WAYN_Countries', /* Name */'WAYN Countries Visited', array( 'description' => 'WAYN Countries Visited Widget' ) );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
		extract( $args );
		
		$username = $instance['username']; //"rextangtw";
		$title = $instance['title'];
		$profile_link = $instance['profile_link'];
		
		echo $before_widget;
		if ( $username )
		{
			$memberPage = "http://member.wayn.com/" . $username;
			$html = file_get_html($memberPage);
			$size = $instance['size'];
			
			echo $before_title . $title . $after_title; 
			foreach($html->find("div[label^=I've been to]") as $country) {
			 echo "<img width=" .$size. " height=" .$size. " src=" .  $country->children(0)->children(0)->attr['src'] . " title=\"" . $country->children(0)->children(0)->attr['alt'] . "\">";    
			}
			if( $profile_link )
			 echo "<br><a class=\"waynlink\"href=\"" .$memberPage. "\"><small>Visit my WAYN profile</small></a>";
		}else{
			echo $before_title . $title . $after_title;
?>
		No WAYN username specified. Please specify it in the Widget section!
		<?php } echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['size'] = strip_tags($new_instance['size']);
		$instance['profile_link'] = strip_tags($new_instance['profile_link']);
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
			$username = esc_attr( $instance[ 'username' ] );
			$size = esc_attr( $instance[ 'size' ] );
			$profile_link = esc_attr( $instance['profile_link'] );
		}
		else {
			$title = __( 'Countries I Visited', 'text_domain' );
			$username = "";
			$size = "90";
			$profile_link = "Yes";
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('WAYN Username:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" />
		</p>
		<p>		
		<input class="checkbox" id="<?php echo $this->get_field_id('profile_link'); ?>" name="<?php echo $this->get_field_name('profile_link'); ?>" type="checkbox" value="Yes" <?php if( $profile_link == "Yes" ){ echo 'checked="checked"'; } ?> /> 
		<label for="<?php echo $this->get_field_id('profile_link'); ?>"><?php _e('Show profile link:'); ?></label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Flag thumbnail size:'); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
		 <option value = "40" <?php if( $size == "40" ){ echo "selected"; } ?>>40</option>
		 <option value = "60" <?php if( $size == "60" ){ echo "selected"; } ?>>60</option>
		 <option value = "90" <?php if( $size == "90" ){ echo "selected"; } ?>>90</option>
		</select>
		</p>
		<?php 
	}

}

add_action( 'widgets_init', create_function( '', 'register_widget("WAYN_Countries_Visited_Widget");' ) );

?>