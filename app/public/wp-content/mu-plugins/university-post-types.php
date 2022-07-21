<?php 
    // Event Post Type
    // En funktion som skapar en ny post_type (custom_post_type (event)) som syns i wordpress dashboard.
    function university_post_types() {
        register_post_type('event', array(
            'show_in_rest' => true,
            'supports' => array(
                'title',
                'editor',
                'excerpt',
            ),
            'rewrite' => array('slug' => 'events'),
            'has_archive' => true,
            'public' => true,       // Detta gör den synlig.
            'show_in_rest' => true,
            'labels' => array(
                'name' => 'Events',  // Byter namn från Posts som är default namnet till Events.
                'add_new_item' => 'Add New Event',  // Ändrar rubriken i wordpress dashboard från Add New Post till Add New Event.
                'edit_item' => 'Edit Event',  // Ändrar rubriken i wordpress dashboard från Edit Post till Edit Event.
                'all_items' => 'All Events',  // Ändrar rubriken i wordpress dashboard menyn från Events till All Events.
                'singular_name' => 'Event'
            ),
            'menu_icon' => 'dashicons-calendar'  // Ändrar ikonen till en egen unik som jag själv väljer genom att googla på wordpress dashicons.
        ));

        // Program Post Type

        register_post_type('program', array(
            'show_in_rest' => true,
            'supports' => array(
                'title',
                'editor'
            ),
            'rewrite' => array('slug' => 'programs'),
            'has_archive' => true,
            'public' => true,       // Detta gör den synlig.
            'show_in_rest' => true,
            'labels' => array(
                'name' => 'programs',  // Byter namn från Posts som är default namnet till Events.
                'add_new_item' => 'Add New Program',  // Ändrar rubriken i wordpress dashboard från Add New Post till Add New Event.
                'edit_item' => 'Edit Program',  // Ändrar rubriken i wordpress dashboard från Edit Post till Edit Event.
                'all_items' => 'All Programs',  // Ändrar rubriken i wordpress dashboard menyn från Events till All Events.
                'singular_name' => 'Program'
            ),
            'menu_icon' => 'dashicons-awards'  // Ändrar ikonen till en egen unik som jag själv väljer genom att googla på wordpress dashicons.
        ));
    }

    add_action('init', 'University_post_types');
?>