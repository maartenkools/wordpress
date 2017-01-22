</div>
<?php
$settings = get_option( SH_NAME );
?>


<div class="footer-bottom">

	<div class="container">

		<p><?php echo stripslashes( sh_set( $settings, 'footer_copyright' ) ); ?>All rights reserved.</p>

	</div>

</div>

<?php wp_footer(); ?>
<script type="text/javascript">Stripe.setPublishableKey('<?php echo STRIPE_PUBLIC_KEY; ?>');</script>

</body>

</html>
