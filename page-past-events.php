<?php   
  get_header();
  pageBanner();
  ?>
  
  <div class="container container--narrow page-section">

    <?php

      $today = date('Ymd');
      $pastEvents = new WP_Query(array(
          'paged' => get_query_var( 'paged', 1 ),
          'post_type' => 'event',
          'posts_per_page' => 10,
          'meta_key' => 'event_date',
          'orderby' => 'meta_value',
          'order' => 'ASC',
          'meta_query' => array(
            array(
              'key' => 'event_date',
              'compare' => '<',
              'value' => $today,
              'type' => 'DATE'
            )
          )              
        ));

    while ($pastEvents->have_posts()) {
      $pastEvents->the_post(); 
      get_template_part('template-parts/content-event'); ?>
      <?php    
    }    
      echo paginate_links(array(
          'total' => $pastEvents->max_num_pages
      ));
    ?>

  </div>

  <p class="t-center no-margin"><a href="<?php echo site_url('/events');?>" class="btn btn--blue">Back to latest Events</a></p>

  <?php  
  get_footer();
 ?>