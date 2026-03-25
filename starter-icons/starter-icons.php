<?php
/**
 * Plugin Name: Starter Icons for Elementor
 * Description: Adds 688 Happy Icons and 3,882 Huge Icons to the Elementor icon picker.
 * Version: 1.0.0
 * Author: KrullDNA
 * Requires Plugins: elementor
 * Text Domain: starter-icons
 * License: GPL v2 or later
 */

defined( 'ABSPATH' ) || exit;

define( 'STARTER_ICONS_VERSION', '1.0.0' );
define( 'STARTER_ICONS_URL', plugin_dir_url( __FILE__ ) );

add_filter( 'elementor/icons_manager/additional_tabs', 'starter_icons_register_tabs' );

function starter_icons_register_tabs( $tabs ) {
	$tabs['happy-icons'] = [
		'name'          => 'happy-icons',
		'label'         => __( 'Happy Icons', 'starter-icons' ),
		'url'           => STARTER_ICONS_URL . 'assets/fonts/style.min.css',
		'enqueue'       => [ STARTER_ICONS_URL . 'assets/fonts/style.min.css' ],
		'prefix'        => 'hm-',
		'displayPrefix' => 'hm',
		'labelIcon'     => 'hm hm-star',
		'ver'           => STARTER_ICONS_VERSION,
		'fetchJson'     => STARTER_ICONS_URL . 'assets/fonts/happy-icons.js?v=' . STARTER_ICONS_VERSION,
		'native'        => false,
	];

	$tabs['huge-icons'] = [
		'name'          => 'huge-icons',
		'label'         => __( 'Huge Icons', 'starter-icons' ),
		'url'           => STARTER_ICONS_URL . 'assets/fonts/huge-icons/huge-icons.min.css',
		'enqueue'       => [ STARTER_ICONS_URL . 'assets/fonts/huge-icons/huge-icons.min.css' ],
		'prefix'        => 'huge-',
		'displayPrefix' => 'huge',
		'labelIcon'     => 'huge huge-star',
		'ver'           => STARTER_ICONS_VERSION,
		'fetchJson'     => STARTER_ICONS_URL . 'assets/fonts/huge-icons/huge-icons.js?v=' . STARTER_ICONS_VERSION,
		'native'        => false,
	];

	return $tabs;
}
