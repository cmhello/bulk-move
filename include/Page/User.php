<?php

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Bulk Move Users admin page.
 *
 * @since 2.0.0
 */
class BM_Page_User extends BM_Page_Base {

	protected function initialize() {
		$this->slug            = 'bulk-move-users';
		$this->page_title      = __( 'Bulk Move Users', 'bulk-move' );
		$this->menu_title      = __( 'Bulk Move Users', 'bulk-move' );
		$this->warning_message = __( 'WARNING: Users moved once cannot be reverted. Use with caution.', 'bulk-move' );
		$this->capability      = 'edit_others_posts';
	}

}
