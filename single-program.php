<?php  
  get_header();

  while(have_posts()) {
    the_post();
    pageBanner(); ?>

    <div class="container container--narrow page-section">
          <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs</a> <span class="metabox__main"><?php the_title(); ?></span></p>
      </div>
      <!-- Replacing the_content field with ACF for exclusion of content search results in live search -->
      <div class="generic-content"><?php the_field('main_body_content'); ?></div>
      
      <?php 
        $relatedProfessors = new WP_Query(array(
          'posts_per_page' => -1,
          'post_type' => 'professor',
          'orderby' => 'title',
          'order' => 'ASC',
          'meta_query' => array(
            array(
              'key' => 'related_program',
              'compare' => 'LIKE',
              'value' => '"' . get_the_ID() . '"'
            )
          )
        ));

        if ($relatedProfessors->have_posts()) {
        echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>';

        echo '<ul class="professor-cards">';
        while($relatedProfessors->have_posts()) {
          $relatedProfessors->the_post(); 
          get_template_part('template-parts/content-professor');
        }        
        echo '</ul>';        
        }

        wp_reset_postdata();        

        $today = date('Ymd');
        $programEvents = new WP_Query(array(
            'post_type' => 'event',
            'posts_per_page' => 2,
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
        echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';

        while($programEvents->have_posts()) {
          $programEvents->the_post();
          get_template_part('template-parts/content-event');        
        }        
        }

        wp_reset_postdata();

        $relatedCampuses = get_field('related_campus');

        if ($relatedCampuses) {
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium">' . get_the_title() . ' is Available At These Campuses:</h2>';

          echo '<ul class="min-list link-list">';
          foreach($relatedCampuses as $campus) {
            ?> <li><a href="<?php echo get_the_permalink($campus); ?>"><?php echo get_the_title($campus) ?></a></li> <?php
          }
          echo '</ul>';
        }
      ?>
    </div>    
  <?php }
  get_footer();
?>