<?php
/*
Plugin Name: Elementor Test Plugin
Plugin URI:
Description:
Version: 1.0
Author: LWHH
Author URI: https://hasin.me
License: GPLv2 or later
Text Domain: elementortestplugin
Domain Path: /languages/
*/

use \Elementor\Plugin as Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	die( __( "Direct Access is not allowed", 'elementortestplugin' ) );
}

final class ElementorTestExtension {

	const VERSION = "1.0.0";
	const MINIMUM_ELEMENTOR_VERSION = "2.0.0";
	const MINIMUM_PHP_VERSION = "7.0";

	private static $_instance = null;

	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;

	}

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	public function init() {
		load_plugin_textdomain( 'elementor-test-extension' );

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );

			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );

			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );

			return;
		}

		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

		add_action( "elementor/elements/categories_registered", [ $this, 'register_new_category' ] );

		add_action( "elementor/frontend/after_enqueue_styles", [ $this, 'widget_styles' ] );
		add_action( "elementor/editor/after_enqueue_scripts", [ $this, 'pricing_editor_assets' ] );
		add_action( "elementor/frontend/after_enqueue_scripts" , [ $this, 'progressbar_assets' ] );

	}

	function progressbar_assets(){
		wp_enqueue_script( "progressbar-js", plugins_url( "/assets/js/progressbar.min.js", __FILE__ ),null, time(), true );
		wp_enqueue_script( "progressbar-helper-js", plugins_url( "/assets/js/scripts.js", __FILE__ ),null, time(), true );

	}


	function pricing_editor_assets() {
		wp_enqueue_script( "pricing-editor-js", plugins_url( "/assets/js/main.js", __FILE__ ), array( "jquery" ), time(), true );
	}


	function widget_styles() {
		wp_enqueue_style( "froala-css", "//cdnjs.cloudflare.com/ajax/libs/froala-design-blocks/2.0.1/css/froala_blocks.min.css" );
	}


	public function register_new_category( $manager ) {
		$manager->add_category( 'testcategory', [
			'title' => __( 'Test Category', 'elementortestplugin' ),
			'icon'  => 'fa fa-image'
		] );

		$manager->add_category( 'sliders', [
			'title' => __( 'Sliders', 'elementortestplugin' ),
			'icon'  => 'fa fa-video'
		] );
	}

	public function init_widgets() {
		require_once( __DIR__ . '/widgets/test-widget.php' );
		require_once( __DIR__ . '/widgets/faq-widget.php' );
		require_once( __DIR__ . '/widgets/pricing-widget.php' );
		require_once( __DIR__ . '/widgets/progressbar-widget.php' );

		// Register widget
		Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_Test_Widget() );
		Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_Faq_Widget() );
		Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_Pricing_Widget() );
		Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_Progressbar_Widget() );

	}


	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementortestplugin' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementortestplugin' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementortestplugin' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementortestplugin' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementortestplugin' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementortestplugin' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementortestplugin' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementortestplugin' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementortestplugin' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );


	}

	public function includes() {
	}

}

ElementorTestExtension::instance();