<?php

/**
 * MaxiBlocks Library Core
 *
 * @package MaxiBlocks_Library
 *
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

define('THEME_VERSION', '1.0.0');
define('THEME_DIR', realpath(get_template_directory(__FILE__)) . DIRECTORY_SEPARATOR);
define('THEME_URL', get_template_directory_uri(__FILE__));
define('THEME_FILE', __FILE__);

if (!class_exists('MaxiBlocks_Library_Core')) :
    /**
     * Main MaxiBlocks_Library_Core Class
     *
     * @since 1.0.0
     */
    class MaxiBlocks_Library_Core
    {
        /**
         * This plugin's instance.
         *
         * @var MaxiBlocks_Library_Core
         */
        protected static $instance;

        /**
         * Registers the plugin.
         */
        public static function register()
        {
            if (null === self::$instance) {
                self::$instance = new MaxiBlocks_Library_Core();
            }
        }

        /**
         * Constructor
         */
        public function __construct()
        {
            // General setup
            add_action('after_setup_theme', [$this, 'maxiblocks_library_setup']);

            // Content width
            add_action('after_setup_theme', [$this, 'maxiblocks_library_content_width'], 0);

            // Enqueue scripts
            add_action('wp_enqueue_scripts', [$this, 'maxiblocks_library_scripts']);

            // Enqueue scripts for admin
            add_action('admin_enqueue_scripts', [$this, 'maxiblocks_library_admin_scripts']);
        }

        /**
         * Sets up theme defaults and registers support for various WordPress features.
         *
         * Note that this function is hooked into the after_setup_theme hook, which
         * runs before the init hook. The init hook is too late for some features, such
         * as indicating support for post thumbnails.
         */
        public function maxiblocks_library_setup()
        {
            /*
            * Make theme available for translation.
            * Translations can be filed in the /languages/ directory.
            * If you're building a theme based on MaxiBlocks Library, use a find and replace
            * to change 'maxiblocks-library' to the name of your theme in all the template files.
            */
            load_theme_textdomain('maxiblocks-library', get_template_directory() . '/languages');

            /*
            * Let WordPress manage the document title.
            * By adding theme support, we declare that this theme does not use a
            * hard-coded <title> tag in the document head, and expect WordPress to
            * provide it for us.
            */
            add_theme_support('title-tag');

            /*
            * Switch default core markup for search form, comment form, and comments
            * to output valid HTML5.
            */
            add_theme_support(
                'html5',
                array(
                    'search-form',
                    'comment-form',
                    'comment-list',
                    'gallery',
                    'caption',
                    'style',
                    'script',
                )
            );
        }

        /**
         * Set the content width in pixels, based on the theme's design and stylesheet.
         *
         * Priority 0 to make it available to lower priority callbacks.
         *
         * @global int $content_width
         */
        public function maxiblocks_library_content_width()
        {
            $GLOBALS['content_width'] = apply_filters('maxiblocks_library_content_width', 640);
        }

        /**
         * Enqueue scripts and styles.
         */
        public function maxiblocks_library_scripts()
        {
            wp_enqueue_style('maxiblocks-library-style', get_stylesheet_uri(), array(), THEME_VERSION);
            wp_style_add_data('maxiblocks-library-style', 'rtl', 'replace');

            wp_enqueue_script('maxiblocks-library-navigation', get_template_directory_uri() . '/js/navigation.js', array(), THEME_VERSION, true);
        }

        public function maxiblocks_library_admin_scripts()
        {
            wp_enqueue_script('maxiblocks-library-admin', get_template_directory_uri() . '/js/admin.js', array(), THEME_VERSION, true);
        }
    }

endif;

MaxiBlocks_Library_Core::register();
