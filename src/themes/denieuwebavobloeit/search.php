<?php
/**
 * The blog list template file.
 *
 */
get_header('site');

$values = explode(',', WPU_Plugin::current()->get_options()->getValue('archives', 'excluded_categories'));

$ids = array();
foreach ($values as $value) $ids[] = intval($value);

if (!$wp_query) global $wp_query;

?>

<div class="site-main">

    <div class="main-content">
        <div class="content-area">
            <div class="site-content" role="main">
                <header class="archive-header">
                    <h1 class="archive-title"><?php onetone_get_breadcrumb(); ?></h1>
                </header>
                <?php query_posts(array_merge(array('category__not_in' => $ids), $wp_query->query)) ?>
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post();

                        get_template_part("content", "article");
                        ?>

                    <?php endwhile; ?>
                <?php else: ?>
                    <?php get_template_part("content", "none"); ?>
                <?php endif; ?>

                <nav class="paging-navigation">
                    <div class="loop-pagination">
                        <?php if (function_exists("onetone_native_pagenavi")) {
                            onetone_native_pagenavi("echo", $wp_query);
                        } ?>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!--main-->

    <div class="sidebar">
        <div class="widget-area">
            <?php dynamic_sidebar(1); ?>
        </div>
    </div>
    <!--sidebar-->
</div>
<?php get_footer('site'); ?>
