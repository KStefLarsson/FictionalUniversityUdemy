<?php

    require get_theme_file_path('/inc/like-route.php');
    require get_theme_file_path('/inc/search-route.php');

    // En funktion som adderar ett värde till json rest api:t för att kunna få fram t ex authors name till ett blogginlägg.
    function university_custom_rest() {
        register_rest_field('post', 'authorName', array(
            'get_callback' => function() {return get_the_author();}
        ));
        
        register_rest_field('note', 'userNoteCount', array(
            'get_callback' => function() {return count_user_posts(get_current_user_id(), 'note');}
        ));
    }

    add_action('rest_api_init', 'university_custom_rest');

    // A function for making a page banner, so we don´t need to write this code on every page.
    // We just need to call the pageBanner function.
    function pageBanner ($args = NULL) {
        // php logic will live here
        if (!$args['title']) {
            $args['title'] = get_the_title();
        }

        if (!$args['subtitle']) {
            $args['subtitle'] = get_field('page_banner_subtitle');
        }

        if (!$args['photo']) {
            if (get_field('page_banner_background_image') AND !is_archive() AND !is_home()) {
                $args['photo'] = get_field('page_banner_background_image') ['sizes'] ['pageBanner'];
            }
            else {
                $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
            }
        }
        
        ?>
        <!-- denna div sätter bakgrundsbild till headern samt namn och subtittel på varje professor. -->
        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
                <div class="page-banner__content container container--narrow">
                    <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
                    <div class="page-banner__intro">
                    <p><?php echo $args['subtitle']; ?></p>
                    </div>
            </div>
        </div>
    <?php }

    // En funktion för att inkludera css och javascript filer 
    function university_files() {
        wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=Put Your google API KEY here', NULL, '1.0', true); //Laddar in javascript fil
        wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true); //Laddar in javascript fil
        wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i'); //Laddar in google fonts
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); //Laddar in social-media ikoner
        wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));  // Laddar in style.css stylesheet
        wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css')); // Laddar in index.css

        // Gör apiet i search.js dynamiskt så den inte bara fungerar på min lokala dator. 
        wp_localize_script('main-university-js', 'universityData', array(
            'root_url' => get_site_url(), 
            'nonce' => wp_create_nonce('wp_rest')
        ));
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
        add_image_size('pageBanner', '1500', '350', true);
    }

    add_action('after_setup_theme', 'university_features');  // Anropar funktionen university_features

    function university_adjust_queries($query) {
         // En query som hanterar campuses
        if (!is_admin() AND is_post_type_archive('campus') AND is_main_query()) {
            $query -> set('post_per_page', -1);
        }

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

    add_action('pre_get_posts', 'university_adjust_queries');

    function universityMapKey($api){
        $api['key'] = 'AIzaSyAbrj8Yl35VHhrZDLEcfuadKTMAsUVlso8';
        return $api;
    }

    add_filter('acf/fields/google_map/api', 'universityMapKey');

    // Omdirigera användare från admin dashbar och in till frontend homepage.
    add_action('admin_init', 'redirectSubsToFrontend');

    function redirectSubsToFrontend() {
        $ourCurrentUser = wp_get_current_user();
        // Om en användare endast har en roll och det är subscriber blir man omdiregerad till förstasidan.
        if (count($ourCurrentUser -> roles) == 1 AND $ourCurrentUser -> roles[0] == 'subscriber') {
            wp_redirect(site_url('/'));
            exit;
        }
    }

    // Tar bort wordpress admin menu navbar.
    add_action('wp_loaded', 'noSubsAdminBar');

    function noSubsAdminBar() {
        $ourCurrentUser = wp_get_current_user();
        // Om en användare endast har en roll och det är subscriber får man inte tillgång till wordpress navbar.
        if (count($ourCurrentUser -> roles) == 1 AND $ourCurrentUser -> roles[0] == 'subscriber') {
            show_admin_bar(false);
        }
    }

    // Ändra på login sidan. Första argumentet är vad som ska ändras, det andra dem nya förändringarna.
    // Här ändras url:n från wordpress.org till våran egna.
    add_filter('login_headerurl', 'ourHeaderUrl');

    function ourHeaderUrl() {
        return esc_url(site_url('/'));
    }
    
    add_action('login_enqueue_scripts', 'ourLoginCSS');

    function ourLoginCSS() {
        wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i'); //Laddar in google fonts
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); //Laddar in social-media ikoner
        wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));  // Laddar in style.css stylesheet
        wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css')); // Laddar in index.css
    }

    add_filter('login_headertitle', 'ourLoginTitle');

    function ourLoginTitle() {
        return get_bloginfo('name');
    }

    // Filtrera inlägg som skrivs in av användare.
    add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

    function makeNotePrivate($data, $postarr) {
      if ($data['post_type'] == 'note') {
            // Sätter en begränsning på hur många post man kan skapa.
            if(count_user_posts(get_current_user_id(), 'note') > 4 AND !$postarr['ID']) {
                die("You have reached your note limit.");
            }

            // Sanerar det som användaren skriver in, filtrerar bort html och javascript.
            $data['post_content'] = sanitize_textarea_field($data['post_content']);
            $data['post_title'] = sanitize_text_field($data['post_title']);
        }

        // Force note posts to be private
        if($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
            $data['post_status'] = "private";
        }
        return $data;
    }


?>

