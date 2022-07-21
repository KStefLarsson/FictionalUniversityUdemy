<?php

    // En funktion för att inkludera css och javascript filer 
    function university_files() {
        wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true); //Laddar in javascript fil

        wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i'); //Laddar in google fonts
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); //Laddar in social-media ikoner
        wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css')); // Laddar in index.css
        wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));  // Laddar in style.css stylesheet
    }

    add_action('wp_enqueue_scripts', 'university_files');  // Kallar på funktionen university_files som laddar in alla javascript och .css filer

    function university_features() {  // En funktion för att visa titteln på webläsar-fliken
        // register_nav_menu('headerMenuLocation', 'Header Menu Location');  // Gör en meny synlig i wordpress dashboard under fliken appearance
        // register_nav_menu('footerLocationOne', 'Footer Location One');
        // register_nav_menu('footerLocationTwo', 'Footer Location Two');
        add_theme_support('title-tag');
        // This is for photographs on professors
        add_theme_support('post-thumbnails');
        // Bestäm själv storlek på bilder
        add_image_size('professorLandscape', '400', '260', true);
        add_image_size('professorPortrait', '480', '650', true);
    }

    add_action('after_setup_theme', 'university_features');  // Anropar funktionen university_features

    function university_adjust_queries($query) {
        // En query som hanterar programs
        if (!is_admin() AND is_post_type_archive('program') AND is_main_query()) {
            $query -> set('orderby', 'title');
            $query -> set('order', 'ASC');
            $query -> set('post_per_page', -1);
        }

        // En query som hanterar events på events sidan, sorterar och filtrerar.
        if (!is_admin() AND is_post_type_archive('event') AND $query -> is_main_query()) {
            // skapar en variabel som håller koll på DAGENS datum
            $today = date('Ymd');
            // meta_key och orderby sorterar alla events efter datum det snaraste eventet visas överst.
            $query -> set('meta_key', 'event_date');
            $query -> set('orderby', 'meta_value_num');
            $query -> set('order', 'ASC');
            // meta_ query sedr till att filtrera bort alla events som har gamla datum.
            $query -> set('meta_query', array(
                array(
                  'key' => 'event_date',
                  'compare' => '>=',
                  'value' => $today,
                  'type' => 'numeric'
                )
            ));
        }
    }

    add_action('pre_get_posts', 'university_adjust_queries')

?>

