<?php

class W2S_Event_Registration {

	/**
	 * Holds an instance of the object
	 *
	 * @var W2S_Registration_Admin
	 */
	protected static $instance = null;

	/**
	 * Returns the running object
	 *
	 * @return W2S_Registration_Admin
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->hooks();
		}

		return self::$instance;
	}

	protected static $guest_checkout_option_changed;

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_filter( 'body_class', [ $this, 'ticket_body_class' ] );
		add_filter( 'wocommerce_box_office_input_field_template_vars', [ $this, 'class_to_ticket_fields' ] );
		add_filter( 'wocommerce_box_office_option_field_template_vars', [ $this, 'class_to_ticket_fields' ] );
		add_filter( 'woocommerce_checkout_fields', [ $this, 'checkout_fields' ] );
		add_filter( 'product_type_options', [ $this, 'ticket_type_option' ], 20 );
		add_filter( 'woocommerce_product_tabs', [ $this, 'woo_remove_product_tabs' ], 98 );
		add_filter( 'woocommerce_add_cart_item_data', [ $this, 'force_individual_cart_items' ], 10, 2 );
		add_action( 'wfobp_product_status', function() {
			return 'any';
		});
		add_action( 'wp_print_scripts', function() {
			if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
				wp_dequeue_script( 'wc-password-strength-meter' );
			}
		});
		add_action( 'woocommerce_single_product_summary', [ $this, 'woocommerce_template_product_description' ], 20 );
	}

	public static function is_ticket( $id = null ) {
		if ( null === $id ) {
			$id = get_the_id();
		}
		$ticket      = false;
		$ticket_meta = get_post_meta( $id, '_ticket', true );
		if ( 'yes' === $ticket_meta ) {
			$ticket = true;
		}
		return $ticket;
	}

	public function ticket_body_class( $classes ) {
		global $post;
		$data = [
			'id'        => $post->ID,
			'is_ticket' => $this->is_ticket( $post->ID ),
		];
		if ( $this->is_ticket( $post->ID ) ) {
			$classes[] = 'woocommerce-ticket';
		}
		return $classes;
	}

	public function class_to_ticket_fields( $vars ) {
		$slug                 = sanitize_title( $vars['label'] );
		$vars['before_field'] = "<p class='form-row ${slug}'>";
		return $vars;
	}

	public function checkout_fields( $fields ) {
		unset( $fields['billing']['billing_company'] );
		unset( $fields['shipping']['billing_company'] );
		return $fields;
	}

	/**
	 * Add 'Ticket' option to products.
	 *
	 * @param  array  $options Default options
	 * @return array           Modified options
	 */
	public function ticket_type_option( $options = array() ) {
		$options['ticket'] = array(
			'id'            => '_ticket',
			'wrapper_class' => 'show_if_simple show_if_variable hide_if_deposit',
			'label'         => __( 'Ticket', 'woocommerce-box-office' ),
			'description'   => __( 'Each ticket purchased will have attendee details added to it.', 'woocommerce-box-office' ),
			'default'       => 'no',
		);

		return $options;
	}

	function woocommerce_template_product_description() {
		wc_get_template( 'single-product/tabs/description.php' );
	}

	function woo_remove_product_tabs( $tabs ) {
	  unset( $tabs['description'] );        // Remove the description tab
	  unset( $tabs['reviews'] );            // Remove the reviews tab
	  return $tabs;
	}

	public function force_individual_cart_items( $cart_item_data, $product_id ) {
	  $unique_cart_item_key = md5( microtime() . rand() );
	  $cart_item_data['unique_key'] = $unique_cart_item_key;

	  return $cart_item_data;
	}
}
