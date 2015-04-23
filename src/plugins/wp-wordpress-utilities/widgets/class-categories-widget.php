<?php

/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 3/21/2015
 * Time: 7:29 PM
 */
class WPU_CategoriesWidget extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'widget_categories',
            'description' => __("A list or dropdown of categories.")
        );
        parent::__construct('categories', __('Categories with excluded categories', WPU_Plugin::$textDomain), $widget_ops);
    }

    public static function register()
    {
        unregister_widget('WP_Widget_Categories');
        register_widget('WPU_CategoriesWidget');
    }

    public function widget($args, $instance)
    {

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Categories') : $instance['title'], $instance, $this->id_base);

        $c = !empty($instance['count']) ? '1' : '0';
        $h = !empty($instance['hierarchical']) ? '1' : '0';
        $d = !empty($instance['dropdown']) ? '1' : '0';
        $ec = !empty($instance['excluded_category_ids']) ? $instance['excluded_category_ids'] : array();

        echo $args['before_widget'];
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $cat_args = array('orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h);

        $cat_args['exclude'] = implode(',', $ec);

        if ($d) {
            $cat_args['show_option_none'] = __('Select Category');

            /**
             * Filter the arguments for the Categories widget drop-down.
             *
             * @since 2.8.0
             *
             * @see wp_dropdown_categories()
             *
             * @param array $cat_args An array of Categories widget drop-down arguments.
             */
            wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
            ?>

            <script type='text/javascript'>
                /* <![CDATA[ */
                var dropdown = document.getElementById("cat");
                function onCatChange() {
                    if (dropdown.options[dropdown.selectedIndex].value > 0) {
                        location.href = "<?php echo home_url(); ?>/?cat=" + dropdown.options[dropdown.selectedIndex].value;
                    }
                }
                dropdown.onchange = onCatChange;
                /* ]]> */
            </script>

        <?php
        } else {
            ?>
            <ul>
                <?php
                $cat_args['title_li'] = '';

                /**
                 * Filter the arguments for the Categories widget.
                 *
                 * @since 2.8.0
                 *
                 * @param array $cat_args An array of Categories widget options.
                 */
                wp_list_categories(apply_filters('widget_categories_args', $cat_args));
                ?>
            </ul>
        <?php
        }

        echo $args['after_widget'];
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['count'] = !empty($new_instance['count']) ? 1 : 0;
        $instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
        $instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
        $instance['excluded_category_ids'] = !empty($new_instance['excluded_category_ids']) ? $new_instance['excluded_category_ids'] : array();

        return $instance;
    }

    public function form($instance)
    {
        //Defaults
        $instance = wp_parse_args((array)$instance, array('title' => ''));
        $title = esc_attr($instance['title']);
        $count = isset($instance['count']) ? (bool)$instance['count'] : false;
        $hierarchical = isset($instance['hierarchical']) ? (bool)$instance['hierarchical'] : false;
        $dropdown = isset($instance['dropdown']) ? (bool)$instance['dropdown'] : false;
        $excluded_categories = is_array($instance['excluded_category_ids']) ? $instance['excluded_category_ids'] : array();
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/></p>

        <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>"
                  name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked($dropdown); ?> />
            <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e('Display as dropdown'); ?></label><br/>

            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>"
                   name="<?php echo $this->get_field_name('count'); ?>"<?php checked($count); ?> />
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show post counts'); ?></label><br/>

            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>"
                   name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked($hierarchical); ?> />
            <label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e('Show hierarchy'); ?></label></p>

        <label
            for="<?php echo $this->get_field_id('excluded_category_ids'); ?>"><?php _e('Select categories to exclude in the categories list:', WPU_Plugin::$textDomain); ?></label>
        <div id="<?php echo $this->get_field_id('excluded_category_ids'); ?>" class="category-container">
            <?php foreach ($this->__getCategories($excluded_categories) as $category): ?>
                <div>
                    <input type="checkbox"
                           id="<?php echo $this->get_field_id('excluded_category_ids'); ?>[<?php echo $category->term_id; ?>]"
                           name="<?php echo $this->get_field_name('excluded_category_ids'); ?>[]"
                           value="<?php echo $category->term_id; ?>" <?php checked($this->__isExcluded($category->term_id, $excluded_categories)) ?> />
                    <label
                        for="<?php echo $this->get_field_id('excluded_category_ids'); ?>[<?php echo $category->term_id; ?>]"><?php echo esc_attr($category->cat_name); ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    <?php
    }

    private function __getCategories($excluded_categories)
    {
        $categories = get_categories('hide_empty=0');

        $sort = function ($a, $b) use ($excluded_categories) {
            $a_checked = false;
            $b_checked = false;

            foreach ($excluded_categories as $excludedId) {
                if ($excludedId == $a->term_id) $a_checked = true;
                else if ($excludedId == $b->term_id) $b_checked = true;
            }

            if ($a_checked && !$b_checked) return -1;
            if (!$a_checked && $b_checked) return 1;

            return $a->term_id - $b->term_id;
        };

        usort($categories, $sort);

        return $categories;
    }

    private function __isExcluded($categoryId, $excluded_categories)
    {
        foreach ($excluded_categories as $excludedId) {
            if ($excludedId == $categoryId) return true;
        }

        return false;
    }
}
