<?php

    get_header();

    while (have_posts()) {
        the_post(); 
        pageBanner();
        ?>

        <!-- en box på en event sida med knapp tillbaka till alla events och en ruta med namnet på eventet -->
        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" 
                aria-hidden="true"></i>All Programs</a><span class="metabox__main"><?php the_title(); ?></span></p>
            </div>

            <div class="generic-content"><?php the_content(); ?></div>
            <?php  
            // -------  Tar fram Professorer --------------    
              $relatedProfessors = new WP_Query(array(
                'posts_per_page' => -1,
                'post_type' => 'professor',
                'orderby' => 'title',
                'order' => 'ASC',
                // meta_ query sedr till att filtrera bort alla events som har gamla datum.
                'meta_query' => array(
                  // en inner array som filtrerar alla event som har en relation med ett program tillhörande ett specifikt id.
                  array(
                      'key' => 'related_programs',
                      'compare' => 'LIKE',
                      'value' => '"' . get_the_ID() . '"'
                  )
                )
              ));
      
              // if satsen visar endast rubriken Upcomming Events och alla events om den är true. dvs om det finns events att visa.
              if ($relatedProfessors -> have_posts()) {
                  
              echo '<hr class="section-break">';
              echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professor</h2>';
      
              // En loop för att skriva ut alla events som har en relation med ett program.
              echo '<ul class="professor-cards">';
              while ($relatedProfessors -> have_posts()) {
                $relatedProfessors -> the_post(); ?>
                <li class="professor-card__list-item">
                  <a class="professor-card" href="<?php the_permalink(); ?>">
                    <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>">
                    <span class="professor-card__name"><?php the_title(); ?></span>
                </a></li>
                <?php }
                echo '</ul>';
              }
              // ------- Slut på Professor metoden ---------

              wp_reset_postdata(); // Utan denna rad syns inte eventsen på sidan. Nollställer wordpress.

              // -------  Tar fram Events --------------
      // skapar en variabel som håller koll på DAGENS datum
      $today = date('Ymd');
      // Skapar en custom query for EVENTS -------
        $homepageEvents = new WP_Query(array(
          'posts_per_page' => 2,
          'post_type' => 'event',
          // meta_key och orderby sorterar alla events efter datum det snaraste eventet visas överst.
          'meta_key' => 'event_date',
          'orderby' => 'meta_value_num',
          'order' => 'ASC',
          // meta_ query sedr till att filtrera bort alla events som har gamla datum.
          'meta_query' => array(
            array(
              'key' => 'event_date',
              'compare' => '>=',
              'value' => $today,
              'type' => 'numeric'
            ),
            // en inner array som filtrerar alla event som har en relation med ett program tillhörande ett specifikt id.
            array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . get_the_ID() . '"'
            )
          )
        ));

        // if satsen visar endast rubriken Upcomming Events och alla events om den är true. dvs om det finns events att visa.
        if ($homepageEvents -> have_posts()) {
            
        echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">Upcomming ' . get_the_title() . ' Events</h2>';

        // En loop för att skriva ut alla events som har en relation med ett program.
        while ($homepageEvents -> have_posts()) {
          $homepageEvents -> the_post(); ?>
          
          <div class="event-summary">
            <a class="event-summary__date t-center" href="#">
              <span class="event-summary__month">
                <!-- Hämtar och sätter datumvärdet på en ny variabel eventDate -->
                <?php  
                $eventDate = new DateTime(get_field('event_date'));
                // skriver ut värdet från variabeln men endast i formatet 'M' som står för månad och skrivs ut med tre bosktäver.
                echo $eventDate -> format('M');
              ?></span>
              <!-- Vi tar värdet från eventDate och skriver ut formatet 'd' för dag med två siffror -->
              <span class="event-summary__day"><?php echo $eventDate -> format('d'); ?></span>
            </a>
            <div class="event-summary__content">
              <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
              <p><?php if (has_excerpt()) {
                echo get_the_excerpt();
              }
              else {
                echo wp_trim_words(get_the_content(), 18);
              } ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
            </div>
          </div>
          
          <?php }
          // ------- Slut på Events metoden ---------
        }

        ?>
        </div>
        
    <?php }

    get_footer();
?>
