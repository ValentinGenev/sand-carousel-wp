<?php
/**
 * This class registers a shortcode for the Sand Slider.
 * 
 * @package Sand_Carousel_WP
 * @version 1.0.0
 */

// Die if accessed directly.
defined('ABSPATH') or die('Hey! Get out!');

class Sand_Carousel_WP_Shortcode {

	/***********************************************************
	 * Registers the shortcode:
	 ***********************************************************/
	public static function register_shortcode() {
		add_shortcode('sand_carousel', array('Sand_Carousel_WP_Shortcode', 'sand_carousel_wp_template'));
	}

	/***********************************************************
	 * The query and the markup for the Sand Sldier:
	 ***********************************************************/	
	public static function sand_carousel_wp_template($atts) {
		// Slide options
		$carousel_title		= (isset($atts['title'])) ? $atts['title'] : __('My Carousel', 'sand-slider');
		$slide_duration		= (isset($atts['duration'])) ? $atts['duration'] : 5000;
		$slider_controls	= (isset($atts['arrows'])) ? $atts['arrows'] : 0;
		$slides_group		= (isset($atts['group'])) ? $atts['group'] : false;
		$slider_id			= (isset($atts['id'])) ? 'id="' . $atts['id'] . '"' : '';
		$slider_class		= (isset($atts['class'])) ? ' ' . $atts['class'] : '';

		// The markup
		$output				= '';

		$slides_args		= array(
			'post_type'		=> 'slide',
			'post_status'	=> 'publish',
			'orderby'		=> 'date',
			'order'			=> 'DESC',
			'tax_query'		=> array(
				($slides_group) ? array(
					'taxonomy'  => 'slides_group',
					'field'     => 'term_id',
					'terms'     => $slides_group,
				) : null,
			),
		);
		$slides_query		= new WP_Query($slides_args);

		if ($slides_query->have_posts()) :
			$output = '
				<section class="sand-slider' . $slider_class . '"' . $slider_id . '>
					<h2 class="screen-reader-text">' . $carousel_title . '</h2>

					<ul id="slides">';

			while ($slides_query->have_posts()) : $slides_query->the_post();
				$post_ID		= get_the_ID();
				$info			= (get_post_meta($post_ID, 'information', true)) ? '<span class="info">' . get_post_meta($post_ID, 'information', true) . '</span>' : '';
				$redirection	= (get_post_meta($post_ID, 'redirection', true)) ? get_post_meta($post_ID, 'redirection', true) : false;

				$output .= ($redirection) ? '<a href="' . $redirection . '">' : '';
				$output .= '
					<li class="slide">
						<h3>' . get_the_title() . '</h3>
						<div class="more-information">
							' . $info . '
							<span class="read-more">' . __('Learn more', 'sand-slider') . '</span>
						</div>
						<figure>
							' . get_the_post_thumbnail($post_ID, 'full') . '
						</figure>
					</li>';
				$output .= ($redirection) ? '</a>' : '';

			endwhile;
			wp_reset_query();

			$output .= '
					</ul>
				</section>
			';

			if (!is_admin()) {
				wp_enqueue_script('sand-slider-init', plugins_url('/assets/js/init.js' , __DIR__), array(), false, true);
				wp_localize_script('sand-slider-init', 'sliderOptions', array(
					'slideDuration'		=> $slide_duration,
					'sliderControls'	=> $slider_controls,
				));
			}
		endif;

		return $output;
	}
}