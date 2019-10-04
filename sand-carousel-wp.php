<?php
/**
 * @package Sand_Carousel_WP
 * @version 1.0.0
 */

/**
 * Plugin Name:	Sand Carousel WP
 * Description:	This plugin creates custom post type named Slide that's displayed in the Sand Slider. <strong>WARNING</strong>: Uninstalling the plugin will delete it's entries.
 * Author:		Valentin Genev
 * Version:		1.0.0
 * Author URI:	http://www.sitesandbox.eu/
 * License:		GPL-2.0+
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Die if accessed directly.
defined('ABSPATH') or die('Hey! Get out!');

if (!class_exists('Sand_Carousel_WP')) {
	class Sand_Carousel_WP {

		/***********************************************************
		 * Standart constructor, runs on new instance creations
		 ***********************************************************/
		function __construct() {
			require_once plugin_dir_path(__FILE__) . '/inc/class-custom-post-type.php';
			Sand_Carousel_WP_Post_Type::add_filters_and_actions();

			require_once plugin_dir_path(__FILE__) . '/inc/class-shortcode-markup.php';
			Sand_Carousel_WP_Shortcode::register_shortcode();
			
			// Hooks the template files
			$this->add_filters_and_actions();
		}

		/***********************************************************
		 * Standart plugin methods: activate, deactivate
		 ***********************************************************/
		function activate() {
			Sand_Carousel_WP_Post_Type::slide_post_type();
			// Rewrites database
			flush_rewrite_rules();
		}
		function deactivate() {
			// Rewrites database
			flush_rewrite_rules();
		}

		/***********************************************************
		 * Hooks:
		 ***********************************************************/
		protected function add_filters_and_actions() {
			// Adds the text domain
			add_action('plugins_loaded',		array($this, 'sand_carousel_wp_load_textdomain'));

			// Adds the editor styles:
            add_action('admin_enqueue_scripts',	array($this, 'add_custom_styles_to_the_editor'));

			// Add Sand Slider
			add_action('wp_enqueue_scripts',	array($this, 'add_sand_carousel_wp_styles_and_scripts'));
		}

		/***********************************************************
		 * Enqueueing the AJAX scripts:
		 ***********************************************************/
		function add_sand_carousel_wp_styles_and_scripts() {
			wp_enqueue_style('sand-slider',		plugins_url('/assets/css/vendor/sand-slider.css' , __FILE__), array(), false);
			wp_enqueue_script('sand-slider',	plugins_url('/assets/js/vendor/sand-slider.js' , __FILE__), array(), false, true);
		}
		
		/***********************************************************
		 * Enqueueing the edit page's custom styles:
		 ***********************************************************/
		function add_custom_styles_to_the_editor() {
            // Load the theme styles within Gutenberg.
            wp_enqueue_style('sand-slider-edit-page',	plugins_url('/assets/css/edit-page-slide.css' , __FILE__), false);
        }

		/***********************************************************
		 * Registers the text domain for the plugin:
		 ***********************************************************/
		function sand_carousel_wp_load_textdomain() {
			load_plugin_textdomain('sand-slider', FALSE, basename(dirname(__FILE__)) . '/languages/');
		}
	}

	// Save instance of the plugin's class in a variable
	$sand_carousel_wp = new Sand_Carousel_WP();
	
	// Passing the plugin's main method
	register_activation_hook(__FILE__, array($sand_carousel_wp, 'activate'));
	register_deactivation_hook(__FILE__, array($sand_carousel_wp, 'deactivate'));
}