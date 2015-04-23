<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 3/14/2015
 * Time: 1:06 PM
 */

/**
 * Represents the main plugin class.
 */
class WPU_Plugin
{
    public static $textDomain = 'wp-wordpress-utilities';
    private $pluginDir;
    private $baseName;
    private $file;
    private $options;
    private $admin;

    /**
     * Initializes a new instance of the WPU_Plugin class.
     * @param $file string The full path to the main file of the plugin.
     */
    public function __construct($file)
    {
        $this->pluginDir = plugin_dir_path($file);
        $this->baseName = plugin_basename($file);
        $this->file = $file;

        register_activation_hook($this->file, array('WPU_Plugin', 'onActivate'));
        register_deactivation_hook($this->file, array('WPU_Plugin', 'onDeactivate'));

        if (!defined('WP_INSTALLING') || WP_INSTALLING == false) {
            add_action('plugins_loaded', array($this, 'onPluginsLoaded'));
            add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
        }

        PucFactory::buildUpdateChecker(
            'https://cdn.rawgit.com/maartenkools/wordpress/master/updater/wp-wordpress-utilities.json',
            $this->file,
            'wp-wordpress-utilities'
        );
    }

    /**
     * Gets the current instance of the WPU_Plugin class.
     * @return WPU_Plugin The current instance of the WPU_Plugin class, if initialized; otherwise, null.
     */
    public static function current()
    {
        if (!isset($GLOBALS['wpu_plugin'])) return null;

        return $GLOBALS['wpu_plugin'];
    }

    /**
     * Called by WordPress when the plugin is activated.
     */
    public static function onActivate()
    {
        do_action('wpu_activate');
    }

    /**
     * Called by WordPress when the plugin is deactivated.
     */
    public static function onDeactivate()
    {
        do_action('wpu_deactivate');
    }

    /**
     * Called by WordPress when the activated plugins have been loaded.
     */
    public function onPluginsLoaded()
    {
        load_plugin_textdomain(self::$textDomain, false, dirname($this->get_baseName()) . '/languages/');

        $this->options = new WPU_Options();

        if (is_admin()) {
            $this->admin = new WPU_Admin();
        }

        add_filter('wp_headers', array($this, 'onBeforeHeadersSent'));

        $this->__addShortCodes();
        $this->__registerWidgets();

        require_once $this->get_pluginDir() . 'inc/wpu-functions.php';
    }

    /**
     * Called by WordPress before the headers are sent to the browser.
     * @param $headers array The list of headers to be sent.
     * @return array The list of headers to be sent.
     */
    public function onBeforeHeadersSent($headers)
    {
        if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {
            $headers['X-UA-Compatible'] = 'IE=edge,chrome=1';
        }

        return $headers;
    }

    /**
     * Called by WordPress when scripts and stylesheets may be queued.
     */
    public function enqueueScripts()
    {
        // Stylesheets
        wp_register_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array(), '4.3.0');

        wp_register_style('slick', $this->resolveUrl('slick/slick.css'), array(), $this->get_version());
        wp_register_style('lightbox', $this->resolveUrl('lightbox/lightbox.css'), array(), $this->get_version());
        wp_register_style('icons', $this->resolveUrl('css/icons.css'), array('font-awesome'), $this->get_version());

        wp_enqueue_style('wpu-style', $this->resolveUrl('css/style.css'), array(
            'slick',
            'lightbox',
            'icons'
        ), $this->get_version());

        // Scripts
        wp_register_script('slick', $this->resolveUrl('slick/slick.min.js'), array('jquery'), $this->get_version());
        wp_register_script('jquery.centertoparent', $this->resolveUrl('js/jquery.centertoparent.min.js'), array('jquery'), $this->get_version());
        wp_register_script('lightbox', $this->resolveUrl('lightbox/lightbox.min.js'), array('jquery'), $this->get_version());

        wp_register_script('wpu-gallery', $this->resolveUrl('js/gallery.min.js'), array(
            'jquery',
            'slick',
            'jquery.centertoparent',
            'lightbox'
        ), $this->get_version());

        wp_register_script('wpu-plugin', $this->resolveUrl('js/plugin.min.js'), array('wpu-gallery'), $this->get_version());

        wp_enqueue_script('wpu-plugin');

        // Script localizations/settings
        wp_localize_script('wpu-gallery', 'settings', array(
            'autoplaySpeed' => $this->get_options()->getValue('gallery', 'autoplay_speed')
        ));

        do_action('wpu_enqueue_scripts');
    }

    /**
     * Returns the absolute url of the specified file.
     * @param $file string The path to a file relative to the root directory of this plugin.
     * @return string The absolute url to the specified file.
     */
    public function resolveUrl($file)
    {
        if ($this->get_options()->getValue('general', 'debug')) {
            // Plugin is running in debug mode
            if (preg_match('/.min.js|.min.css$/', $file)) {
                $f = str_replace('.min.', '.', $file);
                if (file_exists($this->get_pluginDir() . $f)) {
                    $file = $f;
                }
            }
        }

        return plugins_url($file, $this->file);
    }

    private function __addShortCodes()
    {
        add_shortcode('wpu_gallery', array('WPU_Gallery','render'));
        add_shortcode('wpu_icon', array('WPU_Icon', 'render'));
        add_shortcode('wpu_single_post', array('WPU_SinglePost', 'render'));

        do_action('wpu_add_shortcodes');
    }

    private function __registerWidgets()
    {
        add_action('widgets_init', array('WPU_RecentPostsWidget', 'register'));
        add_action('widgets_init', array('WPU_CategoriesWidget', 'register'));
    }

    /**
     * Gets the filesystem directory path (with trailing slash) of the plugin.
     * @return string The file system directory path of the plugin.
     */
    public function get_pluginDir()
    {
        return $this->pluginDir;
    }

    /**
     * Gets the path to the main plugin file relative to the plugins directory, without the leading and trailing slashes.
     * @return string The path to the main plugin file relative to the plugins directory.
     */
    public function get_baseName()
    {
        return $this->baseName;
    }

    /**
     * Gets the version of the plugin.
     * @return string The version of the plugin.
     */
    public function get_version()
    {
        return '<wp-major>.<wp-minor>.<build>.<revision>';
    }

    /**
     * Gets the WPU_Options instance for this plugin.
     * @return WPU_Options The options for this plugin.
     */
    public function get_options()
    {
        return $this->options;
    }

    /**
     * Gets the WPU_Admin instance for this plugin.
     * @return WPU_Admin The admin instance for this plugin. If the currently logged on user is not accessing the admin area, this property returns null.
     */
    public function get_admin()
    {
        return $this->admin;
    }
} 
