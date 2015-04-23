<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 3/14/2015
 * Time: 1:06 PM
 * Original: https://gist.github.com/hlashbrooke/9267467
 */

/**
 * Provides a class that manages the options.
 */
class WPU_Admin
{
    private $optionGroup;
    private $pageName;

    public function __construct()
    {
        $this->optionGroup = 'wpu_options';
        $this->pageName = 'wpu_settings';

        // Initialize settings
        add_action('admin_init', array($this, 'onInitialize'));

        // Add settings page to menu
        add_action('admin_menu', array($this, 'onAddMenuItem'));

        // Add settings link to plugins page
        add_filter('plugin_action_links_' . WPU_Plugin::current()->get_baseName(), array($this, 'onAddSettingsLink'));
    }

    /**
     * Automatically called by WordPress when the user is accessing the admin area.
     */
    public function onInitialize()
    {
        $this->__registerSettings();
    }

    /**
     * Automatically called by WordPress when the menu pages need to be set up.
     */
    public function onAddMenuItem()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));

        $page = add_menu_page(__('WordPress Utilities: ', WPU_Plugin::$textDomain) . __('Settings', WPU_Plugin::$textDomain), __('WP Utilities', WPU_Plugin::$textDomain), 'manage_options', $this->pageName, array($this, 'renderPage'));
        add_action('admin_print_styles-' . $page, array($this, 'onEnqueuePageScripts'));
    }

    public function enqueueScripts()
    {
        wp_register_style('wpu-admin-style', WPU_Plugin::current()->resolveUrl('admin/css/admin.css'), array(), WPU_Plugin::current()->get_version());
        wp_enqueue_style('wpu-admin-style');
    }

    /**
     * Called by WordPress when stylesheets and scripts can be queued.
     */
    public function onEnqueuePageScripts()
    {
        wp_register_script('wpu-admin', WPU_Plugin::current()->resolveUrl('admin/js/admin.js'), array('jquery'), WPU_Plugin::current()->get_version());
        wp_enqueue_script('wpu-admin');
    }

    public function onAddSettingsLink($links)
    {
        $link = WPU_String::format('<a href="admin.php?page={0}">{1}</a>', array($this->get_pageName(), __('Settings', WPU_Plugin::$textDomain)));

        array_push($links, $link);
        return $links;
    }

    private function __registerSettings()
    {
        $options = WPU_Plugin::current()->get_options();

        register_setting($this->optionGroup, $options->get_name());

        $optionDefinitions = $options->get_optionDefinitions();
        if (is_array($optionDefinitions)) {
            foreach ($optionDefinitions as $section => $data) {
                // Add the section
                add_settings_section($section, $data['title'], array($this, 'renderSection'), $this->pageName);

                // For each section, add the fields.
                foreach ($data['options'] as $option) {
                    add_settings_field($option['name'], $option['label'], array($this, 'renderField'), $this->pageName, $section, array('section' => $section, 'option' => $option));
                }
            }
        }
    }

    public function renderSection($args)
    {
        $optionDefinitions = WPU_Plugin::current()->get_options()->get_optionDefinitions();

        $section = $optionDefinitions[$args['id']];

        echo WPU_String::format('<p>{0}</p>' . "\n", array($section['description']));
    }

    /**
     * Called by WordPress when the fields need to be rendered.
     * @param $args array Parameters specified with the add_settings_field method.
     */
    public function renderField($args)
    {
        $section = $args['section'];
        $option = $args['option'];

        $options = WPU_Plugin::current()->get_options();

        $id = esc_attr($section . '.' . $option['name']);
        $name = esc_attr($options->get_name() . '[' . $section . '][' . $option['name'] . ']');
        $value = $options->getValue($section, $option['name']);

        switch ($option['type']) {
            case WPU_DataType::Text:
                WPU_TextRenderer::render($id, $name, $value, $option);
                break;

            case WPU_DataType::Integer:
                WPU_NumericRenderer::render($id, $name, $value, $option);
                break;

            case WPU_DataType::Checkbox:
                WPU_CheckboxRenderer::render($id, $name, $value, $option);
                break;
        }
    }

    public function validate_field($data)
    {
        if ($data && strlen($data) > 0 && $data != '') {
            $data = urlencode(strtolower(str_replace(' ', '-', $data)));
        }

        return $data;
    }

    /**
     * Called by WordPress when the settings page needs to be rendered.
     */
    public function renderPage()
    {
        require_once WPU_Plugin::current()->get_pluginDir() . 'admin/pages/settings.php';
    }

    /**
     * Gets the name of the option group.
     * @return string The name of the option group.
     */
    public function get_optionGroup()
    {
        return $this->optionGroup;
    }

    /**
     * Gets the name of the page.
     * @return string The name of the page.
     */
    public function get_pageName()
    {
        return $this->pageName;
    }
}
