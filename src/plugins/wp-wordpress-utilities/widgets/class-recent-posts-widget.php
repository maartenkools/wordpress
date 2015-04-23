<?php

/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 3/21/2015
 * Time: 2:49 PM
 */

/**
 * Represents a custom 'Recent Posts' widget which allows users to exclude certain categories.
 */
class WPU_RecentPostsWidget extends WP_Widget
{
    function __construct()
    {
        $options = array(
            'classname' => 'widget_recent_entries',
            'description' => __("Your site&#8217;s most recent Posts.")
        );

        parent::__construct('recent-posts', __('Recent Posts with excluded categories', WPU_Plugin::$textDomain), $options);

        $this->alt_option_name = 'widget_recent_entries';

        add_action('save_post', array($this, 'flushCache'));
        add_action('deleted_post', array($this, 'flushCache'));
        add_action('switch_post', array($this, 'flushCache'));
    }

    public static function register()
    {
        unregister_widget('WP_Widget_Recent_Posts');
        register_widget('WPU_RecentPostsWidget');
    }

    public function widget($args, $instance)
    {
        $cache = array();
        if (!$this->is_preview()) {
            $cache = wp_cache_get('widget_recent_posts', 'widget');
        }

        if (!is_array($cache)) {
            $cache = array();
        }

        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        if (isset($cache[$args['widget_id']])) {
            echo $cache[$args['widget_id']];
            return;
        }

        ob_start();

        $title = (!empty($instance['title'])) ? $instance['title'] : __('Recent Posts');

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
        if (!$number)
            $number = 5;
        $show_date = isset($instance['show_date']) ? $instance['show_date'] : false;

        $excludedCategoryIds = isset($instance['excluded_category_ids']) ? $instance['excluded_category_ids'] : array();

        /**
         * Filter the arguments for the Recent Posts widget.
         *
         * @since 3.4.0
         *
         * @see WP_Query::get_posts()
         *
         * @param array $args An array of arguments used to retrieve the recent posts.
         */
        $r = new WP_Query(apply_filters('widget_posts_args', array(
            'posts_per_page' => $number,
            'no_found_rows' => true,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
            'category__not_in' => $excludedCategoryIds
        )));

        if ($r->have_posts()) :
            ?>
            <?php echo $args['before_widget']; ?>
            <?php if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        } ?>
            <ul>
                <?php while ($r->have_posts()) : $r->the_post(); ?>
                    <li>
                        <a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
                        <?php if ($show_date) : ?>
                            <span class="post-date"><?php echo get_the_date(); ?></span>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
            <?php echo $args['after_widget']; ?>
            <?php
            // Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();

        endif;

        if (!$this->is_preview()) {
            $cache[$args['widget_id']] = ob_get_flush();
            wp_cache_set('widget_recent_posts', $cache, 'widget');
        } else {
            ob_end_flush();
        }
    }

    public function update($newInstance, $oldInstance)
    {
        $instance = $oldInstance;
        $instance['title'] = strip_tags($newInstance['title']);
        $instance['number'] = (int)$newInstance['number'];
        $instance['show_date'] = isset($newInstance['show_date']) ? (bool)$newInstance['show_date'] : false;
        $instance['excluded_category_ids'] = $newInstance['excluded_category_ids'];
        $this->flushCache();

        $options = wp_cache_get('alloptions', 'options');
        if (isset($options['widget_recent_entries']))
            delete_option('widget_recent_entries');

        return $instance;
    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $show_date = isset($instance['show_date']) ? (bool)$instance['show_date'] : false;
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>"
                   name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>"
                   size="3"/></p>

        <p><input class="checkbox" type="checkbox" <?php checked($show_date); ?>
                  id="<?php echo $this->get_field_id('show_date'); ?>"
                  name="<?php echo $this->get_field_name('show_date'); ?>"/>
            <label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Display post date?'); ?></label></p>

        <label
            for="<?php echo $this->get_field_id('excluded_category_ids'); ?>"><?php _e('Select categories to exclude in the recent posts list:', WPU_Plugin::$textDomain); ?></label>
        <div id="<?php echo $this->get_field_id('excluded_category_ids'); ?>" class="category-container">
            <?php foreach ($this->__getCategories($instance) as $category): ?>
                <div>
                    <input type="checkbox"
                           id="<?php echo $this->get_field_id('excluded_category_ids'); ?>[<?php echo $category->term_id; ?>]"
                           name="<?php echo $this->get_field_name('excluded_category_ids'); ?>[]"
                           value="<?php echo $category->term_id; ?>" <?php checked($this->__isExcluded($category->term_id, $instance)) ?> />
                    <label
                        for="<?php echo $this->get_field_id('excluded_category_ids'); ?>[<?php echo $category->term_id; ?>]"><?php echo esc_attr($category->cat_name); ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    <?php
    }

    private function __getCategories($instance)
    {
        $categories = get_categories('hide_empty=0');

        if (is_array($instance['excluded_category_ids'])) {
            $sort = function ($a, $b) use ($instance) {
                $a_checked = false;
                $b_checked = false;

                foreach ($instance['excluded_category_ids'] as $excludedId) {
                    if ($excludedId == $a->term_id) $a_checked = true;
                    else if ($excludedId == $b->term_id) $b_checked = true;
                }

                if($a_checked && !$b_checked) return -1;
                if(!$a_checked && $b_checked) return 1;

                return $a->term_id - $b->term_id;
            };

            usort($categories, $sort);
        }

        return $categories;
    }

    private function __isExcluded($categoryId, $instance)
    {
        if (is_array($instance['excluded_category_ids'])) {
            foreach ($instance['excluded_category_ids'] as $excludedId) {
                if ($excludedId == $categoryId) return true;
            }
        }

        return false;
    }

    public function flushCache()
    {
        wp_cache_delete('widget_recent_posts', 'widget');
    }
}
