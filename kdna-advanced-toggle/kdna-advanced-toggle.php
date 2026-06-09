<?php
/**
 * Plugin Name: KDNA Advanced Toggle
 * Description: A lightweight Advanced Toggle widget for Elementor with editor and template content support.
 * Version: 1.0.0
 * Author: KrullDNA
 * Requires Plugins: elementor
 * Text Domain: kdna-advanced-toggle
 * License: GPL v2 or later
 */

defined( 'ABSPATH' ) || exit;

define( 'KDNA_TOGGLE_VERSION', '1.0.0' );
define( 'KDNA_TOGGLE_PATH', plugin_dir_path( __FILE__ ) );
define( 'KDNA_TOGGLE_URL', plugin_dir_url( __FILE__ ) );

final class KDNA_Advanced_Toggle {

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
		require_once KDNA_TOGGLE_PATH . 'controls/group-control-foreground.php';
		$controls_manager->add_group_control( 'foreground', new \KDNAToggle\Controls\Group_Control_Foreground() );
	}

	public function register_widgets( $widgets_manager ) {
		require_once KDNA_TOGGLE_PATH . 'widgets/toggle-widget.php';
		$widgets_manager->register( new \KDNAToggle\Widgets\Toggle_Widget() );
	}

	/**
	 * Cache-busting version: use the file's modification time so updated
	 * CSS/JS are always re-fetched, falling back to the plugin version.
	 */
	private function asset_ver( $relative_path ) {
		$file = KDNA_TOGGLE_PATH . $relative_path;
		return file_exists( $file ) ? filemtime( $file ) : KDNA_TOGGLE_VERSION;
	}

	public function enqueue_frontend_scripts() {
		wp_enqueue_script(
			'kdna-toggle-frontend',
			KDNA_TOGGLE_URL . 'assets/js/toggle.js',
			[ 'jquery', 'elementor-frontend' ],
			$this->asset_ver( 'assets/js/toggle.js' ),
			true
		);
	}

	public function enqueue_frontend_styles() {
		wp_enqueue_style(
			'kdna-toggle-frontend',
			KDNA_TOGGLE_URL . 'assets/css/toggle.css',
			[],
			$this->asset_ver( 'assets/css/toggle.css' )
		);
	}

	public function enqueue_editor_styles() {
		wp_enqueue_style(
			'kdna-toggle-editor',
			KDNA_TOGGLE_URL . 'assets/css/editor.css',
			[],
			$this->asset_ver( 'assets/css/editor.css' )
		);
	}
}

add_action( 'elementor/init', [ 'KDNA_Advanced_Toggle', 'instance' ] );
