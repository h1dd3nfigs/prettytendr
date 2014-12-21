<?php
/**
 * Addon: Sell Content
 * Addon URI: http://mycred.me/add-ons/sell-content/
 * Version: 1.3
 * Description: This add-on allows you to sell posts, pages or any public post types on your website. You can either sell the entire content or using our shortcode, sell parts of your content allowing you to offer "teasers".
 * Author: Gabriel S Merovingi
 * Author URI: http://www.merovingi.com
 */

if ( ! defined( 'myCRED_VERSION' ) ) exit;

define( 'myCRED_SELL',         __FILE__ );
define( 'myCRED_SELL_VERSION', myCRED_VERSION . '.1' );

/**
 * myCRED_Sell_Content_Module class
 * @since 0.1
 * @version 1.1
 */
if ( ! class_exists( 'myCRED_Sell_Content_Module' ) ) {
	class myCRED_Sell_Content_Module extends myCRED_Module {

		/**
		 * Construct
		 */
		function __construct() {
			parent::__construct( 'myCRED_Sell_Content_Module', array(
				'module_name' => 'sell_content',
				'register'    => false,
				'defaults'    => array(
					'post_types'  => 'post,page',
					'type'        => 'mycred_default',
					'pay'         => 'none',
					'pay_percent' => 100,
					'templates'   => array(
						'members'     => '<p>Buy this %post_type% for only %price% %buy_button%</p>',
						'visitors'    => '<p><a href="%login_url_here%">Login</a> to buy access to this %post_type%.</p>',
						'cantafford'  => "<p>You do not have enough %plural% to buy access to this %post_type%.</p>\n<p><strong>Price</strong>: %price%</p>"
					),
					'defaults'    => array(
						'price'                 => 10,
						'overwrite_price'       => 0,
						'button_label'          => __( 'Buy Now', 'mycred' ),
						'overwrite_buttonlabel' => 0,
						'expire'                => 0
					),
					'logs'        => array(
						'buy'  => __( 'Purchase of %link_with_title%', 'mycred' ),
						'sell' => __( 'Sale of %link_with_title%', 'mycred' )
					)
				),
				'add_to_core' => true
			) );

			// Adjust Module to the selected point type
			$this->mycred_type = 'mycred_default';
			if ( isset( $this->sell_content['type'] ) )
				$this->mycred_type = $this->sell_content['type'];
			
			$this->core = mycred( $this->mycred_type );

			add_filter( 'mycred_email_before_send', array( $this, 'email_notices' ), 10, 2 );
		}

		/**
		 * Load
		 * @since 0.1
		 * @version 1.1
		 */
		public function module_init() {
			$this->make_purchase();
			$this->exp_title = apply_filters( 'mycred_sell_exp_title', __( 'Hours', 'mycred' ) );

			add_filter( 'the_content',                array( $this, 'the_content' ), 30       );
			
			add_shortcode( 'mycred_sell_this',        array( $this, 'render_shortcode' )      );
			add_shortcode( 'mycred_sell_this_ajax',   array( $this, 'render_ajax_shortcode' ) );
			add_shortcode( 'mycred_sales_history', 	  array( $this, 'render_sales_history' )  );

			add_action( 'add_meta_boxes',             array( $this, 'add_metabox' )           );
			add_action( 'save_post',                  array( $this, 'save_metabox' )          );

			add_action( 'mycred_admin_enqueue',       array( $this, 'admin_enqueue' )         );
			add_action( 'mycred_front_enqueue',       array( $this, 'front_enqueue' )         );
			add_action( 'wp_footer',                  array( $this, 'footer' )                );
			add_action( 'wp_ajax_mycred-buy-content', array( $this, 'make_purchase_ajax' )    );
		}

		/**
		 * Make Purchase
		 * @since 0.1
		 * @version 1.1
		 */
		public function make_purchase() {
			global $mycred_content_purchase;

			$mycred_content_purchase = false;
			if ( ! $this->is_installed() ) return;
			if ( ! isset( $_POST['mycred_purchase_token'] ) || ! isset( $_POST['mycred_purchase'] ) || ! isset( $_POST['mycred_purchase']['action'] ) || ! isset( $_POST['mycred_purchase']['author'] ) || ! isset( $_POST['mycred_purchase']['post_id'] ) || ! isset( $_POST['mycred_purchase']['post_type'] ) || ! isset( $_POST['mycred_purchase']['user_id'] ) ) return;
			if ( ! wp_verify_nonce( $_POST['mycred_purchase_token'], 'buy-content' ) ) return;

			$action = $_POST['mycred_purchase']['action'];
			$post_id = $_POST['mycred_purchase']['post_id'];
			$post_type = $_POST['mycred_purchase']['post_type'];
			$user_id = $_POST['mycred_purchase']['user_id'];
			$author = $_POST['mycred_purchase']['author'];

			$sell_content = $this->sell_content;
			$prefs = $this->get_sale_prefs( $post_id );

			$request = compact( 'action', 'post_id', 'user_id', 'author', 'post_type', 'sell_content', 'prefs' );
			do_action( 'mycred_sell_content_purchase_request', $request );

			if ( is_user_logged_in() && ! $this->user_paid( $user_id, $post_id ) && $this->user_can_buy( $user_id, $prefs['price'] ) ) {
				// Charge
				$log = $sell_content['logs']['buy'];
				$data = array(
					'ref_type'    => 'post',
					'purchase_id' => 'TXID' . date_i18n( 'U' ),
					'seller'      => $author
				);
				$this->core->add_creds(
					'buy_content',
					$user_id,
					0-$prefs['price'],
					$log,
					$post_id,
					$data,
					$this->mycred_type
				);

				do_action( 'mycred_sell_content_purchase_ready', $request );

				// Pay
				if ( $sell_content['pay'] == 'author' ) {
					$content_price = $prefs['price'];
					// If we are paying the author less then 100%
					if ( (int) $sell_content['pay_percent'] != 100 ) {
						$percent = (int) $sell_content['pay_percent']/100;
						$price = $percent*$content_price;
						$content_price = number_format( $price, $this->core->format['decimals'], '.', '' );
					}
					$log = $sell_content['logs']['sell'];
					$data = array(
						'ref_type'    => 'post',
						'purchase_id' => 'TXID' . date_i18n( 'U' ),
						'buyer'       => $user_id
					);
					$this->core->add_creds(
						'buy_content',
						$author,
						$content_price,
						$log,
						$post_id,
						$data,
						$this->mycred_type
					);
				}

				$mycred_content_purchase = true;
				do_action( 'mycred_sell_content_payment_complete', $request );
			}
		}

		/**
		 * Make Purchase AJAX
		 * @since 1.1
		 * @version 1.1
		 */
		public function make_purchase_ajax() {
			// We must be logged in
			if ( ! is_user_logged_in() ) die();

			// Security
			check_ajax_referer( 'mycred-buy-content-ajax', 'token' );

			// Prep
			$post_id = $_POST['postid'];
			$user_id = get_current_user_id();
			$action = 'buy-content-ajax';

			$sell_content = $this->sell_content;
			$prefs = $this->get_sale_prefs( $post_id );

			if ( ! $this->user_paid( $user_id, $post_id ) && $this->user_can_buy( $user_id, $prefs['price'] ) ) {
				$post = get_post( $post_id );
				$author = $post->post_author;
				$post_type = $post->post_type;

				// Charge
				$log = $sell_content['logs']['buy'];
				$data = array(
					'ref_type'    => 'post',
					'purchase_id' => 'TXID' . date_i18n( 'U' ),
					'seller'      => $author
				);
				$this->core->add_creds(
					'buy_content',
					$user_id,
					0-$prefs['price'],
					$log,
					$post_id,
					$data,
					$this->mycred_type
				);

				$request = compact( 'action', 'post_id', 'user_id', 'author', 'post_type', 'sell_content', 'prefs' );
				do_action( 'mycred_sell_content_purchase_ready', $request );

				// Pay
				if ( $sell_content['pay'] == 'author' ) {
					$content_price = $prefs['price'];
					// If we are paying the author less then 100%
					if ( (int) $sell_content['pay_percent'] != 100 ) {
						$percent = (int) $sell_content['pay_percent']/100;
						$price = $percent*$content_price;
						$content_price = number_format( $price, $this->core->format['decimals'], '.', '' );
					}
					$log = $sell_content['logs']['sell'];
					$data = array(
						'ref_type'    => 'post',
						'purchase_id' => 'TXID' . date_i18n( 'U' ),
						'buyer'       => $user_id
					);
					$this->core->add_creds(
						'buy_content',
						$author,
						$content_price,
						$log,
						$post_id,
						$data,
						$this->mycred_type
					);
				}

				// $match[1] = start tag, $match[2] = settings, $match[3] = content, $match[4] = end tag
				preg_match( "'(\[mycred_sell_this_ajax(.{1,})\])(.*?)(\[\/mycred_sell_this_ajax\])'si", $post->post_content, $match );

				// Filter content before returning
				$content = apply_filters( 'the_content', $match[3] );
				$content = str_replace( ']]>', ']]&gt;', $content );
				$content = do_shortcode( $content );
			}
			// Someone is trying to make a purchase but not allowed
			else {
				$content = '<p>' . __( 'You can not buy this content.', 'mycred' ) . '</p>';
			}

			die( $content );
		}

		/**
		 * Enqueue Admin
		 * @since 0.1
		 * @version 1.0
		 */
		public function admin_enqueue() {
			wp_register_style(
				'mycred-buy-edit',
				plugins_url( 'css/edit.css', myCRED_SELL ),
				false,
				myCRED_SELL_VERSION . '.1',
				'all'
			);

			$screen = get_current_screen();
			$sell_content = $this->sell_content;
			$post_types = $sell_content['post_types'];
			if ( ! empty( $post_types ) ) {
				$pts = explode( ',', $post_types );
				if ( ! in_array( $screen->id , $pts ) ) return;

				wp_enqueue_style( 'mycred-buy-edit' );
			}
		}

		/**
		 * Enqueue Front
		 * @since 1.1
		 * @version 1.0
		 */
		public function front_enqueue() {
			global $mycred_buy_content;

			wp_register_script(
				'mycred-buy-content',
				plugins_url( 'js/buy-content.js', myCRED_SELL ),
				array( 'jquery' ),
				myCRED_SELL_VERSION . '.1',
				true
			);
		}

		/**
		 * Footer
		 * @since 1.1
		 * @version 1.0
		 */
		public function footer() {
			global $mycred_buy_content;

			if ( $mycred_buy_content === true ) {
				wp_localize_script(
					'mycred-buy-content',
					'myCREDsell',
					array(
						'ajaxurl' => admin_url( 'admin-ajax.php' ),
						'working' => __( 'Processing...', 'mycred' ),
						'error'   => __( 'Error. Try Again', 'mycred' ),
						'token'   => wp_create_nonce( 'mycred-buy-content-ajax' )
					)
				);
				wp_enqueue_script( 'mycred-buy-content' );
			}
		}

		/**
		 * Settings Page
		 * @since 0.1
		 * @version 1.2
		 */
		public function after_general_settings() {
			$sell_content = $this->sell_content;
			if ( ! isset( $sell_content['defaults']['expire'] ) )
				$sell_content['defaults']['expire'] = 0;

			$before = $this->core->before;
			$after = $this->core->after;

			$payees = array(
				'none'   => __( 'No Payout. Just charge.' ),
				'author' => __( 'Pay Content Author.' )
			);
			$available_payees = apply_filters( 'mycred_sell_content_payees', $payees, $sell_content );
			
			$mycred_types = mycred_get_types(); ?>

<h4><div class="icon icon-active"></div><?php _e( 'Sell Content', 'mycred' ); ?></h4>
<div class="body" style="display:none;">
	<label class="subheader" for="<?php echo $this->field_id( 'post_types' ); ?>"><?php _e( 'Post Types', 'mycred' ); ?></label>
	<ol id="myCRED-buy-postypes">
		<li>
			<div class="h2"><input type="text" name="<?php echo $this->field_name( 'post_types' ); ?>" id="<?php echo $this->field_id( 'post_types' ); ?>" value="<?php echo $sell_content['post_types']; ?>" class="long" /></div>
			<span class="description"><?php _e( 'Comma separated list of post types that can be sold.', 'mycred' ); ?></span>
		</li>
	</ol>
	<?php if ( count( $mycred_types ) > 1 ) : ?>
	<label class="subheader" for="<?php echo $this->field_id( 'type' ); ?>"><?php _e( 'Point Type', 'mycred' ); ?></label>
	<ol id="myCRED-buy-point-type">
		<li>
			<?php mycred_types_select_from_dropdown( $this->field_name( 'type' ), $this->field_name( 'type' ), $sell_content['type'] ); ?>

		</li>
	</ol>
	<?php else : ?>

	<input type="hidden" name="<?php echo $this->field_name( 'type' ); ?>" id="<?php echo $this->field_name( 'type' ); ?>" value="mycred_default" />
	<?php endif; ?>

	<label class="subheader"><?php _e( 'Payments', 'mycred' ); ?></label>
	<ol id="myCRED-buy-payments">
<?php
			if ( ! empty( $available_payees ) ) {
				foreach ( $available_payees as $key => $description ) { ?>

		<li>
			<input type="radio" name="<?php echo $this->field_name( 'pay' ); ?>" id="<?php echo $this->field_id( array( 'pay' => $key ) ); ?>" <?php checked( $sell_content['pay'], $key ); ?> value="<?php echo $key; ?>" />
			<label for="<?php echo $this->field_id( array( 'pay' => $key ) ); ?>"><?php echo $description; ?></label>
		</li>
<?php
					if ( $key == 'author' ) { ?>

		<li>
			<label for="<?php echo $this->field_id( 'pay_percent' ); ?>"><?php _e( 'Percentage to pay Author', 'mycred' ); ?></label>
			<div class="h2"><input type="text" size="5" maxlength="3" name="<?php echo $this->field_name( 'pay_percent' ); ?>" id="<?php echo $this->field_id( 'pay_percent' ); ?>" value="<?php echo $sell_content['pay_percent']; ?>" /> %</div>
			<span class="description"><?php _e( 'Percentage of the price to pay the author. Can not be zero and is ignored if authors are not paid.', 'mycred' ); ?></span>
		</li>
<?php
					}
				}
			} ?>

	</ol>
	<label class="subheader"><?php _e( 'Defaults', 'mycred' ); ?></label>
	<ol id="myCRED-buy-defaults">
		<li>
			<label for="<?php echo $this->field_id( array( 'defaults' => 'price' ) ); ?>"><?php _e( 'Price', 'mycred' ); ?></label>
			<div class="h2"><?php echo $before; ?> <input type="text" name="<?php echo $this->field_name( array( 'defaults' => 'price' ) ); ?>" id="<?php echo $this->field_id( array( 'defaults' => 'price' ) ); ?>" value="<?php echo $sell_content['defaults']['price']; ?>" size="8" /> <?php echo $after; ?></div>
		</li>
		<li>
			<input type="checkbox" name="<?php echo $this->field_name( array( 'defaults' => 'overwrite_price' ) ); ?>" id="<?php echo $this->field_id( array( 'defaults' => 'overwrite_price' ) ); ?>" <?php checked( $sell_content['defaults']['overwrite_price'], 1 ); ?> value="1" />
			<label for="<?php echo $this->field_id( array( 'defaults' => 'overwrite_price' ) ); ?>"><?php _e( 'Allow authors to change price.', 'mycred' ); ?></label>
		</li>
		<li class="empty">&nbsp;</li>
		<li>
			<label for="<?php echo $this->field_id( array( 'defaults' => 'button_label' ) ); ?>"><?php _e( 'Button Label', 'mycred' ); ?></label>
			<div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'defaults' => 'button_label' ) ); ?>" id="<?php echo $this->field_id( array( 'defaults' => 'button_label' ) ); ?>" value="<?php echo $sell_content['defaults']['button_label']; ?>" size="12" /></div>
		</li>
		<li>
			<input type="checkbox" name="<?php echo $this->field_name( array( 'defaults' => 'overwrite_buttonlabel' ) ); ?>" id="<?php echo $this->field_id( array( 'defaults' => 'overwrite_buttonlabel' ) ); ?>" <?php checked( $sell_content['defaults']['overwrite_buttonlabel'], 1 ); ?> value="1" />
			<label for="<?php echo $this->field_id( array( 'defaults' => 'overwrite_buttonlabel' ) ); ?>"><?php _e( 'Allow authors to change button label.', 'mycred' ); ?></label>
		</li>
		<li class="empty">&nbsp;</li>
		<li>
			<label for="<?php echo $this->field_id( array( 'defaults' => 'expire' ) ); ?>"><?php _e( 'Purchases expire after', 'mycred' ); ?></label>
			<div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'defaults' => 'expire' ) ); ?>" id="<?php echo $this->field_id( array( 'defaults' => 'expire' ) ); ?>" value="<?php echo $sell_content['defaults']['expire']; ?>" size="6" /> <?php echo $this->exp_title; ?></div>
			<span class="description"><?php _e( 'Use zero for permanent sales.', 'mycred' ); ?></span>
		</li>
	</ol>
	<label class="subheader" for="<?php echo $this->field_id( array( 'templates' => 'visitors' ) ); ?>"><?php _e( 'Sale Template for non members', 'mycred' ); ?></label>
	<ol id="myCRED-buy-template-visitors">
		<li>
			<textarea rows="10" cols="50" name="<?php echo $this->field_name( array( 'templates' => 'visitors' ) ); ?>" id="<?php echo $this->field_id( array( 'templates' => 'visitors' ) ); ?>" class="large-text code"><?php echo esc_attr( $sell_content['templates']['visitors'] ); ?></textarea>
			<span class="description"><?php _e( 'Do <strong>not</strong> use the %buy_button% in this template as a user must be logged in to buy content!', 'mycred' ); ?><br />
			<?php echo $this->core->available_template_tags( array( 'general', 'post' ), '%price%' ); ?></span>
		</li>
	</ol>
	<label class="subheader" for="<?php echo $this->field_id( array( 'templates' => 'members' ) ); ?>"><?php _e( 'Sale Template for members', 'mycred' ); ?></label>
	<ol id="myCRED-buy-template-members">
		<li>
			<textarea rows="10" cols="50" name="<?php echo $this->field_name( array( 'templates' => 'members' ) ); ?>" id="<?php echo $this->field_id( array( 'templates' => 'members' ) ); ?>" class="large-text code"><?php echo esc_attr( $sell_content['templates']['members'] ); ?></textarea>
			<span class="description"><?php _e( 'Your template must contain the %buy_button% tag for purchases to work!', 'mycred' ); ?><br />
			<?php echo $this->core->available_template_tags( array( 'general', 'post' ), '%buy_button%, %price%' ); ?></span>
		</li>
	</ol>
	<label class="subheader" for="<?php echo $this->field_id( array( 'templates' => 'cantafford' ) ); ?>"><?php _e( 'Insufficient funds template', 'mycred' ); ?></label>
	<ol id="myCRED-buy-template-insufficient">
		<li>
			<textarea rows="10" cols="50" name="<?php echo $this->field_name( array( 'templates' => 'cantafford' ) ); ?>" id="<?php echo $this->field_id( array( 'templates' => 'cantafford' ) ); ?>" class="large-text code"><?php echo esc_attr( $sell_content['templates']['cantafford'] ); ?></textarea>
			<span class="description"><?php _e( 'Your template must contain the %buy_button% tag for purchases to work!', 'mycred' ); ?><br />
			<?php echo $this->core->available_template_tags( array( 'general', 'post' ), '%buy_button%, %price%' ); ?></span>
		</li>
	</ol>
	<label class="subheader" for="<?php echo $this->field_id( array( 'logs' => 'buy' ) ); ?>"><?php _e( 'Log template for Purchases', 'mycred' ); ?></label>
	<ol id="myCRED-buy-template-purchase">
		<li>
			<div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'logs' => 'buy' ) ); ?>" id="<?php echo $this->field_id( array( 'logs' => 'buy' ) ); ?>" value="<?php echo $sell_content['logs']['buy']; ?>" class="long" /></div>
			<?php echo $this->core->available_template_tags( array( 'general', 'post' ) ); ?></span>
		</li>
	</ol>
	<label class="subheader" for="<?php echo $this->field_id( array( 'logs' => 'sell' ) ); ?>"><?php _e( 'Log template for Sales', 'mycred' ); ?></label>
	<ol id="myCRED-buy-template-sale">
		<li>
			<div class="h2"><input type="text" name="<?php echo $this->field_name( array( 'logs' => 'sell' ) ); ?>" id="<?php echo $this->field_id( array( 'logs' => 'sell' ) ); ?>" value="<?php echo $sell_content['logs']['sell']; ?>" class="long" /></div>
			<span class="description"><?php echo $this->core->available_template_tags( array( 'general', 'post' ) ); ?></span>
		</li>
	</ol>
</div>
<?php
		}

		/**
		 * Sanitize & Save Settings
		 * @since 0.1
		 * @version 1.2
		 */
		public function sanitize_extra_settings( $new_data, $data, $general ) {
			// Post Types
			$settings = $data['sell_content'];

			$new_data['sell_content']['post_types'] = sanitize_text_field( $settings['post_types'] );
			$new_data['sell_content']['type'] = sanitize_text_field( $settings['type'] );
			$new_data['sell_content']['pay'] = sanitize_text_field( $settings['pay'] );
			$new_data['sell_content']['pay_percent'] = abs( $settings['pay_percent'] );
			if ( $new_data['sell_content']['pay_percent'] == 0 || $new_data['sell_content']['pay_percent'] > 100 )
				$new_data['sell_content']['pay_percent'] = 100;

			$new_data['sell_content']['defaults']['price'] = $this->core->format_number( $settings['defaults']['price'] );
			$new_data['sell_content']['defaults']['overwrite_price'] = ( isset( $settings['defaults']['overwrite_price'] ) ) ? 1 : 0;
			$new_data['sell_content']['defaults']['button_label'] = sanitize_text_field( $settings['defaults']['button_label'] );
			$new_data['sell_content']['defaults']['overwrite_buttonlabel'] = ( isset( $settings['defaults']['overwrite_buttonlabel'] ) ) ? 1 : 0;
			$new_data['sell_content']['defaults']['expire'] = abs( $settings['defaults']['expire'] );

			$new_data['sell_content']['templates']['members'] = trim( $settings['templates']['members'] );
			$new_data['sell_content']['templates']['visitors'] = trim( $settings['templates']['visitors'] );
			$new_data['sell_content']['templates']['cantafford'] = trim( $settings['templates']['cantafford'] );

			$new_data['sell_content']['logs']['buy'] = sanitize_text_field( $settings['logs']['buy'] );
			$new_data['sell_content']['logs']['sell'] = sanitize_text_field( $settings['logs']['sell'] );

			unset( $settings );
			return $new_data;
		}

		/**
		 * Add Meta Box to Content
		 * @since 0.1
		 * @version 1.0
		 */
		public function add_metabox() {
			$sell_content = $this->sell_content;
			$post_types = explode( ',', $sell_content['post_types'] );
			$name = sprintf( __( '%s Sell This', 'mycred' ), mycred_label() );
			foreach ( (array) $post_types as $post_type ) {
				$post_type = trim( $post_type );
				add_meta_box(
					'mycred_sell_content',
					$name,
					array( $this, 'metabox' ),
					$post_type,
					'side',
					'high'
				);
			}
		}

		/**
		 * Sale Preference
		 * Returns a given posts sale preferences. If none exists a new one is buildt and returned.
		 * 
		 * @return (array) sales settings
		 * @since 0.1
		 * @version 1.0
		 */
		public function get_sale_prefs( $post_id ) {
			$sell_content = $this->sell_content;
			if ( ! isset( $sell_content['defaults']['expire'] ) )
				$sell_content['defaults']['expire'] = 0;

			$prefs = get_post_meta( $post_id, 'myCRED_sell_content', true );
			if ( empty( $prefs ) ) {
				$sales_data = array(
					'status'       => 'disabled',
					'price'        => $sell_content['defaults']['price'],
					'button_label' => $sell_content['defaults']['button_label'],
					'expire'       => $sell_content['defaults']['expire']
				);
			}
			else {
				if ( ! isset( $prefs['expire'] ) )
					$prefs['expire'] = $sell_content['defaults']['expire'];

				$sales_data = $prefs;
			}

			return $sales_data;
		}

		/**
		 * Sell Meta Box
		 * @since 0.1
		 * @version 1.0
		 */
		public function metabox( $post ) {
			// Make sure add-on has been setup
			if ( ! $this->is_installed() ) {
				echo sprintf( __( '%s Sell Content needs to be setup before you can use this feature.', 'mycred' ), mycred_label() );
				// Settings Link
				if ( $this->core->can_edit_plugin( get_current_user_id() ) )
					echo ' <a href="' . $this->get_settings_url( 'sell_content_module' ) . '" title="' . __( 'Setup add-on', 'mycred' ) . '">' . __( 'Lets do it', 'mycred' ) . '</a>';

				return;
			}
			$admin = false;
			$post_id = $post->ID;
			$post_type = $post->post_type;

			$user_id = get_current_user_id();
			$sell_content = $this->sell_content;
			$sales_data = $this->get_sale_prefs( $post_id );

			// Mark admins
			if ( $this->core->can_edit_plugin( $user_id ) )
				$admin = true;

			// Empty $sales_data means disabled same if the status is actually set to "disabled"
			if ( empty( $sales_data ) || ( isset( $sales_data['status'] ) && $sales_data['status'] == 'disabled' ) ) {
				$style = 'display:none;';
				$status = 'disabled';
			}
			else {
				$style = 'display:block;';
				$status = 'enabled';
			}

			$op = (bool) $sell_content['defaults']['overwrite_price'];
			$ob = (bool) $sell_content['defaults']['overwrite_buttonlabel']; ?>

<p><input type="checkbox" name="mycred_sell_this" id="mycred-sell-this"<?php checked( $status, 'enabled' ); ?> value="enabled" /><label for="mycred-sell-this"><?php echo __( 'Enable sale of this ', 'mycred' ) . $post_type . '.'; ?></label></p>
<div id="mycred-sale-settings " style="<?php echo $style; ?>">
	<input type="hidden" name="mycred-sell-this-token" value="<?php echo wp_create_nonce( 'mycred-sell-this' ); ?>" />
	<input type="hidden" name="mycred-sell-this-status" value="<?php echo $status; ?>" />
	<ul>
		<li>
			<label for="mycred-buy-prefs-"><?php _e( 'Price', 'mycred' ); ?></label>
			<div class="formated"><?php echo $this->core->before; ?> <input type="text" name="myCRED_sell_content[price]" id="mycred-buy-prefs-price" value="<?php echo $sales_data['price']; ?>" <?php if ( $op === false && !$admin ) echo 'disabled="disabled" class="disabled"'; ?> size="5" /> <?php echo $this->core->after; ?></div>
		</li>
		<li>
			<label for="mycred-buy-prefs-"><?php _e( 'Button Label', 'mycred' ); ?></label>
			<input type="text" name="myCRED_sell_content[button_label]" id="mycred-buy-prefs-" value="<?php echo $sales_data['button_label']; ?>" <?php if ( $ob === false && !$admin ) echo 'disabled="disabled" class="disabled"'; ?> />
		</li>
		<li>
			<label for="mycred-buy-prefs-"><?php _e( 'Purchase expires after', 'mycred' ); ?></label>
			<div class="formated"><input type="text" name="myCRED_sell_content[expire]" id="mycred-buy-prefs-expire" value="<?php echo $sales_data['expire']; ?>" <?php if ( $op === false && !$admin ) echo 'disabled="disabled" class="disabled"'; ?> size="5" /> <?php echo $this->exp_title; ?></div>
		</li>
	</ul>
</div>
<div class="clear"></div>
<script type="text/javascript">//<![CDATA[
jQuery(function($) {
	$('#mycred-sell-this').click(function(){
		$('#mycred-sale-settings').toggle();
	});
});//]]>
</script>
<?php
		}

		/**
		 * Save Sell Meta Box
		 * @since 0.1
		 * @version 1.0
		 */
		public function save_metabox( $post_id ) {
			// Make sure sale is enabled
			if ( ! isset( $_POST['mycred-sell-this-status'] ) || ! isset( $_POST['mycred-sell-this-token'] ) ) return $post_id;

			// Verify token
			if ( wp_verify_nonce( $_POST['mycred-sell-this-token'], 'mycred-sell-this' ) === false ) return $post_id;

			// Status
			if ( ! isset( $_POST['mycred_sell_this'] ) )
				$status = 'disabled';
			else
				$status = 'enabled';

			$prefs = get_post_meta( $post_id, 'myCRED_sell_content', true );
			// If sale has never been set and is not enabled bail
			if ( empty( $prefs ) && $status == 'disabled' ) return $post_id;

			$sell_content = $this->sell_content;
			$is_admin = $this->core->can_edit_plugin();

			// Status
			$prefs['status'] = $status;

			// Prefs
			$op = (bool) $sell_content['defaults']['overwrite_price'];
			$prefs['price'] = ( $op === true || $is_admin === true ) ? $_POST['myCRED_sell_content']['price'] : $sell_content['defaults']['price'];

			$ob = (bool) $sell_content['defaults']['overwrite_buttonlabel'];
			$prefs['button_label'] = ( $ob === true || $is_admin === true ) ? $_POST['myCRED_sell_content']['button_label'] : $sell_content['defaults']['button_label'];

			// Expiration
			$prefs['expire'] = ( $is_admin === true ) ? abs( $_POST['myCRED_sell_content']['expire'] ) : $sell_content['defaults']['expire'];

			update_post_meta( $post_id, 'myCRED_sell_content', $prefs );
		}

		/**
		 * Get the Post ID
		 * Added support for sale of bbPress items.
		 * @since 1.3.3.2
		 * @version 1.0
		 */
		public function get_post_ID() {
			$post_id = $bbp_topic_id = $bbp_reply_id = 0;
			if ( function_exists( 'bbpress' ) ) {
				global $wp_query;

				$bbp = bbpress();

				// Currently inside a topic loop
				if ( ! empty( $bbp->topic_query->in_the_loop ) && isset( $bbp->topic_query->post->ID ) )
					$bbp_topic_id = $bbp->topic_query->post->ID;

				// Currently inside a search loop
				elseif ( ! empty( $bbp->search_query->in_the_loop ) && isset( $bbp->search_query->post->ID ) && bbp_is_topic( $bbp->search_query->post->ID ) )
					$bbp_topic_id = $bbp->search_query->post->ID;

				// Currently viewing/editing a topic, likely alone
				elseif ( ( bbp_is_single_topic() || bbp_is_topic_edit() ) && ! empty( $bbp->current_topic_id ) )
					$bbp_topic_id = $bbp->current_topic_id;

				// Currently viewing/editing a topic, likely in a loop
				elseif ( ( bbp_is_single_topic() || bbp_is_topic_edit() ) && isset( $wp_query->post->ID ) )
					$bbp_topic_id = $wp_query->post->ID;
				
				// So far, no topic found, check if we are in a reply
				if ( $bbp_topic_id == 0 ) {

					// Currently inside a replies loop
					if ( ! empty( $bbp->reply_query->in_the_loop ) && isset( $bbp->reply_query->post->ID ) )
						$bbp_reply_id = $bbp->reply_query->post->ID;

					// Currently inside a search loop
					elseif ( ! empty( $bbp->search_query->in_the_loop ) && isset( $bbp->search_query->post->ID ) && bbp_is_reply( $bbp->search_query->post->ID ) )
						$bbp_reply_id = $bbp->search_query->post->ID;

					// Currently viewing a forum
					elseif ( ( bbp_is_single_reply() || bbp_is_reply_edit() ) && ! empty( $bbp->current_reply_id ) )
						$bbp_reply_id = $bbp->current_reply_id;

					// Currently viewing a reply
					elseif ( ( bbp_is_single_reply() || bbp_is_reply_edit() ) && isset( $wp_query->post->ID ) )
						$bbp_reply_id = $wp_query->post->ID;
				
					if ( $bbp_reply_id != 0 )
						$post_id = $bbp_reply_id;

				}
				
				// Else we are in a topic
				else $post_id = $bbp_topic_id;

			}

			if ( $post_id == 0 && isset( $GLOBALS['post'] ) )
				$post_id = $GLOBALS['post']->ID;

			return apply_filters( 'mycred_sell_this_get_post_ID', $post_id, $this );
		}

		/**
		 * For Sale
		 * Checks if a given post is for sale.
		 * 
		 * @param $post_id (int) required post id
		 * @returns (bool) true or false
		 * @since 0.1
		 * @version 1.0
		 */
		public function for_sale( $post_id ) {
			$prefs = get_post_meta( $post_id, 'myCRED_sell_content', true );
			if ( ! empty( $prefs ) && isset( $prefs['status'] ) && $prefs['status'] == 'enabled' ) return true;

			return false;
		}

		/**
		 * User Paid
		 * Checks if a given user has paid for a specific post.
		 * Will return true if the user can edit this plugin or creds.
		 *
		 * @param $user_id (int) required user id
		 * @param $post_id (int) required post id
		 * @returns (bool) true or false
		 * @since 0.1
		 * @version 1.3
		 */
		public function user_paid( $user_id, $post_id ) {
			// Admins can view
			if ( $this->core->can_edit_plugin( $user_id ) || $this->core->can_edit_creds( $user_id ) ) return true;
			// Authors can view
			$the_post = get_post( $post_id );
			if ( ! isset( $the_post->post_author ) && $the_post->post_author == $user_id ) return true;

			global $wpdb;
			
			$sell_content = $this->sell_content;

			// Search for the latest purchase of this item.
			$sql = "
				SELECT * 
				FROM {$this->core->log_table} 
				WHERE user_id = %d 
					AND ref = %s 
					AND ref_id = %d 
					AND ctype = %s
				ORDER BY time DESC LIMIT 0,1;";
			$purchases = $wpdb->get_results( $wpdb->prepare( $sql, $user_id, 'buy_content', $post_id, $this->mycred_type ) );

			// We have found purchase records
			if ( ! empty( $purchases ) ) {
				// Since individual posts can override the default settings we need to check sales prefs
				$prefs = $this->get_sale_prefs( $post_id );
				if ( ! isset( $prefs['expire'] ) )
					$prefs['expire'] = ( isset( $sell_content['defaults']['expire'] ) ) ? $sell_content['defaults']['expire'] : 0;

				// If purchases never expire just return true here and now
				if ( $prefs['expire'] == 0 ) return true;

				// Check if purchase has expired
				if ( ! $this->purchase_has_expired( $purchases[0]->time, $prefs['expire'], $user_id, $post_id ) ) return true;
			}

			// All else there are no purchases
			return false;
		}

		/**
		 * Purchase Has Expired
		 * Makes a time comparison to check if a given timestamp is in the future (not expired) or
		 * in the past (expired).
		 *
		 * @param $timestamp (int) The UNIX timestamp to wich we apply the expiration check
		 * @param $length (int) Length of expiration time to check by default this is the number of hours.
		 * @filter 'mycred_sell_expire_calc'
		 * @returns (bool) true or false
		 * @since 1.1
		 * @version 1.0
		 */
		public function purchase_has_expired( $timestamp, $length = 0, $user_id, $post_id ) {
			if ( $length == 0 ) return false;

			$expiration = apply_filters( 'mycred_sell_expire_calc', abs( $length*3600 ), $length, $user_id, $post_id );
			$expiration = $expiration+$timestamp;

			if ( $expiration > date_i18n( 'U' ) ) return false;
			
			return true;
		}

		/**
		 * User Can Buy
		 * Checks if a given user can afford the given price.
		 *
		 * @param $user_id (int) required user id
		 * @param $price (int|float) required price to check
		 * @returns (bool) true or false
		 * @since 0.1
		 * @version 1.0
		 */
		public function user_can_buy( $user_id, $price ) {
			$balance = $this->core->get_users_cred( $user_id, $this->mycred_type );
			if ( $balance-$price < 0 ) return false;
			return true;
		}

		/**
		 * Get Button
		 * Replaces the %buy_button% template tag with the submit button along
		 * with the set button label. If no template tag is found one is inserted in the end of the given string.
		 *
		 * @param $text (string) text to check for template tag.
		 * @param $post (object) optional post object to allow post template tags.
		 * @returns (string) formated string.
		 * @since 0.1
		 * @version 1.1
		 */
		public function get_button( $text, $post ) {
			$sell_content = $this->sell_content;
			$prefs = $this->get_sale_prefs( $post->ID );

			// Button Label
			if ( isset( $prefs['button_label'] ) )
				$button_text = $prefs['button_label'];
			else
				$button_text = $sell_content['defaults']['button_label'];

			// Button element
			$button = '<input type="submit" name="mycred-buy-button" value="' . $this->core->template_tags_post( $button_text, $post ) . '" class="button large" />';

			// Make sure there is a button
			if ( ! preg_match( '/%buy_button%/', $text ) )
				$text .= ' %buy_button% ';

			$content = str_replace( '%buy_button%', $button, $text );

			return apply_filters( 'mycred_sell_this_button', $content, $post );
		}

		/**
		 * The Content Overwrite
		 * If the current post is set for sale we apply the appropirate template.
		 * Uses 3 different templates. a) Visitors Template b) Members Template and c) Cant Afford Template
		 *
		 * @returns (string) content
		 * @since 0.1
		 * @version 1.1
		 */
		public function the_content( $content ) {
			global $mycred_content_purchase;

			$post_id = $this->get_post_ID();
			$the_post = get_post( $post_id );

			// If content is for sale
			if ( $this->for_sale( $post_id ) ) {
				// Prep
				$user_id = get_current_user_id();
				$sell_content = $this->sell_content;
				$prefs = $this->get_sale_prefs( $post_id );

				// Visitors
				if ( ! is_user_logged_in() ) {
					$template = $sell_content['templates']['visitors'];
					
					$template = str_replace( '%price%', $this->core->format_creds( $prefs['price'] ), $template );
					$template = $this->core->template_tags_general( $template );
					$template = $this->core->template_tags_post( $template, $the_post );
					return '<div class="mycred-content-forsale">' . $template . '</div>';
				}

				// We are logged in, have not purchased this item and can make a purchase
				elseif ( is_user_logged_in() && ! $this->user_paid( $user_id, $post_id ) && $this->user_can_buy( $user_id, $prefs['price'] ) ) {
					$template = $sell_content['templates']['members'];

					$template = str_replace( '%price%', $this->core->format_creds( $prefs['price'] ), $template );
					$template = $this->core->template_tags_general( $template );
					$template = $this->core->template_tags_post( $template, $the_post );
					$template = $this->get_button( $template, $the_post );
					return '
<form action="" method="post">
	<input type="hidden" name="mycred_purchase[post_id]"   value="' . $post_id . '" />
	<input type="hidden" name="mycred_purchase[post_type]" value="' . $the_post->post_type . '" />
	<input type="hidden" name="mycred_purchase[user_id]"   value="' . get_current_user_id() . '" />
	<input type="hidden" name="mycred_purchase[author]"    value="' . $the_post->post_author . '" />
	<input type="hidden" name="mycred_purchase_token"      value="' . wp_create_nonce( 'buy-content' ) . '" />
	<input type="hidden" name="mycred_purchase[action]"    value="buy" />
	<div class="mycred-content-forsale">' . $template . '</div>
</form>';
				}
				// We are logged in, have not purchased this item and can not afford to buy this
				elseif ( is_user_logged_in() && ! $this->user_paid( $user_id, $post_id ) && ! $this->user_can_buy( $user_id, $prefs['price'] ) ) {
					$template = $sell_content['templates']['cantafford'];

					$template = str_replace( '%price%', $this->core->format_creds( $prefs['price'] ), $template );
					$template = $this->core->template_tags_general( $template );
					$template = $this->core->template_tags_post( $template, $the_post );
					return '<div class="mycred-content-forsale">' . $template . '</div>';
				}
			}

			// Mark purchases
			if ( $mycred_content_purchase === true ) {
				$thank_you = __( 'Thank you for your purchase!', 'mycred' );
				$wrapper = '<div id="mycred-thank-you"><p>' . $thank_you . '</p></div>';
				$content = $wrapper . $content;
			}

			return do_shortcode( $content );
		}

		/**
		 * Render Shortcode
		 * Just as protecting the entire content, the mycred_sell_this shortcode protects
		 * parts of the content.
		 *
		 * @returns (string) content
		 * @since 0.1
		 * @version 1.3
		 */
		public function render_shortcode( $atts, $content ) {
			// The Post
			$post_id = $this->get_post_ID();
			$the_post = get_post( $post_id );

			// The User
			$user_id = get_current_user_id();
			$sell_content = $this->sell_content;

			$prefs = shortcode_atts( array(
				'price'        => $sell_content['defaults']['price'],
				'button_label' => $sell_content['defaults']['button_label'],
				'expire'       => $sell_content['defaults']['expire']
			), $atts );
			$sales_prefs = $this->get_sale_prefs( $post_id );

			// If we are not using defaults save these settings.
			if ( ( $sales_prefs['price'] != $prefs['price'] ) || ( $sales_prefs['button_label'] != $prefs['button_label'] ) || ( $sales_prefs['expire'] != $prefs['expire'] ) ) {
				update_post_meta( $post_id, 'myCRED_sell_content', array(
					'price'        => $prefs['price'],
					'status'       => $sales_prefs['status'],
					'button_label' => $prefs['button_label'],
					'expire'       => $prefs['expire']
				) );
			}

			// Not logged in
			if ( ! is_user_logged_in() ) {
				$template = $sell_content['templates']['visitors'];

				$template = str_replace( '%price%', $this->core->format_creds( $prefs['price'] ), $template );
				$template = $this->core->template_tags_general( $template );
				$template = $this->core->template_tags_post( $template, $the_post );
				unset( $content );
				return '<div class="mycred-content-forsale">' . $template . '</div>';
			}

			// Can buy
			elseif ( is_user_logged_in() && ! $this->user_paid( $user_id, $post_id ) && $this->user_can_buy( $user_id, $prefs['price'] ) ) {
				$template = $sell_content['templates']['members'];

				$template = str_replace( '%price%', $this->core->format_creds( $prefs['price'] ), $template );
				$template = $this->core->template_tags_general( $template );
				$template = $this->core->template_tags_post( $template, $the_post );
				$template = $this->get_button( $template, $the_post );
				unset( $content );
				return '
<form action="" method="post">
	<input type="hidden" name="mycred_purchase[post_id]"   value="' . $post_id . '" />
	<input type="hidden" name="mycred_purchase[post_type]" value="' . $the_post->post_type . '" />
	<input type="hidden" name="mycred_purchase[user_id]"   value="' . get_current_user_id() . '" />
	<input type="hidden" name="mycred_purchase[author]"    value="' . $the_post->post_author . '" />
	<input type="hidden" name="mycred_purchase_token"      value="' . wp_create_nonce( 'buy-content' ) . '" />
	<input type="hidden" name="mycred_purchase[action]"    value="buy" />
	<div class="mycred-content-forsale">' . $template . '</div>
</form>';
			}

			// We are logged in, have not purchased this item and can not afford to buy this
			elseif ( is_user_logged_in() && ! $this->user_paid( $user_id, $post_id ) && ! $this->user_can_buy( $user_id, $prefs['price'] ) ) {
				$template = $sell_content['templates']['cantafford'];
					
				$template = str_replace( '%price%', $this->core->format_creds( $prefs['price'] ), $template );
				$template = $this->core->template_tags_general( $template );
				$template = $this->core->template_tags_post( $template, $the_post );
				unset( $content );
				return '<div class="mycred-content-forsale">' . $template . '</div>';
			}

			// Admin and Author Wrapper for highlight of content set for sale
			if ( mycred_is_admin() || $the_post->post_author == $user_id )
				$content = '<div class="mycred-mark-title">' . __( 'The following content is set for sale:', 'mycred' ) . '</div><div class="mycred-mark-content">' . $content . '</div>';

			return do_shortcode( $content );
		}
		
		/**
		 * Render Shortcode AJAX
		 * Just as protecting the entire content, the mycred_sell_this_ajax shortcode protects
		 * parts of the content and uses AJAX to make the purchase
		 *
		 * @returns (string) content
		 * @since 0.1
		 * @version 1.0.1
		 */
		public function render_ajax_shortcode( $atts, $content ) {
			global $mycred_buy_content;

			// The Post
			$post_id = $this->get_post_ID();
			$the_post = get_post( $post_id );

			$user_id = get_current_user_id();
			$sell_content = $this->sell_content;

			$prefs = shortcode_atts( array(
				'price'        => $sell_content['defaults']['price'],
				'button_label' => $sell_content['defaults']['button_label'],
				'expire'       => $sell_content['defaults']['expire']
			), $atts );
			$sales_prefs = $this->get_sale_prefs( $post_id );

			// If we are not using defaults save these settings.
			if ( ( $sales_prefs['price'] != $prefs['price'] ) || ( $sales_prefs['button_label'] != $prefs['button_label'] ) || ( $sales_prefs['expire'] != $prefs['expire'] ) ) {
				update_post_meta( $post_id, 'myCRED_sell_content', array(
					'price'        => $prefs['price'],
					'status'       => $sales_prefs['status'],
					'button_label' => $prefs['button_label'],
					'expire'       => $prefs['expire']
				) );
			}

			// Not logged in
			if ( ! is_user_logged_in() ) {
				$template = $sell_content['templates']['visitors'];

				$template = str_replace( '%price%', $this->core->format_creds( $prefs['price'] ), $template );
				$template = $this->core->template_tags_general( $template );
				$template = $this->core->template_tags_post( $template, $the_post );
				unset( $content );
				return '<div class="mycred-content-forsale">' . $template . '</div>';
			}

			// Can buy
			elseif ( is_user_logged_in() && ! $this->user_paid( $user_id, $post_id ) && $this->user_can_buy( $user_id, $prefs['price'] ) ) {
				$template = $sell_content['templates']['members'];

				$template = str_replace( '%price%', $this->core->format_creds( $prefs['price'] ), $template );
				$template = $this->core->template_tags_general( $template );
				$template = $this->core->template_tags_post( $template, $the_post );

				if ( isset( $prefs['button_label'] ) )
					$button_text = $prefs['button_label'];
				else
					$button_text = $sell_content['defaults']['button_label'];

				$button = '<input type="button" data-id="' . $the_post->ID . '" name="mycred-buy-button" value="' . $this->core->template_tags_post( $button_text, $the_post ) . '" class="mycred-sell-this-button button large" />';
				$template = str_replace( '%buy_button%', $button, $template );
				unset( $content );

				$mycred_buy_content = true;
				return '<div class="mycred-content-forsale">' . $template . '</div>';
			}

			// We are logged in, have not purchased this item and can not afford to buy this
			elseif ( is_user_logged_in() && ! $this->user_paid( $user_id, $post_id ) && ! $this->user_can_buy( $user_id, $prefs['price'] ) ) {
				$template = $sell_content['templates']['cantafford'];
					
				$template = str_replace( '%price%', $this->core->format_creds( $prefs['price'] ), $template );
				$template = $this->core->template_tags_general( $template );
				$template = $this->core->template_tags_post( $template, $the_post );
				unset( $content );
				return '<div class="mycred-content-forsale">' . $template . '</div>';
			}

			// Admin and Author Wrapper for highlight of content set for sale
			if ( mycred_is_admin() || $the_post->post_author == $user_id )
				$content = '<div class="mycred-mark-title">' . __( 'The following content is set for sale:', 'mycred' ) . '</div><div class="mycred-mark-content">' . $content . '</div>';

			return do_shortcode( $content );
		}
		
		/**
		 * Render Sales History Shortcode
		 * @see http://mycred.me/shortcodes/mycred_sales_history/
		 * @since 1.0.9
		 * @version 1.1
		 */
		public function render_sales_history( $atts ) {
			extract( shortcode_atts( array(
				'login'        => NULL,
				'title'        => '',
				'title_el'     => 'h1',
				'title_class'  => '',
				'include_date' => true,
				'no_result'    => __( 'No purchases found', 'mycred' )
			), $atts ) );
			
			// Not logged in
			if ( ! is_user_logged_in() ) {
				if ( $login != NULL )
					return '<div class="mycred-not-logged-in">' . $login . '</div>';

				return;
			}

			// Prep
			$output = '<div class="mycred-sales-history-wrapper">';
			$user_id = get_current_user_id();
	
			global $wpdb;
	
			// Title
			if ( ! empty( $title ) ) {
				if ( ! empty( $title_class ) )
					$title_class = ' class="' . $title_class . '"';
				$output .= '<' . $title_el . $title_class . '>' . $title . '</' . $title_el . '>';
			}
			
			// Query
			$sql = "
				SELECT * 
				FROM {$this->core->log_table} 
				WHERE user_id = %d 
					AND ref = %s 
					AND ctype = %s 
				ORDER BY time;";
			$results = $wpdb->get_results( $wpdb->prepare( $sql, $user_id, 'buy_content', $this->mycred_type ) );
			$rows = array();
			
			// Results
			if ( $wpdb->num_rows > 0 ) {
				foreach ( $results as $item ) {
					// Row
					$row = '<span class="item-link"><a href="' . get_permalink( $item->ref_id ) . '">' . get_the_title( $item->ref_id ) . '</a></span>';

					// Add Date to row
					if ( $include_date )
						$row .= '<span class="purchased">' . __( 'Purchased', 'mycred' ) . ' ' . date_i18n( get_option( 'date_format' ), $item->time ) . '</span>';

					// Construct row (and let others play)
					$rows[] = apply_filters( 'mycred_sale_history_row', $row, $item );
				}
			}
			
			// Implode rows if there are any
			if ( ! empty( $rows ) ) {
				$output .= '<ul class="mycred-purchase-history"><li>' . implode( '</li><li>', $rows ) . '</li></ul>';
			}
			// No results
			else {
				if ( ! empty( $no_result ) )
					$output .= '<p>' . $no_result . '</p>';
			}

			$output .= '</div>';

			return $output;
		}
		
		/**
		 * Support for Email Notices
		 * @since 1.1
		 * @version 1.0
		 */
		public function email_notices( $data ) {
			if ( $data['request']['ref'] == 'buy_content' ) {
				$message = $data['message'];
				$data['message'] = $this->core->template_tags_post( $message, $data['request']['ref_id'] );
			}
			return $data;
		}
	}
	$sell_content = new myCRED_Sell_Content_Module();
	$sell_content->load();
}
?>