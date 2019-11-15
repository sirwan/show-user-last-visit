<?php
/*
Plugin Name: Show User Last Visit
Plugin URI: https://sirwan.me/plugin/wordpress/show-user-last-visit
Description: Show date and time of a users last visit in the All Users admin page. This field could be exported in a CSV spreadsheet too, from any other plugin.
Author: Sirwan Qutbi
Version: 1.0.0
Author URI: https://sirwan.me/
*/

function sirwan_show_user_last_visit_column( $columns ){
	
	$columns['sirwan_show_user_last_visit_column'] = 'Last Visit';

    return $columns;

}

function sirwan_show_user_last_visit_column_content( $content, $column, $user_id ){

	if ( $column === 'sirwan_show_user_last_visit_column' ) {
		
		$visit = get_user_meta($user_id, "sirwan_user_last_visited", true);

		if (empty($visit)) {

			return "Not visited";

		}else{

			$now = current_time("timestamp");

    		$diff = human_time_diff($now, $visit) . " " . __('ago');

			$date = date('d/m/Y  - H:i:s', $visit);

    		$content = '<span title="'.$date.'">'.$diff.'</span>';


			return $content;

		}

    }

}

function sirwan_show_user_last_visit_hit_count(){

	$timestamp = current_time("timestamp");

	update_user_meta( get_current_user_id(), "sirwan_user_last_visited", $timestamp );

}

add_action( 'init', 'sirwan_show_user_last_visit_hit_count', 10, 1 );

if (is_admin()) {

	add_filter( 'manage_users_columns', 'sirwan_show_user_last_visit_column' );

	add_filter( 'manage_users_custom_column', 'sirwan_show_user_last_visit_column_content', 10, 3 );

}