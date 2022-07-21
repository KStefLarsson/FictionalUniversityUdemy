<?php 
get_header();

?>

<div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>)"></div>
        <div class="page-banner__content container container--narrow">
            
        <!-- Visar rubriken i headern olika beroende på vilken sida du besöker. -->
            <h1 class="page-banner__title">Past Events</h1>
            
            <div class="page-banner__intro">
            <p>A recap of our past events.</p>
            </div>
        </div>
        </div>
<div class="container container--narrow page-section">
  <?php 

    $today = date('Ymd');
    // Skapar en custom query for EVENTS -------
    $pastEvents = new WP_Query(array(
        'paged' => get_query_var('paged', 1),
        'post_type' => 'event',
        // meta_key och orderby sorterar alla events efter datum det snaraste eventet visas överst.
        'meta_key' => 'event_date',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        // meta_ query sedr till att filtrera bort alla events som har gamla datum.
        'meta_query' => array(
        array(
            'key' => 'event_date',
            'compare' => '<',
            'value' => $today,
            'type' => 'numeric'
        )
        )
    ));

  while($pastEvents -> have_posts()) {
    $pastEvents -> the_post(); ?>

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
            <p><?php echo wp_trim_words(get_the_content(), 18); ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
        </div>
     </div>

  <?php }
    echo paginate_links(array(
        'total' => $pastEvents -> max_num_pages
    ));  
  ?>
</div>

<?php 
  get_footer();


?>