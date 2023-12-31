<?php
/**
 * Vanilla - WooCommerce Email Theme
 * Themes allow enhanced customization and editing of WooCommerce store emails.
 */

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Instantiate plugin.
 */
global $cxec_vanilla_theme;
$cxec_vanilla_theme = ec_vanilla_theme();

/**
 *
 * Main Class.
 */
class WC_Email_Theme_Vanilla {
	
	private static $instance;
	
	// Id
	public $id = 'vanilla';
	
	// Name
	public $name = 'Vanilla';
	
	// Description
	public $description = '';
	
	// Folder name
	public $folder_name;
	
	/**
	* Get Instance creates a singleton class that's cached to stop duplicate instances
	*/
	public static function get_instance(){
		if ( !self::$instance ){
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}
	
	/**
	* Construct empty on purpose
	*/
	private function __construct(){}
	
	/**
	* Init behaves like, and replaces, construct
	*/
	public function init(){
		
		// Set Constants.
		$folder_name = basename( __DIR__ );
		
		// Register Email Theme.
		add_action( 'register_email_theme',	array( $this, 'register_email_theme' ) );
		
		// Register template functions.
		add_action( 'ec_before_get_email_template_' . $this->id, array( $this, 'register_template_functions' ) );
		
		// Remove all additional info.
		add_action( 'init', array( $this, 'remove_additional_info' ) );
		
		// Display dummy text to style while creating theme.
		// add_action( 'woocommerce_email_order_details', array( $this, 'dummy_text' ) );
		
		// add_filter( 'woocommerce_order_formatted_billing_address', array( $this, 'format_addie' ) );
		// add_filter( 'woocommerce_order_formatted_shipping_address', array( $this, 'format_addie' ) );
	}
	
	// Custom format the following address so that it uses less space.
	function format_addie( $args ){
		
		$new_last_name = array();
		if ( isset( $args['last_name'] ) && trim( $args['last_name'] ) ) $new_last_name[] = $args['last_name'];
		if ( isset( $args['company'] ) && trim( $args['company'] ) ) $new_last_name[] = $args['company'];
		$args['last_name'] = implode( ' - ', $new_last_name );
		unset( $args['company'] );
		
		// s( $args );
		
		return $args;
	}
	
	public function dummy_text() {
		?>
		<div class="top_heading">
			<!-- <p>Invoice for order July 28, 2016</p> -->
			<p>Then there is this and Another Invoice for order July 28, 2016</p>
		</div>

		<p>Thanks for your order on the order that we are going to be speakng about more as a kind of lormem ispum scenario. That we are going to be speakng about more as a kind of lormem ispum scenario <a href="http://localhost/plugins">Superstore</a>.</p>
		<p>To pay for this order please use the following link: <a href="http://localhost/plugins/checkout/" >Pay now</a></p>
		<p>Thanks for your order on <a href="http://localhost/plugins">Superstore</a>.</p>
		<p>To pay for this order please use the following link: <a href="http://localhost/plugins/checkout/" >Pay now</a></p>

		<p>Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won’t be shipped until the funds have cleared in our account.</p>

		<h2 class="wc-bacs-bank-details-heading">Our Bank Details</h2>
		<h3>Stockton Goods - Nedbank</h3>
		<ul class="wc-bacs-bank-details order_details bacs_details">
			<li class="account_number">Account Number: <strong>4514567891</strong> </li>
			<li class="sort_code">Branch Code: <strong>3213216</strong> </li>
		</ul>
		<?php
	}
	
	/**
	 * Register Email Theme
	 *
	 * @date	20-08-2014
	 * @since	1.0
	 */
	public function register_email_theme() {
		
		ec_register_email_theme(
			$this->id,
			array(
				'name'                         => $this->name,
				'description'                  => $this->description,
				'template_folder'              => WC_EMAIL_CONTROL_DIR . '/templates',
				'sections'                     => $this->get_sections(),
				'settings'                     => $this->get_settings(),
				'woocoomerce_required_version' => '3.0.8',
			)
		);
	}
	
	public function get_sections() {
		
		$sections = array(
			array(
				"name" => __( "Text", 'email-control' ),
				"id"   => "text_section",
				"desc" => "",
				"tip"  => "",
			),
			array(
				"name" => __( "Appearance", 'email-control' ),
				"id"   => "appearance_section",
				"desc" => "",
				"tip"  => "",
			),
			array(
				"name" => __( "Header", 'email-control' ),
				"id"   => "header_section",
				"desc" => "",
				"tip"  => "",
			),
			array(
				"name" => __( "Links", 'email-control' ),
				"id"   => "links_section",
				"desc" => "",
				"tip"  => "",
			),
			array(
				"name" => __( "Section Headings", 'email-control' ),
				"id"   => "section_headings_section",
				"desc" => "",
				"tip"  => "",
			),
			array(
				"name" => __( "Order Items Table", 'email-control' ),
				"id"   => "order_items_table_section",
				"desc" => "",
				"tip"  => "",
			),
			array(
				"name" => __( "Footer", 'email-control' ),
				"id"   => "footer_section",
				"desc" => "",
				"tip"  => "",
			),
			array(
				"name" => __( "Custom CSS", 'email-control' ),
				"id"   => "custom_css_section",
				"desc" => "",
				"tip"  => "",
			),
		);
		
		return $sections;
	}
	
	/**
	 * Get Settings
	 *
	 * @date	20-08-2014
	 * @since	1.0
	 */
	public function get_settings() {
		
		// Types
		// title, sectionend, text, email, number, color, password,
		// textarea, select, multiselect, radio, checkbox, image_width,
		// single_select_page, single_select_country, multi_select_countries
		
		$settings = array(
		
			
		
			// New Order (new_order, admin-new-order.php)
			array(
				"name"    => __( "Heading", 'email-control' ), // YES
				"id"      => "heading",
				"type"    => "textarea",
				"default" => __( "New order received!", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "new_order",
				"css"     => "height:47px;",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", 'email-control' ), // YES
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "You have received an order from [ec_firstname] [ec_lastname].\n\nTheir order is as follows: [ec_order]", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "new_order",
				"section" => "text_section",
			),
			
			
			
			
			// Cancelled Order (cancelled_order, admin-cancelled-order.php)
			array(
				"name"    => __( "Heading", 'email-control' ), // YES
				"id"      => "heading",
				"type"    => "textarea",
				"default" => __( "Cancelled order", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "cancelled_order",
				"css"     => "height:47px;",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", 'email-control' ), // YES
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "The order [ec_order] for [ec_firstname] [ec_lastname] has been cancelled.", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "cancelled_order",
				"section" => "text_section",
			),
			
			
			
			
			// Failed Order (failed_order, admin-failed-order.php)
			array(
				"name"    => __( "Heading", 'email-control' ), // YES
				"id"      => "heading",
				"type"    => "textarea",
				"default" => __( "Failed order", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "failed_order",
				"css"     => "height:47px;",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", 'email-control' ), // YES
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "Payment for order [ec_order] from [ec_firstname] [ec_lastname] has failed.", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "failed_order",
				"section" => "text_section",
			),
			
			
			
			
			// On-hold Order (customer_on_hold_order, customer-on-hold-order.php)
			array(
				"name"    => __( "Heading", 'email-control' ), // YES
				"id"      => "heading",
				"type"    => "textarea",
				"default" => __( "Thank you for your order", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_on_hold_order",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", 'email-control' ), // YES
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "Your order is on-hold until we confirm payment has been received. Your order details are shown below for your reference:", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_on_hold_order",
				"section" => "text_section",
			),
			
			
			
			
			// Processing Order (customer_processing_order, customer-processing-order.php)
			array(
				"name"    => __( "Heading", 'email-control' ), // YES
				"id"      => "heading",
				"type"    => "textarea",
				"default" => __( "Your order is being processed", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_processing_order",
				"css"     => "height:47px;",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", 'email-control' ), // YES
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "Your order [ec_order] has been received and is now being processed.", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_processing_order",
				"section" => "text_section",
			),
			
			
			
			
			// Completed Order (customer_completed_order, customer-completed-order.php)
			array(
				"name"    => __( "Heading", 'email-control' ), // YES
				"id"      => "heading",
				"type"    => "textarea",
				"default" => __( "Your order is complete", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_completed_order",
				"css"     => "height:47px;",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", 'email-control' ), // YES
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "Your order [ec_order] at [ec_site_name] has been completed.\n\nWe're just letting you know. No further action is required.", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_completed_order",
				"section" => "text_section",
			),
			
			
			
			
			// Refunded Order - full (customer_refunded_order, customer-refunded-order.php)
			array(
				"name"    => __( "Heading (full)", 'email-control' ), // YES
				"id"      => "heading_full",
				"type"    => "textarea",
				"default" => __( "Your order has been refunded", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_refunded_order",
				"css"     => "height:47px;",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", 'email-control' ), // YES
				"id"      => "main_text_full",
				"type"    => "textarea",
				"default" => __( "Your order [ec_order] has been refunded. Thanks", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_refunded_order",
				"section" => "text_section",
			),
			
			// Refunded Order - partial (customer_refunded_order, customer-refunded-order.php)
			array(
				"name"    => __( "Heading (partial)", 'email-control' ), // YES
				"id"      => "heading_partial",
				"type"    => "textarea",
				"default" => __( "You have been partially refunded", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_refunded_order",
				"css"     => "height:47px;",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", 'email-control' ), // YES
				"id"      => "main_text_partial",
				"type"    => "textarea",
				"default" => __( "Your order [ec_order] has been partially refunded. Thanks", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_refunded_order",
				"section" => "text_section",
			),
			
			
			
			
			// Customer Invoice - payment pending (customer_invoice, customer-invoice.php)
			array(
				"name"    => __( "Heading (payment pending)", 'email-control' ), // YES
				"id"      => "heading_pending",
				"type"    => "textarea",
				"default" => __( "Invoice for order #[ec_order show=\"number\"]", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_invoice",
				"css"     => "height:47px;",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", 'email-control' ), // YES
				"id"      => "main_text_pending",
				"type"    => "textarea",
				"default" => __( "Thanks for your order on [ec_site_link].\n\nTo pay for this order please use the following link: [ec_pay_link]", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_invoice",
				"section" => "text_section",
			),
			
			// Customer Invoice - payment complete (customer_invoice, customer-invoice.php)
			array(
				"name"    => __( "Heading (payment complete)", 'email-control' ), // YES
				"id"      => "heading_complete",
				"type"    => "textarea",
				"default" => __( "Invoice for order #[ec_order show=\"number\"]", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_invoice",
				"css"     => "height:47px;",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", 'email-control' ), // YES
				"id"      => "main_text_complete",
				"type"    => "textarea",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_invoice",
				"section" => "text_section",
			),
			
			
			
			
			// Customer Note (customer_note, customer-note.php)
			array(
				"name"    => __( "Heading", 'email-control' ), // YES
				"id"      => "heading",
				"type"    => "textarea",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_note",
				"css"     => "height:47px;",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", 'email-control' ), // YES
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "[ec_customer_note]", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_note",
				"section" => "text_section",
			),
			
			
			
			
			// Reset Password (customer_reset_password, customer-reset-password.php)
			array(
				"name"    => __( "Heading", 'email-control' ), // YES
				"id"      => "heading",
				"type"    => "textarea",
				"default" => __( "Password Reset", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_reset_password",
				"css"     => "height:47px;",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", 'email-control' ), // YES
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "Someone requested that the password be reset for the following account:\n[ec_user_login]\n\nIf this was a mistake, just ignore this email and nothing will happen.\n\nTo reset your password, visit the following address:\n[ec_reset_password_link]", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_reset_password",
				"section" => "text_section",
			),
			
			
			
			
			// New Account (customer_new_account, customer-new-account.php)
			array(
				"name"    => __( "Heading", 'email-control' ), // YES
				"id"      => "heading",
				"type"    => "textarea",
				"default" => __( "Your account has been created", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_new_account",
				"css"     => "height:47px;",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Main Text", 'email-control' ), // YES
				"id"      => "main_text",
				"type"    => "textarea",
				"default" => __( "Thanks for creating an account on [ec_site_name].\nYour username is: [ec_user_login].\n\nYou can access your account area to view your orders and change your password here: [ec_account_link]", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_new_account",
				"section" => "text_section",
			),
			array(
				"name"    => __( "Password Regenerated Text", 'email-control' ), // YES
				"id"      => "main_text_generate_pass",
				"type"    => "textarea",
				"default" => __( "Your password has been automatically generated: [ec_user_password]", 'email-control' ),
				"desc"    => "",
				"tip"     => "",
				"email-type" => "customer_new_account",
				"section" => "text_section",
			),
			
			
			
			
			// all
			
			array(
				"name"    => __( "Email Width", 'email-control' ), // YES
				"id"      => "email_width",
				"type"    => "number",
				"default" => "660",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-half",
				"section" => "appearance_section",
			),
			
			array(
				"name"    => __( "Background Color", 'email-control' ), // YES
				"id"      => "background_color",
				"type"    => "color",
				"default" => "#f7f7f7",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-half",
				"section" => "appearance_section",
			),
			
			array(
				"name"       => __( "Text Color", 'email-control' ), // YES
				"id"         => "text_color",
				"type"       => "color",
				"default"    => "#858585",
				"default"    => "#7d7d7d",
				"default"    => "#757575",
				// "default" => "#6d6d6d",
				"desc"       => "",
				"tip"        => "",
				"email-type"    => "all",
				"class"      => "ec-half",
				"section"    => "appearance_section",
			),
			
			array(
				"name"    => __( "Heading Color", 'email-control' ), // YES
				"id"      => "heading_color",
				"type"    => "color",
				"default" => "#555555",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-half",
				"section" => "appearance_section",
			),
			
			array(
				"name"    => __( "Text Accent Color", 'email-control' ), // YES
				"id"      => "text_accent_color",
				"type"    => "color",
				"default" => "#fd535c",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-half",
				"section" => "appearance_section",
			),
			
			array(
				"name"    => __( "Order Items Text Color", 'email-control' ), // YES
				"id"      => "order_items_table_text_color",
				"type"    => "color",
				"default" => "#757575",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-half",
				"section" => "appearance_section",
			),
			
			array(
				"name"    => __( "Main Heading size", 'email-control' ), // YES
				"id"      => "heading_1_size",
				"type"    => "number",
				"default" => "34",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-half",
				"section" => "appearance_section",
			),
			
			$settings[] =array(
				"name"    => __( "Product Images", 'email-control' ),
				"id"      => "product_thumbnail",
				"type"    => 'checkbox',
				"default" => 'yes',
				"desc"    => '',
				"tip"     => '',
				"email-type" => "all",
				"class"   => "ec-half",
				"section" => "appearance_section",
			),
			
			
			
			
			array(
				"name"    => __( "Logo", 'email-control' ), // YES
				"id"      => "header_logo",
				"type"    => "image_upload",
				"default" => "",
				"desc"    => __( "Enter a URL or upload an image", 'email-control' ),
				"tip"     => "",
				"email-type" => "all",
				"section" => "header_section",
			),
			
			
			
			
			
			array(
				"name"    => __( "Link 1 Text", 'email-control' ), // YES
				"id"      => "link_1_text",
				"type"    => "text",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			array(
				"name"    => __( "Link 1 URL", 'email-control' ), // YES
				"id"      => "link_1_url",
				"type"    => "text",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			array(
				"name"    => __( "Link 1 Image", 'email-control' ), // YES
				"id"      => "link_1_image",
				"type"    => "image_upload",
				"default" => "",
				"desc"    => __( "Enter a URL or upload an image", 'email-control' ),
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			
			
			array(
				"name"    => __( "Link 2 Text", 'email-control' ),  // YES
				"id"      => "link_2_text",
				"type"    => "text",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			array(
				"name"    => __( "Link 2 URL", 'email-control' ),  // YES
				"id"      => "link_2_url",
				"type"    => "text",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			array(
				"name"    => __( "Link 2 Image", 'email-control' ),  // YES
				"id"      => "link_2_image",
				"type"    => "image_upload",
				"default" => "",
				"desc"    => __( "Enter a URL or upload an image", 'email-control' ),
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			
			
			array(
				"name"    => __( "Link 3 Text", 'email-control' ),  // YES
				"id"      => "link_3_text",
				"type"    => "text",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			array(
				"name"    => __( "Link 3 URL", 'email-control' ),  // YES
				"id"      => "link_3_url",
				"type"    => "text",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			array(
				"name"    => __( "Link 3 Image", 'email-control' ),  // YES
				"id"      => "link_3_image",
				"type"    => "image_upload",
				"default" => "",
				"desc"    => __( "Enter a URL or upload an image", 'email-control' ),
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			
			
			array(
				"name"    => __( "Link 4 Text", 'email-control' ),  // YES
				"id"      => "link_4_text",
				"type"    => "text",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			array(
				"name"    => __( "Link 4 URL", 'email-control' ),  // YES
				"id"      => "link_4_url",
				"type"    => "text",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			array(
				"name"    => __( "Link 4 Image", 'email-control' ),  // YES
				"id"      => "link_4_image",
				"type"    => "image_upload",
				"default" => "",
				"desc"    => __( "Enter a URL or upload an image", 'email-control' ),
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			
			
			array(
				"name"    => __( "Link 5 Text", 'email-control' ),  // YES
				"id"      => "link_5_text",
				"type"    => "text",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			array(
				"name"    => __( "Link 5 URL", 'email-control' ),  // YES
				"id"      => "link_5_url",
				"type"    => "text",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			array(
				"name"    => __( "Link 5 Image", 'email-control' ),  // YES
				"id"      => "link_5_image",
				"type"    => "image_upload",
				"default" => "",
				"desc"    => __( "Enter a URL or upload an image", 'email-control' ),
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			
			
			array(
				"name"    => __( "Link 6 Text", 'email-control' ),  // YES
				"id"      => "link_6_text",
				"type"    => "text",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			array(
				"name"    => __( "Link 6 URL", 'email-control' ),  // YES
				"id"      => "link_6_url",
				"type"    => "text",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			array(
				"name"    => __( "Link 6 Image", 'email-control' ),  // YES
				"id"      => "link_6_image",
				"type"    => "image_upload",
				"default" => "",
				"desc"    => __( "Enter a URL or upload an image", 'email-control' ),
				"tip"     => "",
				"email-type" => "all",
				"class"   => "ec-condensed",
				"section" => "links_section",
			),
			
			
			
			
			array(
				"name"    => __( "Footer Image", 'email-control' ), // YES
				"id"      => "footer_image",
				"type"    => "image_upload",
				"default" => "",
				"desc"    => __( "Enter a URL or upload an image", 'email-control' ),
				"tip"     => "",
				"email-type" => "all",
				"section" => "footer_section",
			),
			
			array(
				"name"    => __( "Footer Text", 'email-control' ), // YES
				"id"      => "footer_text",
				"type"    => "textarea",
				"default" => "",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"css"     => "height:47px;",
				"section" => "footer_section",
			),
			
			
			
			array(
				"name"    => "",
				"id"      => "custom_css",
				"type"    => "textarea",
				"default" => ".example-class { color: #d11d38; }",
				"desc"    => "",
				"tip"     => "",
				"email-type" => "all",
				"css"     => "height:200px;",
				"section" => "custom_css_section",
			),
		
		
		);
		
		return $settings;
	}
	
	/**
	 * Register specific template functions.
	 */
	public function register_template_functions() {
		
		/**
		 * EC Nav Bar
		 */
		if ( ! function_exists( 'ec_nav_bar' ) ) :
			function ec_nav_bar () {
				
				$return = false;
				
				$link_text_1	= get_option( 'ec_vanilla_all_link_1_text' );
				$link_image_1	= get_option( 'ec_vanilla_all_link_1_image' );
				$link_url_1		= get_option( 'ec_vanilla_all_link_1_url' );
				
				$link_text_2	= get_option( 'ec_vanilla_all_link_2_text' );
				$link_image_2	= get_option( 'ec_vanilla_all_link_2_image' );
				$link_url_2		= get_option( 'ec_vanilla_all_link_2_url' );
				
				$link_text_3	= get_option( 'ec_vanilla_all_link_3_text' );
				$link_image_3	= get_option( 'ec_vanilla_all_link_3_image' );
				$link_url_3		= get_option( 'ec_vanilla_all_link_3_url' );
				
				$link_text_4	= get_option( 'ec_vanilla_all_link_4_text' );
				$link_image_4	= get_option( 'ec_vanilla_all_link_4_image' );
				$link_url_4		= get_option( 'ec_vanilla_all_link_4_url' );
				
				$link_text_5	= get_option( 'ec_vanilla_all_link_5_text' );
				$link_image_5	= get_option( 'ec_vanilla_all_link_5_image' );
				$link_url_5		= get_option( 'ec_vanilla_all_link_5_url' );
				
				$link_text_6	= get_option( 'ec_vanilla_all_link_6_text' );
				$link_image_6	= get_option( 'ec_vanilla_all_link_6_image' );
				$link_url_6		= get_option( 'ec_vanilla_all_link_6_url' );
				
				if 	( $link_text_1 || $link_image_1 || $link_text_2 || $link_image_2 || $link_text_3 || $link_image_3 || $link_text_4 || $link_image_4 || $link_text_5 || $link_image_5 || $link_text_6 || $link_image_6 ) {
				
					ob_start();
					?>
					<table border="0" cellpadding="0" cellspacing="0" width="auto" class="top_nav">
						<tr>
							<td class="nav-spacer-block">&nbsp;
								
							</td>
							
							<?php
							if ( $link_text_1 || $link_image_1 ) {
								?>
								<?php if ( $link_image_1 ) { ?>
									<td class="nav-image-block">
										<?php if ( $link_url_1 ) { ?><a href="<?php echo esc_url_raw( $link_url_1 ); ?>"><?php } ?>
											<img src="<?php echo get_option( 'ec_vanilla_all_link_1_image' ); ?>" />
										<?php if ( $link_url_1 ) { ?></a><?php } ?>
									</td>
								<?php } ?>
								<?php if ( $link_text_1 ) { ?>
									<td class="nav-text-block <?php if ( $link_image_1 ) { ?>nav-text-block-with-image<?php } ?>">
										<?php if ( $link_url_1 ) { ?><a href="<?php echo esc_url_raw( $link_url_1 ); ?>"><?php } ?>
											<?php echo get_option( 'ec_vanilla_all_link_1_text' ); ?>
										<?php if ( $link_url_1 ) { ?></a><?php } ?>
									</td>
								<?php } ?>
								<?php
							}
							?>
							
							<?php
							if ( $link_text_2 || $link_image_2 ) {
								?>
								<?php if ( $link_image_2 ) { ?>
									<td class="nav-image-block">
										<?php if ( $link_url_2 ) { ?><a href="<?php echo esc_url_raw( $link_url_2 ); ?>"><?php } ?>
											<img src="<?php echo get_option( 'ec_vanilla_all_link_2_image' ); ?>" />
										<?php if ( $link_url_2 ) { ?></a><?php } ?>
									</td>
								<?php } ?>
								<?php if ( $link_text_2 ) { ?>
									<td class="nav-text-block <?php if ( $link_image_2 ) { ?>nav-text-block-with-image<?php } ?>">
										<?php if ( $link_url_2 ) { ?><a href="<?php echo esc_url_raw( $link_url_2 ); ?>"><?php } ?>
											<?php echo get_option( 'ec_vanilla_all_link_2_text' ); ?>
										<?php if ( $link_url_2 ) { ?></a><?php } ?>
									</td>
								<?php } ?>
								<?php
							}
							?>
							
							<?php
							if ( $link_text_3 || $link_image_3 ) {
								?>
								<?php if ( $link_image_3 ) { ?>
									<td class="nav-image-block">
										<?php if ( $link_url_3 ) { ?><a href="<?php echo esc_url_raw( $link_url_3 ); ?>"><?php } ?>
											<img src="<?php echo get_option( 'ec_vanilla_all_link_3_image' ); ?>" />
										<?php if ( $link_url_3 ) { ?></a><?php } ?>
									</td>
								<?php } ?>
								<?php if ( $link_text_3 ) { ?>
									<td class="nav-text-block <?php if ( $link_image_3 ) { ?>nav-text-block-with-image<?php } ?>">
										<?php if ( $link_url_3 ) { ?><a href="<?php echo esc_url_raw( $link_url_3 ); ?>"><?php } ?>
											<?php echo get_option( 'ec_vanilla_all_link_3_text' ); ?>
										<?php if ( $link_url_3 ) { ?></a><?php } ?>
									</td>
								<?php } ?>
								<?php
							}
							?>
							
							<?php
							if ( $link_text_4 || $link_image_4 ) {
								?>
								<?php if ( $link_image_4 ) { ?>
									<td class="nav-image-block">
										<?php if ( $link_url_4 ) { ?><a href="<?php echo esc_url_raw( $link_url_4 ); ?>"><?php } ?>
											<img src="<?php echo get_option( 'ec_vanilla_all_link_4_image' ); ?>" />
										<?php if ( $link_url_4 ) { ?></a><?php } ?>
									</td>
								<?php } ?>
								<?php if ( $link_text_4 ) { ?>
									<td class="nav-text-block <?php if ( $link_image_4 ) { ?>nav-text-block-with-image<?php } ?>">
										<?php if ( $link_url_4 ) { ?><a href="<?php echo esc_url_raw( $link_url_4 ); ?>"><?php } ?>
											<?php echo get_option( 'ec_vanilla_all_link_4_text' ); ?>
										<?php if ( $link_url_4 ) { ?></a><?php } ?>
									</td>
								<?php } ?>
								<?php
							}
							?>
							
							<?php
							if ( $link_text_5 || $link_image_5 ) {
								?>
								<?php if ( $link_image_5 ) { ?>
									<td class="nav-image-block">
										<?php if ( $link_url_5 ) { ?><a href="<?php echo esc_url_raw( $link_url_5 ); ?>"><?php } ?>
											<img src="<?php echo get_option( 'ec_vanilla_all_link_5_image' ); ?>" />
										<?php if ( $link_url_5 ) { ?></a><?php } ?>
									</td>
								<?php } ?>
								<?php if ( $link_text_5 ) { ?>
									<td class="nav-text-block <?php if ( $link_image_5 ) { ?>nav-text-block-with-image<?php } ?>">
										<?php if ( $link_url_5 ) { ?><a href="<?php echo esc_url_raw( $link_url_5 ); ?>"><?php } ?>
											<?php echo get_option( 'ec_vanilla_all_link_5_text' ); ?>
										<?php if ( $link_url_5 ) { ?></a><?php } ?>
									</td>
								<?php } ?>
								<?php
							}
							?>
							
							<?php
							if ( $link_text_6 || $link_image_6 ) {
								?>
								<?php if ( $link_image_6 ) { ?>
									<td class="nav-image-block">
										<?php if ( $link_url_6 ) { ?><a href="<?php echo esc_url_raw( $link_url_6 ); ?>"><?php } ?>
											<img src="<?php echo get_option( 'ec_vanilla_all_link_6_image' ); ?>" />
										<?php if ( $link_url_6 ) { ?></a><?php } ?>
									</td>
								<?php } ?>
								<?php if ( $link_text_6 ) { ?>
									<td class="nav-text-block <?php if ( $link_image_6 ) { ?>nav-text-block-with-image<?php } ?>" >
										<?php if ( $link_url_6 ) { ?><a href="<?php echo esc_url_raw( $link_url_6 ); ?>"><?php } ?>
											<?php echo get_option( 'ec_vanilla_all_link_6_text' ); ?>
										<?php if ( $link_url_6 ) { ?></a><?php } ?>
									</td>
								<?php } ?>
								<?php
							}
							?>
							
							<td class="nav-spacer-block">&nbsp;
								
							</td>
						</tr>
					</table>
					<?php
					$return = ob_get_clean();
				
				}
				
				return $return;
			}
		endif;
	}
	
	/**
	 * Remove all additional info.
	 */
	public function remove_additional_info(){
		
		// Bail if not atvie.
		if ( ! isset( $this->id ) || $this->id !== ec_get_selected_theme() ) return;
		
		// Get WC Mailers.
		$mailer = WC()->mailer();
		$mails = $mailer->get_emails();
		
		// Remove all additional info.
		foreach ( $mails as $mail ){
			add_filter( "woocommerce_email_additional_content_{$mail->id}", '__return_empty_string' );
		}
	}
}

/**
 * Main instance of tagr.
 */
function ec_vanilla_theme() {
	return WC_Email_Theme_Vanilla::get_instance();
}
