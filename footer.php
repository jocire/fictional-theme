        <footer class="site-footer">
            <div class="site-footer__inner container container--narrow">
                <div class="group">
                    <div class="site-footer__col-one">
                        <h1 class="school-logo-text school-logo-text--alt-color">
                        <a href="<?php echo site_url()?>"><strong><?php bloginfo('title')?></strong></a>
                        </h1>
                        <p><a class="site-footer__link" href="mailto:<?php bloginfo('admin_email')?>"><?php bloginfo('admin_email')?></a></p>
                    </div>

                    <div class="site-footer__col-two-three-group">
                        <div class="site-footer__col-two">
                        <h3 class="headline headline--small">Explore</h3>
                        <nav class="nav-list">
                        <?php wp_nav_menu(array(
                                'theme_location' => 'footer_menu-1'
                            )); ?> 
                        </nav>
                        </div>

                        <div class="site-footer__col-three">
                        <h3 class="headline headline--small">Legal</h3>
                        <nav class="nav-list">
                            <?php wp_nav_menu(array(
                                'theme_location' => 'footer_menu-2'
                            )); ?> 
                        </nav>
                        </div>
                    </div>

                    <div class="site-footer__col-four">
                        <h3 class="headline headline--small">Connect With Us</h3>
                        <nav>
                        <?php wp_nav_menu(array(
                                'theme_location' => 'social_menu'
                            )); ?> 
                        </nav>
                    </div>
                </div>
            </div>
        </footer> 
    <?php wp_footer(); ?>
    </body>
</html>