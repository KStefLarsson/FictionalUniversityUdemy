<?php 

/* Funktion för att skapa ett eget rest api.
   register_rest_route sätter url:n där första argumentet är namespace, det andra är route (det sista ordet på url:n)
   och det tredje argumentet är vad som ska skickas med.
   Vi talar här om att det ska vara en GET med hjälp av parametern 'methods' och callbacken skickar in en annan funktion som 
   bestämmer vilken data som ska skickas med.
*/
function universityRegisterSearch() {
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,  // Samma som GET fast säkrare då den gör så det fungerar får alla.
        'callback' => 'universitySearchResults'
    ));
}

/* Inputparametern $data tar in requestet från användaren via 's' som står för search, 
   sanatize_text_field() används som validering för att användaren inte ska skicka in skadlig kod.
   Vi skapar en ny tom array och bestämmer vilken post_type den ska gälla för.
   loopar igenom arrayen så länge det finns data och lägger till den datan vi sagt att vi vill ha i den tomma arrayen.
*/
function universitySearchResults($data) {
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'professor', 'event', 'campus', 'program'),
        's' => sanitize_text_field($data['term']) 
    ));

    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array()
    );

    while($mainQuery -> have_posts()) {
        $mainQuery -> the_post();

        if (get_post_type() == 'post' OR get_post_type() == 'page') {
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            ));
        }

        if (get_post_type() == 'professor') {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }
        
        if (get_post_type() == 'event') {
            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }
        
        if (get_post_type() == 'program') {
            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }

        if (get_post_type() == 'campus') {
            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }
    }

    return $results;
}

add_action('rest_api_init', 'universityRegisterSearch');


?>