<?php
sh_custom_header();
wp_reset_query();
global $post_type, $wp_query;
$settings      = get_post_meta( get_the_ID(), '_page_settings', true );
$sidebar       = sh_set( $settings, 'sidebar' ) ? sh_set( $settings, 'sidebar' ) : '';
$col_class     = sh_set( $settings, 'sidebar' ) ? 'col-md-9' : '';
$paged         = get_query_var( 'paged' );
$theme_options = get_option( SH_NAME );
$buddy         = ( function_exists( 'bp_is_active' ) ) ? 'true' : 'false';

if ( class_exists( 'bbPress' ) ) {
	$bbpress = is_bbpress();
} else {
	$bbpress = '';
}
?>

<?php if ( sh_set( $settings, 'header' ) == 'true' ) : ?>
	<?php if ( sh_set( $settings, 'top_image' ) ): ?>
		<div class="top-image"><img src="<?php echo sh_set( $settings, 'top_image' ); ?>" alt=""/>
			<h1 class="page-title"><?php the_title(); ?></h1></div>
	<?php else: ?>
		<div class="no-top-image"><h1 class="page-title"><?php the_title(); ?></h1></div>
	<?php endif; ?>
	<?php
else:
	if ( sh_set( $settings, 'is_home' ) != 1 ):
		?>
		<div class="no-top-image"><h1 class="page-title"><?php the_title(); ?></h1></div>
		<?php
	endif;
endif;
?>

<?php
if ( ( sh_set( $settings, 'header' ) == 'true' || $post_type == 'forum' || $post_type == 'reply' || $post_type == 'topic' || $bbpress == 1 || sh_woo_pages( get_the_ID() ) == 'true' ) && sh_set( $settings, 'is_home' ) != 1 ) {
	$section_class = 'inner-page';
} else {
	$section_class = '';
}
?>

<section class="<?php echo $section_class; ?>">
	<?php if ( $sidebar != "" ) : ?>
	<div class="container">
		<div class="row">
			<?php endif; ?>
			<?php
			if ( $post_type == 'forum' || $post_type == 'topic' || $post_type == 'reply' || $bbpress == 1 || sh_woo_pages( get_the_ID() ) == 'true' ) {
				echo '<div class="container">';
			}
			?>
			<div class="left-content  <?php echo $col_class; ?>">
				<?php while ( have_posts() ): the_post(); ?>
					<?php
					if ( has_post_thumbnail() ):
						the_post_thumbnail( '270x155', array( 'class' => 'page_thumb' ) );
					endif;
					?>
					<?php
					if ( $post_type == 'forum' || $post_type == 'topic' || $post_type == 'reply' || $bbpress == 1 || sh_woo_pages( get_the_ID() ) == 'true' ) {
						echo '<div class="container">  ';
					}
					?>
					<?php if ( $sidebar == "" ) : ?>
						<div class="default">
					<?php endif; ?>
					<?php the_content(); ?>
					</div>
					<?php if ( $sidebar == "" ) : ?>
						<?php
						if ( $post_type == 'forum' || $post_type == 'topic' || $post_type == 'reply' || $bbpress == 1 || sh_woo_pages( get_the_ID() ) == 'true' ) {
							echo '</div">  ';
						}
						?>
					<?php endif; ?>
				<?php endwhile; ?>
				<?php
				if ( $post_type == 'forum' || $post_type == 'topic' || $post_type == 'reply' || $bbpress == 1 || sh_woo_pages( get_the_ID() ) == 'true' ) {
					echo '</div>';
				}
				?>
				<?php if ( $sidebar ) : ?>
					<div class="sidebar col-md-3 pull-right">
						<?php dynamic_sidebar( $sidebar ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $sidebar != "" ) : ?>
			</div>
		</div>
		<?php endif; ?>
</section>
<?php get_footer(); ?>
