<?php
/**
 * The template for displaying the footer.
 *
 * @package Bushwick
 */
?>
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'bushwick_credits' ); ?>
			<p>
				<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'bushwick' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s.', 'bushwick' ), 'WordPress' ); ?></a>
				<?php printf( __( 'Theme: %1$s by %2$s.', 'bushwick' ), 'Bushwick', '<a href="http://molovo.co.uk" rel="designer">James Dinsdale</a>' ); ?>
			</p>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->


    <script type="text/javascript">
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-56805775-1', 'auto');
        ga('send', 'pageview');

    </script>
	<?php wp_footer(); ?>

</body>
</html>
