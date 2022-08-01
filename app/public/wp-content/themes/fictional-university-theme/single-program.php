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
          $homepageEvents -> the_post(); 

          // Kallar på en metod som skrevs i mappen template-parts och filen event-excerpt
          get_template_part('template-parts/content-event');
          
          }
          // ------- Slut på Events metoden ---------
        }

        // -------  Tar fram related campus --------------

        wp_reset_postdata();// Utan denna rad syns inte eventsen på sidan. Nollställer wordpress.

        $relatedCampuses = get_field('related_campus');

        if ($relatedCampuses) {
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium">' . get_the_title() . ' is Available At These Campuses:</h2>';

          echo '<ul class="min-list link-list">'; // Creats a ul for each item to live in.
          // En loop för att skriva ut alla campuses som har en relation med ett program.
          foreach ($relatedCampuses as $campus) {
            ?> <li><a href="<?php echo get_the_permalink($campus); ?>"><?php echo get_the_title($campus) ?></a></li><?php
          }
        }

        echo '</ul>';
        ?>
        </div>
        
    <?php }

    get_footer();
?>
