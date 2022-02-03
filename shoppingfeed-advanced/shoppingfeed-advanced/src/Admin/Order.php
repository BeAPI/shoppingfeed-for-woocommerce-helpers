<?php

namespace ShoppingFeed\ShoppingFeedWCAdvanced\Admin;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

class Order {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'register_sfa_tracking_details_metabox' ), 100 );
		add_action( 'save_post', array( $this, 'save_sfa_tracking_details_metabox' ) );
	}

	public function save_sfa_tracking_details_metabox( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! isset( $_POST['sfa_tracking_nounce'] ) || ! wp_verify_nonce( $_POST['sfa_tracking_nounce'], '_sfa_tracking_nounce' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST[ TRACKING_NUMBER_FIELD_SLUG ] ) ) {
			update_post_meta( $post_id, TRACKING_NUMBER_FIELD_SLUG, esc_attr( $_POST[ TRACKING_NUMBER_FIELD_SLUG ] ) );
		}
		if ( isset( $_POST[ TRACKING_LINK_FIELD_SLUG ] ) ) {
			update_post_meta( $post_id, TRACKING_LINK_FIELD_SLUG, esc_attr( $_POST[ TRACKING_LINK_FIELD_SLUG ] ) );
		}
	}

	public function register_sfa_tracking_details_metabox() {

		global $post;
		$screen = get_current_screen();
		if ( is_null( $screen ) || 'shop_order' !== $screen->post_type ) {
			return;
		}

		$order = wc_get_order( $post );
		if ( false === $order ) {
			return;
		}

		if ( ! \ShoppingFeed\ShoppingFeedWC\Orders\Order::is_sf_order( $order ) ) {
			return;
		}

		add_meta_box(
			'sfa-carrier_fields',
			__( 'ShoppingFeed Carrier Details', 'shopping-feed-advanced' ),
			array( $this, 'render' ),
			'shop_order',
			'side'
		);
	}

	public function render( $order ) {
		wp_nonce_field( '_sfa_tracking_nounce', 'sfa_tracking_nounce' );
		$order = wc_get_order( $order );
		if ( false === $order ) {
			return;
		}
		?>

        <p>
            <label for="sfa_tracking_number">
				<?php esc_html_e( 'Tracking Number', 'shopping-feed-advanced' ); ?>
            </label>
            <br>
            <input type="text" name="<?php echo TRACKING_NUMBER_FIELD_SLUG; ?>"
                   id="<?php echo TRACKING_NUMBER_FIELD_SLUG; ?>"
                   value="<?php echo $order->get_meta( TRACKING_NUMBER_FIELD_SLUG ); ?>">
        </p>
        <p>
            <label for="sfa_tracking_link">
				<?php esc_html_e( 'Tracking Link', 'shopping-feed-advanced' ); ?>
            </label>
            <br>
            <input type="text" name="<?php echo TRACKING_LINK_FIELD_SLUG; ?>"
                   id="<?php echo TRACKING_LINK_FIELD_SLUG; ?>"
                   value="<?php echo $order->get_meta( TRACKING_LINK_FIELD_SLUG ); ?>">
        </p>
		<?php
		echo submit_button();
	}
}