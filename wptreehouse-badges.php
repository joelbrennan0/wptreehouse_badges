<?php
/*
===============================
 *  Treehouse Badges Plugin
 *  License: GPL2
===============================
*/

/*
Plugin Name: Treehouse Badges
Plugin URI:  http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Show members' Treehouse statics on your site.
Version:     1.5
Author:      Joel Brennan
Author URI:  http://URI_Of_The_Plugin_Author
License:     GPL2
 
{Treehouse Badges} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
{Treehouse Badges} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with {Treehouse Badges}. If not, see {License URI}.
*/

/* 
 *  Assign Plugin URL:
*/

$plugin_url = WP_PLUGIN_URL . '/wptreehouse-badges';
$options = [];

/* 
 *  Add link to Plugin from the Admin Menu under:
 *  'settings > Treehouse Badges'
*/

function wptreehouse_badges_menu() {
	/*
	 *  Use the add_options_page funtion
	 *  add_options_page( $page_title, $menu_title, $capability, $menu-slug, $function )
	 *
	*/
	add_options_page(
		'Treehouse Badges Plugin',
		'Treehouse Badges',
		'manage_options',
		'wptreehouse-badges',
		'wptreehouse_badges_options_page'
	);
}

add_action( 'admin_menu', 'wptreehouse_badges_menu' );

function wptreehouse_badges_options_page() {
	if ( !current_user_can( 'manage_options' ) ) {
		wp_die( 'Sorry you have insufficient privilages to access this area.
			Please contact your Administrator.' );
	}

	global $plugin_url;
	global $options;

	if( isset( $_POST['wptreehouse_form_submitted'] ) ) {
		$hidden_field = esc_html( $_POST['wptreehouse_form_submitted'] );
			if ($hidden_field == 'Y') {
				$wptreehouse_username = esc_html( $_POST['wptreehouse_username'] );
				$wptreehouse_profile = wptreehouse_badges_get_profile( $wptreehouse_username );

			$options['wptreehouse_username'] = $wptreehouse_username;
			$options['wptreehouse_profile'] = $wptreehouse_profile;
			$options['last_updated'] = time();

			update_option( 'wptreehouse_badges', $options );

			} 
	}

	$options = get_option('wptreehouse_badges');

	if ( $options != '' )  {
		$wptreehouse_username = $options['wptreehouse_username'];
	    $wptreehouse_profile = wptreehouse_badges_get_profile( $wptreehouse_username );
	}


	require( 'inc/options-page-wrapper.php' );
}

class Wptreehouse_Badges_Widget extends WP_Widget {

	function __construct() {
		// Instantiate the parent object
		parent::__construct( false, 'Treehouse Badges Widget' );
	}

	function widget( $args, $instance ) {
		// Widget output

		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$num_badges = $instance['num_badges'];
		$display_tooltip = $instance['display_tooltip'];

		$options = get_option( 'wptreehouse_badges' );
		$wptreehouse_profile = $options['wptreehouse_profile'];

		require( 'inc/front-end.php' );
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options

		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['num_badges'] = strip_tags($new_instance['num_badges']);
		$instance['display_tooltip'] = strip_tags($new_instance['display_tooltip']);

		return $instance;
	}

	function form( $instance ) {
		// Output admin widget options form

		$title = esc_attr($instance['title']);
		$num_badges = esc_attr($instance['num_badges']);
		$display_tooltip = esc_attr($instance['display_tooltip']);

		$options = get_option( 'wptreehouse_badges' );
		$wptreehouse_profile = $options['wptreehouse_profile'];

		require( 'inc/widget-fields.php' );
	}
}

function wptreehouse_badges_register_widgets() {
	register_widget( 'Wptreehouse_Badges_Widget' );
}

add_action( 'widgets_init', 'wptreehouse_badges_register_widgets' );

function wptreehouse_badges_shortcode( $atts, $content = null ) {
	global $post;

	extract( shortcode_atts( array(
			'before_widget' => '',
			'before_title' => '',
			'title' => '',
			'after_title' => '',
			'num_badges' => '8',
			'tooltip' => 'on',
			'after_widget' => ''
		), $atts ) );

	if ( $tooltip == 'on' ) $tooltip = 1;
	if ( $tooltip == 'off' ) $tooltip = 0;

	$display_tooltip = $tooltip;

	$options = get_option( 'wptreehouse_badges' );
	$wptreehouse_profile = $options['wptreehouse_profile'];

	ob_start();

	require( 'inc/front-end.php' );

	$content = ob_get_clean();

	return $content;
}
add_shortcode( 'wptreehouse_badges', 'wptreehouse_badges_shortcode' );


function wptreehouse_badges_get_profile( $wptreehouse_username ) {
	$json_feed_url = "http://teamtreehouse.com/" . $wptreehouse_username . ".json";
	$args = array( 'timeout' => 120 );
	$json_feed = wp_remote_get( $json_feed_url, $args );

	$wptreehouse_profile = json_decode( $json_feed['body'] );
	return $wptreehouse_profile;
}

function wptreehouse_badges_refresh_profile() {
	$options = get_option( 'wptreehouse_badges' );
	$last_updated = $options['last_updated'];

	$current_time = time();

	$update_difference = $current_time - $last_updated;

	if ( $update_difference > 86400 ) {
		$wptreehouse_username = $options['wptreehouse_username'];

		$options['wptreehouse_profile'] = wptreehouse_badges_get_profile( $wptreehouse_username );
		$options['last_updated'] = time();

		update_option( 'wptreehouse_badges', $options );
	}

	die();
}

add_action( 'wp_ajax_wptreehouse_badges_refresh_profile', 'wptreehouse_badges_refresh_profile' );

function wp_treehouse_badges_enable_frontend_ajax() {
?>
	<script>
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>'
	</script>
<?php
}
add_action('wp_head', 'wp_treehouse_badges_enable_frontend_ajax');


function wptreehouse_badges_backend_styles() {
	wp_enqueue_style( 'wptreehouse_badges_backend_styles', plugins_url( 'wptreehouse-badges/wptreehouse-badges.css' ) );
}
add_action( 'admin_head', 'wptreehouse_badges_backend_styles' );

function wptreehouse_badges_frontend_scripts_and_styles() {
	wp_enqueue_style( 'wptreehouse_badges_frontend_styles', plugins_url( 'wptreehouse-badges/wptreehouse-badges.css' ) );
	wp_enqueue_script( 'wptreehouse_badges_frontend_js', plugins_url( 'wptreehouse-badges/wptreehouse-badges.js' ), array('jquery'), '', true );
}
add_action( 'wp_enqueue_scripts', 'wptreehouse_badges_frontend_scripts_and_styles' );

?>