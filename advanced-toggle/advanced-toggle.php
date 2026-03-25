<?php
/**
 * Plugin Name: Advanced Toggle for Elementor
 * Description: A lightweight Advanced Toggle widget for Elementor with editor and template content support.
 * Version: 1.0.0
 * Author: KrullDNA
 * Requires Plugins: elementor
 * Text Domain: advanced-toggle
 * License: GPL v2 or later
 */

defined( 'ABSPATH' ) || exit;

define( 'ADV_TOGGLE_VERSION', '1.0.0' );
define( 'ADV_TOGGLE_PATH', plugin_dir_path( __FILE__ ) );
define( 'ADV_TOGGLE_URL', plugin_dir_url( __FILE__ ) );

final class Advanced_Toggle_Plugin {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_frontend_styles' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
	}

	public function register_controls( $controls_manager ) {
		require_once ADV_TOGGLE_PATH . 'controls/group-control-foreground.php';
		$controls_manager->add_group_control( 'foreground', new \AdvancedToggle\Controls\Group_Control_Foreground() );
	}

	public function register_widgets( $widgets_manager ) {
		require_once ADV_TOGGLE_PATH . 'widgets/toggle-widget.php';
		$widgets_manager->register( new \AdvancedToggle\Widgets\Toggle_Widget() );
	}

	public function enqueue_frontend_scripts() {
		wp_enqueue_script(
			'advanced-toggle-frontend',
			ADV_TOGGLE_URL . 'assets/js/toggle.js',
			[ 'jquery', 'elementor-frontend' ],
			ADV_TOGGLE_VERSION,
			true
		);
	}

	public function enqueue_frontend_styles() {
		wp_enqueue_style(
			'advanced-toggle-frontend',
			ADV_TOGGLE_URL . 'assets/css/toggle.css',
			[],
			ADV_TOGGLE_VERSION
		);
	}

	public function enqueue_editor_styles() {
		wp_enqueue_style(
			'advanced-toggle-editor',
			ADV_TOGGLE_URL . 'assets/css/editor.css',
			[],
			ADV_TOGGLE_VERSION
		);
	}
}

add_action( 'elementor/init', [ 'Advanced_Toggle_Plugin', 'instance' ] );
