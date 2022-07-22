<?php 
get_header();
pageBanner(array(
  'title' => 'Past Events',
  'subtitle' => 'A recap of our past events.'
));
?>

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
    $pastEvents -> the_post();

    // Kallar på en metod som skrevs i mappen template-parts och filen event-excerpt
    get_template_part('template-parts/content-event');

  }
    echo paginate_links(array(
        'total' => $pastEvents -> max_num_pages
    ));  
  ?>
</div>

<?php 
  get_footer();


?>