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
        $slides_group           = (isset($atts['group'])) ? $atts['group'] : 0;
        $slide_duration         = (isset($atts['duration'])) ? $atts['duration'] : 5000;
        $transition_duration    = (isset($atts['transition'])) ? $atts['transition'] : 500;
        $resizable_carousel     = (isset($atts['resizable'])) ? $atts['resizable'] : 0;
        $autoplay               = (isset($atts['autoplay'])) ? $atts['autoplay'] : 1;
        $carousel_controls      = (isset($atts['arrows'])) ? $atts['arrows'] : 1;
        $carousel_id            = (isset($atts['id'])) ? $atts['id'] : uniqid('carousel_');
        $carousel_class         = (isset($atts['className'])) ? ' ' . $atts['className'] : '';

        // The markup
        $output                 = '';

        $slides_args            = array(
            'post_type'         => 'slide',
            'post_status'       => 'publish',
            'orderby'           => 'date',
            'order'             => 'DESC',
            'tax_query'         => array(
                ($slides_group) ?  array(
                    'taxonomy'  => 'slides_group',
                    'field'     => 'slug',
                    'terms'     => $slides_group,
                ) : null,
            ),
        );
        $slides_query           = new WP_Query($slides_args);

        if ($slides_query->have_posts()) :
            $output = '
                <div class="sand-carousel' . $carousel_class . '" id="' . $carousel_id . '">
                    <ul class="slides-wrapper">';

            while ($slides_query->have_posts()) : $slides_query->the_post();
                $post_ID        = get_the_ID();
                $info           = (get_post_meta($post_ID, 'information', true)) ? '<span class="info">' . get_post_meta($post_ID, 'information', true) . '</span>' : '';
                $redirection    = (get_post_meta($post_ID, 'redirection', true)) ? get_post_meta($post_ID, 'redirection', true) : false;

                $output .= ($redirection) ? '<a href="' . $redirection . '">' : '';
                $output .= '
                    <li class="slide">
                        <h3>' . get_the_title() . '</h3>
                        <div class="more-information">
                            ' . $info . '
                            <span class="read-more">' . __('Learn more', 'sand-carousel-wp') . '</span>
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
                </div>
            ';

            if (!is_admin()) {
                if (!wp_script_is('sand-carousel')) {
                    wp_enqueue_style('sand-carousel',   plugins_url('/assets/css/vendor/sand-carousel.min.css' , __DIR__), array(), false);
                    wp_enqueue_script('sand-carousel',  plugins_url('/assets/js/vendor/sand-carousel.min.js' , __DIR__), array(), false);
                }
                wp_enqueue_script('sand-carousel-init'. $carousel_id, plugins_url('/assets/js/init.js' , __DIR__), array('sand-carousel'), false);                
                wp_localize_script('sand-carousel-init'. $carousel_id, 'sliderOptions', array(
                    'carouselId'            => '#' . $carousel_id,
                    'carouselSlides'        => '#' . $carousel_id . ' .slide',
                    'slideDuration'         => $slide_duration,
                    'transitionDuration'    => $transition_duration,
                    'resizable'             => $resizable_carousel,
                    'autoPlay'              => $autoplay,
                    'carouselControls'      => $carousel_controls,
                ));
            }        
        endif;

        return $output;
    }
}
