<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<h1 itemprop="name" class="product_title entry-title"><?php the_title(); ?></h1>

<?php
/*
* Product title is the Brand Name, e.g. 'Fresh'
* Product's subtitle is the actual product name, e.g. 'Sugar Lip Treatment SPF 15'

* Rather than create subtitle as a new column in wp_posts table
* I store the subtitle in wp_postmtea with meta_key 'subtitle' 
* and meta_value as the product's name
*
*/

$subtitle = get_post_meta(get_the_ID(), 'subtitle', $single = true); 
if($subtitle !== '') {

?>
<p class="product-review-module product-review-name"><?php echo $subtitle; ?></p>

<?php
}
?>