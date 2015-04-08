<?php
/*
 *	Plugin Name: Beauty Quiz 
 *	Plugin URI: https://www.ruthfombrun.com
 *	Description: Creates beauty quiz table in wpdb & stores submissions there.
 *	Version: 0.0
 *	Author: Ruth Fombrun
 *	Author URI: https://www.facebook.com/ruth.fombrun
 *	License: GPL2
 *
*/
global $ptquiz_db_version;
$ptquiz_db_version = '1.0';
$options = array();

function ptquiz_admin_menu(){

	/*
	*	Use the add_options_page function
	*	add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );
	*/

	add_options_page( 
		'Beauty Quiz Plugin', 
		'Beauty Quiz', 
		'manage_options', 
		'beauty-quiz', 
		'ptquiz_options_page' );
}

add_action( 'admin_menu', 'ptquiz_admin_menu' );

function ptquiz_options_page(){

	if(!current_user_can( 'manage_options' )){
		wp_die( 'You do not have sufficient permission to access this page.');
	}
	//global $wpdb;

	$yt_list = ptquiz_get_youtube_json() ;
	
	update_option('ptquiz_youtube', $yt_list );
	
//	echo '<p><strong> Most Popular Vid Name</strong>: '.$yt_list->{'feed'}->{'entry'}[1]->{'title'}->{'$t'}.'</p>';
//	echo '<a href=" '.$yt_list->{'feed'}->{'entry'}[0]->{'content'}->{'src'}.' ">Video Link</a>';
 	echo '<p>'.$yt_list->{'entry'}->{'title'}->{'$t'}.'</p>';
	//var_dump($yt_list);
	
}

function ptquiz_get_youtube_json(){

//	$json_feed_url = 'http://gdata.youtube.com/feeds/api/standardfeeds/most_popular?v=2&alt=json';
	$json_feed_url = 'https://gdata.youtube.com/feeds/api/users/teasedblackpearlz?v=2.1&alt=json';
	$args = array('timeout'=> 120);

	$json_feed = wp_remote_get( $json_feed_url, $args );
	
	$yt_list = json_decode( $json_feed['body']);
	return $yt_list;

}

function ptquiz_install($quiz_keys) {
	global $wpdb;
	global $ptquiz_db_version;

	$table_name = $wpdb->prefix . 'beauty_quiz2';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id bigint(20) NOT NULL,
		total_times_submitted smallint(6) NOT NULL,
		last_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL";

	foreach($quiz_keys as $col_name){
				$sql .= $col_name." varchar(2) NOT NULL,";
			}
		
	$sql .=	"
		
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	echo 'Success!';
	add_option( 'ptquiz_db_version', $ptquiz_db_version );
}

function ptquiz_create_quiz_page(){

	//if(	!post_exists('Quiz')){

		// Create post object
		$my_post = array(
		  'post_title'    => 'Quiz1',
		  'post_type'     => 'page',
		  'post_status'   => 'publish',
		  'post_author'   => 1,
		  'page_template' => 'page-wrapper.php'
		);

		// Insert the post into the database
		wp_insert_post( $my_post, true );

	//}

}
/*
	function ptquiz_insert($quiz_keys, $quiz_responses) {
		global $wpdb;

		$table = $wpdb->prefix . 'beauty_quiz2';
		
		// Create an array of the data to be inserted
		// where key => value is 'column_name'=>'value'
		// first 2 fields are id & last_updated
		// remaining fields are 109 quiz responses
		$data = array( 
				'id' => '', 
				'last_updated' => current_time('mysql'));

		foreach($quiz_keys as $i){
			$data[$i] = $quiz_responses[$i];  
		}
			

		//Create an array of the table's format
		//the table's structure is 111 columns where the first is an integer
		//and remaining 110 are strings
		$col_format_d = array('%d');
		$col_format_s = array_fill(0, 110, '%s');
		$format = array_merge($col_format_d, $col_format_s);


	 	$wpdb->insert( $table, $data, $format );

		//print_r($data);

	}
*/
register_activation_hook( __FILE__, 'ptquiz_install' );
register_activation_hook( __FILE__, 'ptquiz_create_quiz_page' );

//register_activation_hook( __FILE__, 'ptquiz_install_data' );

?>