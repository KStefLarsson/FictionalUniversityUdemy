<?php

    // En funktion för att inkludera en css fil.
    function university_files() {
        wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true); //Laddar in javascript fil

        wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i'); //Laddar in google fonts
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); //Laddar in social-media ikoner
        wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css')); //
        wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));  // Laddar in style.css stylesheet
    }

    add_action('wp_enqueue_scripts', 'university_files');  // Kallar på funktionen university_files som laddar in alla javascript och .css filer

    function university_features() {  // En funktion för att visa titteln på webläsar-fliken
        // register_nav_menu('headerMenuLocation', 'Header Menu Location');  // Gör en meny synlig i wordpress dashbar under fliken appearance
        // register_nav_menu('footerLocationOne', 'Footer Location One');
        // register_nav_menu('footerLocationTwo', 'Footer Location Two');
        add_theme_support('title-tag');
    }

    add_action('after_setup_theme', 'university_features');  // Anropar funktionen university_features
?>