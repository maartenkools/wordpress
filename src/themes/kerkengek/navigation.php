<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 1/28/2016
 * Time: 9:20 PM
 */
?>
<nav id="site-navigation" class="navigation-main" role="navigation">
	<h1 class="menu-toggle genericon"></h1>
	<div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'bushwick' ); ?>"><?php _e( 'Skip to content', 'bushwick' ); ?></a></div>

	<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
	<a class="feed" href="<?php bloginfo('rss2_url'); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/feed-icon-28x28.png" alt="RSS Feed" title="RSS Feed" /></a>
	<a class="widget-handle genericon" href="#"></a>
</nav><!-- #site-navigation -->
