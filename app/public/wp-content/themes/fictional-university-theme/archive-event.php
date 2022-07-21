<?php 
get_header();

?>

<div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/barksalot.jpg') ?>)"></div>
        <div class="page-banner__content container container--narrow">
            
        <!-- Visar rubriken i headern olika beroende på vilken sida du besöker. -->
            <h1 class="page-banner__title">All Events</h1>
            
            <div class="page-banner__intro">
            <p>See what is going on in our world.</p>
            </div>
        </div>
        </div>
<div class="container container--narrow page-section">
  <?php 

  while(have_posts()) {
    the_post(); ?>

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
    echo paginate_links();  
  ?>
  
<hr class="section-break">

  <p>Looking for a recap of past events? <a href="<?php echo site_url('/past-events') ?>">Check out our past events archive.</a></p>

</div>

<?php 
  get_footer();


?>