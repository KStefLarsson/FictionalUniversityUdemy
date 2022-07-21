<?php

    get_header();

    while (have_posts()) {
        the_post(); 
        
        pageBanner();
        ?>

        <div class="container container--narrow page-section">

        <?php 
            // sparar sidans parents id i en variabel som används i if-satsen.
            $theParent = wp_get_post_parent_id(get_the_ID());

            if ($theParent) { ?>
                <!-- Är boxen som visar "Back to About Us", php the_title() visar titeln i boxen bredvid t ex "our history". -->
                <div class="metabox metabox--position-up metabox--with-home-link">
                    <p>
                    <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home" 
                    aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span>
                    </p>
                </div>
            <?php }
        ?>


        <?php 
        $testArray = get_pages(array(
            'child_of' => get_the_ID()
        ));

        if ($theParent or $testArray) { ?>
        <div class="page-links">

            <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a></h2>

            <ul class="min-list">
                <?php 
                    if($theParent) {
                        $findChildernOf = $theParent;
                    }
                    else {
                        $findChildernOf = get_the_ID();
                    }

                wp_list_pages(array(
                    'title_li' => NULL,
                    'child_of' => $findChildernOf,
                    'sort_column' => 'menu_order'
                )); 
                ?> 
            </ul>
        </div>
        <?php } ?>

        <div class="generic-content">
            <?php the_content(); ?>    
        </div>
        </div>
        
    <?php }
    get_footer();
?>
