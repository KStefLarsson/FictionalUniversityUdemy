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
    $professors = new WP_Query(array(
        'post_type' => 'professor',
        's' => sanitize_text_field($data['term']) 
    ));

    $professorResult = array();

    while($professors -> have_posts()) {
        $professors -> the_post();
        array_push($professorResult, array(
            'title' => get_the_title(),
            'link' => get_the_permalink()
        ));
    }

    return $professorResult;
}

add_action('rest_api_init', 'universityRegisterSearch');


?>