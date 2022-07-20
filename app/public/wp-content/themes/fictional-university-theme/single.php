<?php
    get_header();



    while (have_posts()) {
        the_post(); ?>

        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>)"></div>
            <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php the_title(); ?></h1>
                <div class="page-banner__intro">
                    <p>Dont forget to replace me later.</p>
                </div>
            </div>
        </div>

        <h2><?php the_title(); ?></h2>
        <?php the_content(); ?>        
        
    <?php }

    get_footer();
?>
