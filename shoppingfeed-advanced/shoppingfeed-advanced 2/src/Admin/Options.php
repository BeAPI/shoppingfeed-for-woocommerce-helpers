<?php

namespace ShoppingFeed\ShoppingFeedWCAdvanced\Admin;

use ShoppingFeed\ShoppingFeedWCAdvanced\ShoppingFeedAdvancedHelper;

// Exit on direct access
defined( 'ABSPATH' ) || exit;

class Options {

	const MENU_SLUG = 'shopping-feed-advanced';
	const SFA_OPTIONS = 'sfa_custom_fields_options';

	public function __construct() {
		$this->register_settings_page();
	}

	/**
	 * Add admin menu
	 */
	private function register_settings_page() {
		add_action(
			'admin_init',
			function () {
				register_setting(
					'sfa_custom_fields',
					self::SFA_OPTIONS
				);
			}
		);

		add_action(
			'admin_menu',
			function () {
				add_options_page(
					__( 'ShoppingFeed Advanced', 'shopping-feed-advanced' ),
					__( 'ShoppingFeed Advanced', 'shopping-feed-advanced' ),
					'manage_options',
					self::MENU_SLUG,
					array( $this, 'load_setting_page' )
				);
			}
		);
	}

	public function load_setting_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		add_settings_section(
			'sfa_options',
			__( 'Options', 'shopping-feed-advanced' ),
			function () {
			},
			'sfa_custom_fields_settings'
		);

		add_settings_field(
			EAN_FIELD_SLUG,
			__( 'Enable EAN field', 'shopping-feed-advanced' ),
			function () {
				?>
                <input type="checkbox" name="sfa_custom_fields_options[<?php echo EAN_FIELD_SLUG; ?>]"
                       value="1" <?php checked( 1, ShoppingFeedAdvancedHelper::get_sfa_settings( EAN_FIELD_SLUG ), true ); ?> />
				<?php
			},
			'sfa_custom_fields_settings',
			'sfa_options'
		);

		add_settings_field(
			BRAND_FIELD_SLUG,
			__( 'Enable BRAND field', 'shopping-feed-advanced' ),
			function () {
				?>
                <input type="checkbox" name="sfa_custom_fields_options[<?php echo BRAND_FIELD_SLUG; ?>]"
                       value="1" <?php checked( 1, ShoppingFeedAdvancedHelper::get_sfa_settings( BRAND_FIELD_SLUG ), true ); ?> />
				<?php
			},
			'sfa_custom_fields_settings',
			'sfa_options'
		);

		?>
        <div class="wrap">
			<?php settings_errors(); ?>

            <form method="post" action="options.php">
				<?php
				settings_fields( 'sfa_custom_fields' );
				do_settings_sections( 'sfa_custom_fields_settings' );
				?>
				<?php
				submit_button( __( 'Save changes', 'shopping-feed-advanced' ) );
				?>
            </form>

            <form action="<?php echo admin_url( 'admin-post.php' ); ?>" style="float: right">
				<?php
				$migration = ShoppingFeedAdvancedHelper::get_last_action_data();
				if ( ! empty( $migration ) ) {
					echo '<h2>A migration is already exist</h2>';
					if ( 'pending' === $migration['status'] || 'In-progress' === $migration['status'] ) {
						echo '<a href="#" onClick="window.location.reload();">Refresh to check progress</a>';
					}
					echo sprintf( '<h2>Status : <strong>%s</strong></h2>', $migration['status'] );
					echo sprintf( '<h2>Date : <strong>%s</strong></h2>', $migration['date'] );
				}
				?>
                <input type="hidden" name="action" value="sfa_migrate">
				<?php
				if ( 'pending' !== $migration['status'] && 'in-progress' !== $migration['status'] ) {
					submit_button( __( 'Migrate Old Data', 'shopping-feed-advanced' ) );
				}
				?>
            </form>
        </div>

		<?php

	}
}
