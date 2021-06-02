<?php

namespace ShoppingFeed\ShoppingFeedWCCustomFields\Admin;

// Exit on direct access
use ShoppingFeed\ShoppingFeedWCCustomFields\ShoppingFeedCustomFieldsHelper;

defined( 'ABSPATH' ) || exit;

class Options {

	const MENU_SLUG = 'shopping-feed-custom-fields';
	const SFA_OPTIONS = 'sfcf_options';
	/**
	 * Setting page
	 */
	const SFCF_SETTINGS_PAGE = 'sfcf-settings-page';
	/** @var array $sfcf_acf_options */
	private $sfcf_acf_options;


	public function __construct() {
		$this->register_settings_page();
	}

	/**
	 * Add admin menu
	 */
	private function register_settings_page() {
		/**
		 * Add admin menu
		 */
		add_action(
			'admin_menu',
			function () {
				add_options_page(
					__( 'ShoppingFeed Custom Fields', 'shopping-feed-custom-fields' ),
					__( 'ShoppingFeed Custom Fields', 'shopping-feed-custom-fields' ),
					'manage_options',
					self::MENU_SLUG,
					array( $this, 'load_setting_page' )
				);
			}
		);

		/*
		 * Register settings
		 */
		add_action(
			'admin_init',
			function () {
				register_setting(
					'sfcf_settings_page_fields',
					self::SFA_OPTIONS
				);
			}
		);

		//get acf settings options
		$this->sfcf_acf_options = ShoppingFeedCustomFieldsHelper::get_acf_options();
	}

	public function load_setting_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		//load assets
		$this->load_assets();

		//Products
		add_settings_section(
			'sfcf_settings',
			__( 'Champs ACF', 'shopping-feed-custom-fields' ),
			function () {
			},
			self::SFCF_SETTINGS_PAGE
		);

		$acf_product_fields = ShoppingFeedCustomFieldsHelper::get_acf_product_fields();

		//PRODUCT FIELDS
		add_settings_field(
			'acf',
			__( 'Champs personnalisés', 'shopping-feed-custom-fields' ),
			function () use ( $acf_product_fields ) {
				?>
                <select class="acf" multiple
                        name='<?php echo esc_html( sprintf( '%s[acf][]', self::SFA_OPTIONS ) ); ?>'>
					<?php
					foreach ( $acf_product_fields as $acf_product_field ) {
						?>
                        <option value="<?php echo wc_esc_json( wp_json_encode( $acf_product_field ) ); ?>"
							<?php selected( ShoppingFeedCustomFieldsHelper::acf_is_selected( $acf_product_field['key'], $this->sfcf_acf_options ), 1 ); ?>
                        >
							<?php echo esc_html( $acf_product_field['label'] ); ?></option>
						<?php
					}
					?>
                </select>
                <p class="description" id="tagline-description">
					<?php esc_attr_e( 'Custom fields to export to Shoppingfeed. Default : all', 'shopping-feed-custom-fields' ); ?>
                </p>
				<?php
			},
			self::SFCF_SETTINGS_PAGE,
			'sfcf_settings'
		);

		?>
        <div class="wrap">
			<?php settings_errors(); ?>

            <form method="post" action="options.php">
				<?php
				settings_fields( 'sfcf_settings_page_fields' );
				do_settings_sections( self::SFCF_SETTINGS_PAGE );
				submit_button( __( 'Save changes', 'shopping-feed-custom-fields' ), 'submit' );
				?>
            </form>
        </div>
		<?php
	}

	private function load_assets() {
		wp_enqueue_style(
			'sfcf_app',
			SFCF_PLUGIN_URL . 'assets/css/app.css',
			array(),
			time()
		);

		wp_enqueue_script(
			'sfcf_multi_js',
			SFCF_PLUGIN_URL . 'assets/js/multi.min.js',
			array( 'jquery' ),
			false,
			true
		);

		wp_enqueue_script( 'sfcf_multi_js_init', SFCF_PLUGIN_URL . 'assets/js/init.js', array( 'sfcf_multi_js' ), time() );
		wp_localize_script(
			'sfcf_multi_js_init',
			'sf_options',
			array(
				'selected'   => __( 'Selected ACF Fields', 'shopping-feed-custom-fields' ),
				'unselected' => __( 'Unselected ACF Fields', 'shopping-feed-custom-fields' ),
				'search'     => __( 'Search', 'shopping-feed-custom-fields' ),
			)
		);
	}
}
