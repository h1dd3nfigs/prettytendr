<?php
/**
 * Content wrappers
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$template = get_option( 'template' );

switch( $template ) {
	case 'twentyeleven' :
		echo '<div id="primary"><div id="content" role="main">';
		break;
	case 'twentytwelve' :
		echo '<div id="primary" class="site-content"><div id="content" role="main">';
		break;
	case 'twentythirteen' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="entry-content twentythirteen">';
		break;
	case 'twentyfourteen' :
		echo '
			<div class="row">
		      <div class="small-12 small-centered medium-12 large-12 large-centered columns">
		        <div class="leaderboard-container">
		          <img class="show-for-medium-up" src="http://placehold.it/728x90">
		          <img class="hide-for-medium-up" src="http://placehold.it/320x50">
		         </div>
		      </div>  
		    </div>
		<div id="primary" class="content-area"><div id="content" role="main" class="site-content twentyfourteen"><div class="tfwc">';
		break;
	default :
		echo '<div id="container"><div id="content" role="main">';
		break;
}