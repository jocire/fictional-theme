<?php
  // Fallback file for native search if JS is disabled in browser 
  get_header();
?>
  
  <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg');?>)"></div>
      <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title">Search Results</h1>
          <div class="page-banner__intro">
          <p>You searched for: "<?php echo esc_html(get_search_query());?>"</p>
          </div>
      </div>
  </div> 
  
  <div class="container container--narrow page-section">
    <?php
    if(have_posts()){
      while(have_posts()) {
        the_post(); 
        // Get one template part for each post type (existing problem here with professor post type query in native search results (Non JS))
        get_template_part('template-parts/content', get_post_type());
        }
        echo paginate_links();
    } else {
      echo '<h2 class="headline headline--small-plus">No results match that search</h2>';
    } ?>
    <h3>Would you like to proceed with another search?</h3>
    <?php
    get_search_form();
    ?>
  </div>
  
<?php
  get_footer();
?>