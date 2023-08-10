<?php
    require get_theme_file_path( '/inc/search-route.php' );
    require get_theme_file_path( '/inc/like-route.php' );

    function university_custom_rest() {
      register_rest_field( 'post', 'authorName', array(
        'get_callback' => function(){return get_the_author();}
      ));

      register_rest_field( 'note', 'userNoteCount', array(
        'get_callback' => function(){return count_user_posts(get_current_user_id(), 'note');}
      ));
    }
    add_action('rest_api_init', 'university_custom_rest');

    function pageBanner() {
      if(is_page() || is_singular()){
      ?>
        <div class="page-banner">
          <div class="page-banner__bg-image" style="background-image: url(
              <?php 
              $pageBannerImage = get_field('page_banner_background_image'); 

              if(get_field('page_banner_background_image')){
                echo $pageBannerImage['sizes']['pageBanner'];
              } else {
                echo $pageBannerImage = get_theme_file_uri('/images/ocean.jpg');
              }
              ?>    
              )">
          </div>
          <div class="page-banner__content container container--narrow">           
              <h1 class="page-banner__title"><?php the_title()?></h1>
                  <div class="page-banner__intro">
                  <p><?php the_field('page_banner_subtitle');?></p>
                  </div>
          </div>
        </div>
      <?php 
      } elseif(is_archive()){
        ?>
        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg');?>)"></div>
            <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php 
            if (is_category()) {
                the_archive_title();                
            } 
            if(is_author()) {
                ?> Posts by <?php the_author();
            } ?></h1>
                <div class="page-banner__intro">
                <p><?php the_archive_description();?></p>
                </div>
            </div>
        </div> 
        <?php        
      }     
    }

    // Remove Title Prefix from Archive Pages
    function my_theme_archive_title( $title ) {
      if ( is_category() ) {
          $title = single_cat_title( '', false );
      } elseif ( is_tag() ) {
          $title = single_tag_title( '', false );
      } elseif ( is_author() ) {
          $title = '<span class="vcard">' . get_the_author() . '</span>';
      } elseif ( is_post_type_archive() ) {
          $title = post_type_archive_title( '', false );
      } elseif ( is_tax() ) {
          $title = single_term_title( '', false );
      }
    
      return $title;
  }
   
  add_filter( 'get_the_archive_title', 'my_theme_archive_title' );

    function university_files(){
        wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyAgfGmYt26TtcfS5EmxiuGamF0fYVVP4UA', NULL, '1.0', true );
        wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true );
        wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css');
        wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
        wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
        wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));        
        // Creating site URL JS Object
        wp_localize_script('main-university-js', 'universityData', array(
          'root_url' => get_site_url(),
          'nonce' => wp_create_nonce('wp_rest')          
        ));
    }
    add_action('wp_enqueue_scripts', 'university_files');

    function university_features() {
        register_nav_menu( 'header_menu', 'Header Menu Location' );
        register_nav_menu( 'footer_menu-1', 'Footer Menu Location 1' );
        register_nav_menu( 'footer_menu-2', 'Footer Menu Location 2' );
        register_nav_menu( 'social_menu', 'Social Menu Location' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'menus' );
        add_theme_support( 'post-thumbnails' );
        add_image_size( 'professorLandscape', 400, 260, true );
        add_image_size( 'professorPortrait', 480, 640, true );
        add_image_size( 'pageBanner', 1500, 350, true );
    }
    add_action( 'after_setup_theme', 'university_features' );
    
    function university_adjust_queries($query) {
      if (!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
        $query->set('posts_per_page', -1);
      }
      if(!is_admin() && is_post_type_archive('program') && $query->is_main_query()){
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('post_per_page', -1);
      }
      if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
                  array(
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'DATE'
                  )
                ));
       }     
    }      
    add_action('pre_get_posts', 'university_adjust_queries'); 

    function universityMapKey($api){
      $api['key'] = 'AIzaSyAgfGmYt26TtcfS5EmxiuGamF0fYVVP4UA';
      return $api;
    }
    add_filter('acf/fields/google_map/api', 'universityMapKey');

    //Redirect Subscriber accounts from admin to homepage
    function redirectSubsToFrontend(){
      $currentUser = wp_get_current_user();
      if(count($currentUser->roles) == 1 && $currentUser->roles[0] == 'subscriber') {
        wp_redirect( site_url('/'));
        exit;
      }
    }
    add_action( 'admin_init', 'redirectSubsToFrontend' );
    
    //Remove Admin Bar for Subscriber accounts 
    function removeAdminBarSub(){
      $currentUser = wp_get_current_user();
      if(count($currentUser->roles) == 1 && $currentUser->roles[0] == 'subscriber') {
       show_admin_bar(false);
      }
    }
    add_action( 'wp_loaded', 'removeAdminBarSub' );

    //Customize Login Screen
    function ourHeaderUrl(){
      return esc_url(site_url('/'));
    }
    add_filter('login_headerurl','ourHeaderUrl');

    function ourLoginCSS(){
      wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
      wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css');
      wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
      wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
      wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));        
    }
    add_action('login_enqueue_scripts', 'ourLoginCSS');

    function changeLoginTitle(){
      return get_bloginfo('name');
    }
    add_filter('login_headertext', 'changeLoginTitle');

    // Force note posts to be private
    function makeNotePrivate($data, $postarr){
      if($data['post_type'] == 'note'){
        // Set post limit for notes
        if(count_user_posts(get_current_user_id() && !$postarr['ID'], 'note') > 4){
          die("You have reached your note limit.");
        }
        $data['post_title'] = sanitize_text_field($data['post_title']);
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
      }
      if($data['post_type'] == 'note' && $data['post_status'] != 'trash'){
      $data['post_status'] = "private";
      }
      return $data;      
    }
    add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

?>

    
  