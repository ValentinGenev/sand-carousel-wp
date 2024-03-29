<?php
/**
 * Creating the custom post type and it's taxonomies.
 * 
 * @package Sand_Carousel_WP
 * @version 1.0.0
 */

// Die if accessed directly.
defined('ABSPATH') or die('Hey! Get out!');

class Sand_Carousel_WP_Post_Type {

    /***********************************************************
     * Registers the post type and the taxonomy:
     ***********************************************************/
    public static function add_filters_and_actions() {
        add_action('init',			array('Sand_Carousel_WP_Post_Type', 'slide_post_type'), 0);        
        add_action('init',			array('Sand_Carousel_WP_Post_Type', 'slides_group_taxamony'), 10);

        // Custom meta boxes
        add_action('admin_init',	array('Sand_Carousel_WP_Post_Type', 'slide_custom_fields'));
        add_action('save_post',		array('Sand_Carousel_WP_Post_Type', 'save_custom_fields'));

        // Custom columns on admin slides list        
        add_filter('manage_edit-slide_columns',		array('Sand_Carousel_WP_Post_Type', 'slide_custom_columns'));
        add_action('manage_posts_custom_column',	array('Sand_Carousel_WP_Post_Type', 'slide_custom_columns_data'), 10, 2); 
    }

    /***********************************************************
     * Custom post type:
     ***********************************************************/
    public static function slide_post_type() {
        $labels = array(
            'name'                  => _x('Slides', 'Post Type General Name', 'sand-carousel-wp'),
            'singular_name'         => _x('Slide', 'Post Type Singular Name', 'sand-carousel-wp'),
            'menu_name'             => __('Slides', 'sand-carousel-wp'),
            'name_admin_bar'        => __('Slide', 'sand-carousel-wp'),
            'archives'              => __('Slide Archives', 'sand-carousel-wp'),
            'attributes'            => __('Slide Attributes', 'sand-carousel-wp'),
            'parent_item_colon'     => __('Parent slide:', 'sand-carousel-wp'),
            'all_items'             => __('All slides', 'sand-carousel-wp'),
            'add_new_item'          => __('Add New Slide', 'sand-carousel-wp'),
            'add_new'               => __('New slide', 'sand-carousel-wp'),
            'new_item'              => __('New slide', 'sand-carousel-wp'),
            'edit_item'             => __('Edit slide', 'sand-carousel-wp'),
            'update_item'           => __('Update slide', 'sand-carousel-wp'),
            'view_item'             => __('View slide', 'sand-carousel-wp'),
            'view_items'            => __('View slides', 'sand-carousel-wp'),
            'search_items'          => __('Search slides', 'sand-carousel-wp'),
            'not_found'             => __('No slides found', 'sand-carousel-wp'),
            'not_found_in_trash'    => __('No slides found in Trash', 'sand-carousel-wp'),
            'featured_image'        => __('Featured Image', 'sand-carousel-wp'),
            'set_featured_image'    => __('Set featured image', 'sand-carousel-wp'),
            'remove_featured_image' => __('Remove featured image', 'sand-carousel-wp'),
            'use_featured_image'    => __('Use as featured image', 'sand-carousel-wp'),
            'insert_into_item'      => __('Insert into slide', 'sand-carousel-wp'),
            'uploaded_to_this_item' => __('Uploaded to this slide', 'sand-carousel-wp'),
            'items_list'            => __('Slides list', 'sand-carousel-wp'),
            'items_list_navigation' => __('Slides list navigation', 'sand-carousel-wp'),
            'filter_items_list'     => __('Filter slides list', 'sand-carousel-wp'),
        );
        $args = array(
            'label'                 => __('Slide', 'sand-carousel-wp'),
            'description'           => __('Slide information pages.', 'sand-carousel-wp'),
            'labels'                => $labels,
            'supports'              => array('title', 'thumbnail', 'custom-fields'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 7,
            'menu_icon'             => 'dashicons-slides',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
                
        register_post_type('slide', $args);
    }

    /***********************************************************
     * Custom taxanomy (categories):
     ***********************************************************/
    public static function slides_group_taxamony() {
        $labels = array(
            'name'                          => _x('Slides Groups', 'Taxonomy General Name', 'sand-carousel-wp'),
            'singular_name'                 => _x('Slides Group', 'Taxonomy Singular Name', 'sand-carousel-wp'),
            'menu_name'                     => __('Slides Groups', 'sand-carousel-wp'),
            'all_items'                     => __('All Groups', 'sand-carousel-wp'),
            'parent_item'                   => __('Parent Group', 'sand-carousel-wp'),
            'parent_item_colon'             => __('Parent Group:', 'sand-carousel-wp'),
            'new_item_name'                 => __('New slides group', 'sand-carousel-wp'),
            'add_new_item'                  => __('Add slides group', 'sand-carousel-wp'),
            'edit_item'                     => __('Edit Group', 'sand-carousel-wp'),
            'update_item'                   => __('Update Group', 'sand-carousel-wp'),
            'view_item'                     => __('View Group', 'sand-carousel-wp'),
            'separate_items_with_commas'    => __('Separate items with commas', 'sand-carousel-wp'),
            'add_or_remove_items'           => __('Add or remove groupes', 'sand-carousel-wp'),
            'choose_from_most_used'         => __('Choose from the most used', 'sand-carousel-wp'),
            'popular_items'                 => __('Popular groupes', 'sand-carousel-wp'),
            'search_items'                  => __('Search slides groups', 'sand-carousel-wp'),
            'not_found'                     => __('Not Found', 'sand-carousel-wp'),
            'no_terms'                      => __('No slides groups', 'sand-carousel-wp'),
            'items_list'                    => __('Items list', 'sand-carousel-wp'),
            'items_list_navigation'         => __('Items list navigation', 'sand-carousel-wp'),
        );
        $args = array(
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'show_in_rest'      => true,
            'capabilities'      => array(
                'edit_terms'    => 'edit_pages',
                'delete_terms'  => 'edit_pages',
            ),
        );
        register_taxonomy('slides_group', array('slide'), $args);
    }

    /***********************************************************
     * Custom fields:
     ***********************************************************/
    public static function slide_custom_fields() {
        add_meta_box('slide_information', __('The slide\'s data', 'sand-carousel-wp'), array('Sand_Carousel_WP_Post_Type', 'slide_information'), 'slide', 'normal');
    }

    // Single fields initiation functions:
    public static function slide_information() {
        global $post;

        // This displays the field data from the database
        $custom			= get_post_custom($post->ID);
        $information	= $custom['information'][0];
        $redirection    = $custom['redirection'][0];
		$background		= $custom['background'][0];

        echo '
            <fieldset class="custom-meta-box">
                <label for="slide_information">' . __('Additional information', 'sand-carousel-wp') . ' <em>' . __('(Short summary or other additional information should be written below.)', 'sand-carousel-wp') . '</em></label>
                <input id="slide_information" name="information" type="text" value="' . $information . '">
            </fieldset>

            <fieldset class="custom-meta-box">
                <label for="slide_redirection">' . __('Redirection link', 'sand-carousel-wp') . '</label>
                <input id="slide_redirection" name="redirection" type="url" value="' . $redirection . '">
            </fieldset>

            <fieldset class="custom-meta-box">
                <label for="slide_background_color">' . __('Background color', 'sand-carousel-wp') . '</label>
                <input class="my-color-field" id="slide_background_color" name="background" type="text" data-default-color="#fff" value="' . $background . '">
            </fieldset>
		';
    }

    // Saving the data from all the custom fields:
    public static function save_custom_fields() {
        global $post;

        // Event ifnormation fields
        update_post_meta($post->ID, "information", $_POST["information"]);
        update_post_meta($post->ID, "redirection", $_POST["redirection"]);
        update_post_meta($post->ID, "background", $_POST["background"]);
    }


    /***********************************************************
     * Custom fields:
     ***********************************************************/
    function slide_custom_columns($columns) {
        $columns = array(
            'cb'                => '<input type="checkbox" />',
            'featured_image'    => 'Image',
            'title'             => 'Title',
            'slides_group'      => 'Group',
            'date'              => 'Date'
        );

        return $columns;
    }

    function slide_custom_columns_data($column, $post_id) {
        switch ($column) {
            case 'featured_image':
                the_post_thumbnail(array(100, 100));
                break;
            case 'slides_group':
                $slide_gropus = get_the_terms($post_id, 'slides_group');
                if ($slide_gropus) {
                    foreach ($slide_gropus as $group) {
                        echo '<a href="./edit.php?slides_group=' . $group->slug . '&post_type=slide">' . $group->name . '</a>, ';
                    }
                }
                else {
                    echo '-';
                }
                break;
        }
    }
}