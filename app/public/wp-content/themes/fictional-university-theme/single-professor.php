<?php
    get_header();

    while (have_posts()) {
        the_post(); 
        // denna div sätter bakgrundsbild till headern samt namn och subtittel på varje professor.
        pageBanner();
        ?>

        <!-- en box på en event sida med knapp tillbaka till alla events och en ruta med namnet på eventet -->
        <div class="container container--narrow page-section">
            

            <div class="generic-content">
                <div class="row group">
                    <div class="one-third">
                        <?php the_post_thumbnail('professorPortrait'); ?>
                    </div>
                    <div class="two-thirds">
                        <?php 
                            // Ser till att gillamarkeringen hamnar på rätt professor med hjälp av id:t
                            $likeCount = new WP_Query(array(
                                'post_type' => 'like',
                                'meta_query' => array(
                                    array(
                                        'key' => 'liked_professor_id',
                                        'compare' => '=',
                                        'value' => get_the_ID()
                                    )
                                )
                            ));

                            $existStatus = 'no';

                            if (is_user_logged_in()) {
                                // Håller reda på om en användare gillat en professor eller inte
                                $likeExists = new WP_Query(array(
                                    'author' => get_current_user_id(),
                                    'post_type' => 'like',
                                    'meta_query' => array(
                                        array(
                                            'key' => 'liked_professor_id',
                                            'compare' => '=',
                                            'value' => get_the_ID()
                                        )
                                    )
                                ));
    
                                // Om likeExists får ett posts ändras existStatus till yes
                                if ($likeExists -> found_posts) {
                                    $existStatus = 'yes';
                                }
                            }

                        ?>
                        <!-- Renderar like box till professorer -->
                        <span class="like-box" data-like="<?php echo $likeExists -> posts[0] -> ID; ?>" data-professor="<?php the_ID() ?>" data-exists="<?php echo $existStatus; ?>">
                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                            <i class="fa fa-heart" aria-hidden="true"></i>
                            <span class="like-count"><?php echo $likeCount -> found_posts; ?></span>
                        </span>
                        <?php the_content(); ?>
                    </div>
                </div>  
            </div>

            <?php 
             $relatedPrograms = get_field('related_programs');

             if ($relatedPrograms) {
                 echo '<hr class="section-break">';
                 echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
                 echo '<ul class="link-list min-list">';
                 foreach ($relatedPrograms as $program) { ?>
                    <li><a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a></li>
                    
                <?php }
                echo '</ul>';
             }
            ?>
        </div>
        
    <?php }

    get_footer();
?>
