<?php

//Creating the table
/*
global $wpdb;

$table_name = $wpdb->prefix . "pt_quiz"; 
*/
/*
 * Code Source: http://codex.wordpress.org/Creating_Tables_with_Plugins
 * WP will set the default character set and collation for this table.
 * If we don't do this, some characters could end up being converted 
 * to just ?'s when saved in our table.
 */
/*$charset_collate = '';

if ( ! empty( $wpdb->charset ) ) {
  $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
}

if ( ! empty( $wpdb->collate ) ) {
  $charset_collate .= " COLLATE {$wpdb->collate}";
}

$sql = "CREATE TABLE ". $table_name ."(
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  name tinytext NOT NULL,
  text text NOT NULL,
  url varchar(55) DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
) $charset_collate;";

//define('ABSPATH','http://localhost:8888/');
//require_once( ABSPATH. 'wp-admin/includes/upgrade.php' );
require_once('wp-admin/includes/upgrade.php' );
dbDelta( $sql );
*/
?>
