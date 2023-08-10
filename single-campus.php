<?php

get_header();

while(have_posts()) {
    the_post(); 
    pageBanner(); 
    ?>
    
    <div class="container container--narrow page-section">
        
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
            <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus')?>"><i class="fa fa-home" aria-hidden="true"></i> Back to Campuses</a> 
            <span class="metabox__main"><?php the_title();?></span>
            </p>
        </div>

        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <div class="acf-map">

        <?php $mapLocation = get_field('map_location');?>

        <div data-lat="<?php echo $mapLocation['lat'];?>" data-lng="<?php echo $mapLocation['lng'];?>" class="marker">
            <h3><?php the_title();?></a></h3>
            <p><?php echo $mapLocation['address'];?></p>
        </div>        
  </div>
        
        <?php
           $relatedPrograms = new WP_Query(array(
            'post_type' => 'program',
            'posts_per_page' => -1,            
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(          
              array(
                  'key' => 'related_campus',
                  'compare' => 'LIKE',
                  'value' => '"' . get_the_ID() . '"'
              )
            )              
          ));

          if ($relatedPrograms->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--small">Realted program(s) at this campus</h2>';
            echo '<ul class="min-list link-list">';
            while($relatedPrograms->have_posts()) {
              $relatedPrograms->the_post(); ?>

              <li>
                <a href="<?php the_permalink();?>">
                  <?php the_title();?>
                </a>
              </li>
      <?php }
      echo '</ul>';
      }

      wp_reset_postdata();

          $today = date('Ymd');
          $programEvents = new WP_Query(array(
              'post_type' => 'event',
              'posts_per_page' => -1,
              'meta_key' => 'event_date',
              'orderby' => 'meta_value',
              'order' => 'ASC',
              'meta_query' => array(
                array(
                  'key' => 'event_date',
                  'compare' => '>=',
                  'value' => $today,
                  'type' => 'DATE'
                ),
                array(
                    'key' => 'related_program',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"'
                )
              )              
            ));

            if ($programEvents->have_posts()) {
              echo '<hr class="section-break">';
              echo '<h2 class="headline headline--small">Upcoming ' . get_the_title() . ' Events</h2>';
      
              while($programEvents->have_posts()) {
                $programEvents->the_post(); 

              get_template_part('template-parts/content-event');  
          }
        }
      ?>
    </div>   
  <?php }
  get_footer();
?>