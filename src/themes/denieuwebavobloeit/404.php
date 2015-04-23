<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 3/28/2015
 * Time: 1:00 PM
 */
get_header('site');

$section_background = onetone_options_array('section_background_0');
$background = onetone_get_background($section_background);

$background .= 'background-attachment:fixed;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;';
?>
<section>
    <section style="<?php echo $background; ?>" class="content">
        <div class="home-container page_container">
            <icon class="fa fa-4 fa-frown-o"></icon>
            <h1>De pagina kon niet worden gevonden!</h1>
            <h2><?php _e( 'Search for:'); ?></h2>
            <div>
                <?php get_search_form(true); ?>
            </div>
        </div>
    </section>
</section>
<?php get_footer('site'); ?>
