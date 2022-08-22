<?php 

add_action('rest_api_init', 'universityRegisterSearch');

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
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
            ));
        }
        
        if (get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if (has_excerpt()) {
                $description = get_the_excerpt();
            }
            else {
                $description = wp_trim_words(get_the_content(), 18);
            }

            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate -> format('M'),
                'day' => $eventDate -> format('d'),
                'description' => $description
            ));
        }
        
        if (get_post_type() == 'program') {
            $relatedCampuses = get_field('related_campus');

            if ($relatedCampuses){
                foreach ($relatedCampuses as $campus) {
                    array_push($results['campuses'], array(
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus)
                    ));
                }
            }

            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_id()
            ));
        }

        if (get_post_type() == 'campus') {
            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }
    }

    if ($results['programs']) {

        $programsMetaQuery = array('relation' => 'OR');
    
        foreach ($results['programs'] as $item) {
            array_push($programsMetaQuery, array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                // Value gör queryn dynamisk genom att titta i vår results array, letar efter underarray programs,
                // tar fram första objektet [0] och letar efter en property med namnet id.
                'value' => '"' . $item['id'] . '"'
            ));
        }
    
        // A new query to target relationships between programs and professors.
        $programRelationshipQuery = new WP_Query(array(
            'post_type' => array(
                'professor',
                'event'
            ),
            'meta_query' => $programsMetaQuery
        ));
    
        while($programRelationshipQuery -> have_posts()) {
            $programRelationshipQuery -> the_post();
    
            if (get_post_type() == 'event') {
                $eventDate = new DateTime(get_field('event_date'));
                $description = null;
                if (has_excerpt()) {
                    $description = get_the_excerpt();
                }
                else {
                    $description = wp_trim_words(get_the_content(), 18);
                }
    
                array_push($results['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $eventDate -> format('M'),
                    'day' => $eventDate -> format('d'),
                    'description' => $description
                ));
            }

            if (get_post_type() == 'professor') {
                array_push($results['professors'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                )); 
            }
        }
    
        // Tittar igenom professors array och tar bort alla dubliceringar i underarrayer.
        $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));

        $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
    }


    return $results;
}

?>

