<!-- Denna class är för att visa events som sedan skrivs ut på sidan på olika platser,
och inte behöva skriva samma kod på flera olika .php-filer -->
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