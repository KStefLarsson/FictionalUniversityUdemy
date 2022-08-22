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
                'title'
            ),
            'rewrite' => array('slug' => 'programs'),
            'has_archive' => true,
            'public' => true,       // Detta gör den synlig.
            'show_in_rest' => true,
            'labels' => array(
                'name' => 'Programs',  // Byter namn från Posts som är default namnet till Programs.
                'add_new_item' => 'Add New Program',  // Ändrar rubriken i wordpress dashboard från Add New Post till Add New Event.
                'edit_item' => 'Edit Program',  // Ändrar rubriken i wordpress dashboard från Edit Post till Edit Program.
                'all_items' => 'All Programs',  // Ändrar rubriken i wordpress dashboard menyn från Program till All Program.
                'singular_name' => 'Program'
            ),
            'menu_icon' => 'dashicons-awards'  // Ändrar ikonen till en egen unik som jag själv väljer genom att googla på wordpress dashicons.
        ));

        
        // Professor Post Type

        register_post_type('professor', array(
            'show_in_rest' => true,
            'supports' => array(
                'title',
                'editor',
                'thumbnail' // Detta enablar featured image flik på respektive professor i wordpress dashboard.
            ),
            'public' => true,       // Detta gör den synlig.
            'show_in_rest' => true,
            'labels' => array(
                'name' => 'Professor',  // Byter namn från Posts som är default namnet till Professor.
                'add_new_item' => 'Add New professor',  // Ändrar rubriken i wordpress dashboard från Add New Post till Add New Professor.
                'edit_item' => 'Edit professor',  // Ändrar rubriken i wordpress dashboard från Edit Post till Edit Professor.
                'all_items' => 'All Professor',  // Ändrar rubriken i wordpress dashboard menyn från Professors till All Professors.
                'singular_name' => 'professor'
            ),
            'menu_icon' => 'dashicons-welcome-learn-more'  // Ändrar ikonen till en egen unik som jag själv väljer genom att googla på wordpress dashicons.
        ));

        // Campus Post Type

        register_post_type('campus', array(
            'show_in_rest' => true,
            'supports' => array(
                'title',
                'editor',
                'excerpt',
            ),
            'rewrite' => array('slug' => 'campuses'),
            'has_archive' => true,
            'public' => true,       // Detta gör den synlig.
            'show_in_rest' => true,
            'labels' => array(
                'name' => 'Campuses',  // Byter namn från Posts som är default namnet till Events.
                'add_new_item' => 'Add New Campus',  // Ändrar rubriken i wordpress dashboard från Add New Post till Add New Event.
                'edit_item' => 'Edit Campus',  // Ändrar rubriken i wordpress dashboard från Edit Post till Edit Event.
                'all_items' => 'All Campuses',  // Ändrar rubriken i wordpress dashboard menyn från Events till All Events.
                'singular_name' => 'Campus'
            ),
            'menu_icon' => 'dashicons-location-alt'  // Ändrar ikonen till en egen unik som jag själv väljer genom att googla på wordpress dashicons.
        ));
    }

    add_action('init', 'University_post_types');
?>