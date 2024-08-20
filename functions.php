<?php

/**
 * MaxiBlocks Demo functions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package MaxiBlocks_Library
 */

/**
 * Library API
 */
require get_template_directory() . '/inc/core.php';

function patterns_custom_taxonomies()
{
    // Add new "Light or Dark" taxonomy to Posts
    register_taxonomy('light_or_dark', 'post', array(
      // Hierarchical taxonomy (like categories)
      'hierarchical' => false,
      'show_in_rest' => true,
      'show_ui'      => true,
      'show_admin_column' => 'true',
      'show_in_quick_edit' => true,
  
      // This array of options controls the labels displayed in the WordPress Admin UI
      'labels' => array(
        'name' => _x('Light or Dark', 'taxonomy general name'),
        'singular_name' => _x('Light or Dark', 'taxonomy singular name'),
        'search_items' =>  __('Search Light or Dark'),
        'all_items' => __('All Light and Dark'),
        'parent_item' => __('Parent Light or Dark'),
        'parent_item_colon' => __('Parent Light or Dark:'),
        'edit_item' => __('Edit Light or Dark'),
        'update_item' => __('Update Light or Dark'),
        'add_new_item' => __('Add New Light or Dark'),
        'new_item_name' => __('New Light or Dark Name'),
        'menu_name' => __('Light or Dark'),
      ),
      // Control the slugs used for this taxonomy
      'rewrite' => array(
        'slug' => 'tone', // This controls the base slug that will display before each term
        'with_front' => false, // Don't display the category base before "/locations/"
        'hierarchical' => true, // This will allow URL's like "/locations/boston/cambridge/"
      ),
    ));
    
    register_taxonomy('cost', 'post', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => false,
        'show_in_rest' => true,
        'show_ui'      => true,
        'show_admin_column' => 'true',
        'show_in_quick_edit' => true,

        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
            'name' => _x('Cost', 'taxonomy general name'),
            'singular_name' => _x('Cost', 'taxonomy singular name'),
            'search_items' =>  __('Search by Cost'),
            'all_items' => __('All Costs'),
            'parent_item' => __('Parent Cost'),
            'parent_item_colon' => __('Parent Cost:'),
            'edit_item' => __('Edit Cost'),
            'update_item' => __('Update Cost'),
            'add_new_item' => __('Add New Cost'),
            'new_item_name' => __('New Cost Name'),
            'menu_name' => __('Cost'),
            ),
            // Control the slugs used for this taxonomy
            'rewrite' => array(
            'slug' => 'library', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true, // This will allow URL's like "/locations/boston/cambridge/"
            //'show_ui'      => true,
            'show_admin_column' => 'true',
            'show_in_quick_edit' => true,
        ),
    ));
}

function cloud_restrict_manage_posts()
{
    global $typenow;
    $taxonomy = 'light_or_dark';
    if ($typenow == 'post') {
        $filters = array($taxonomy);
        foreach ($filters as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            $terms = get_terms($tax_slug);
            echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
            echo "<option value=''>All $tax_name</option>";
            foreach ($terms as $term) {
                echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
            }
            echo "</select>";
        }
    }
}

add_action('init', 'patterns_custom_taxonomies', 0);
add_action('restrict_manage_posts', 'cloud_restrict_manage_posts');

add_theme_support('post-thumbnails');

//Register Meta Box
function link_to_related_register_meta_box()
{
    add_meta_box('link-to-related-meta-box-id', esc_html__('ID of related tone variant', 'text-domain'), 'link_to_related_meta_box_callback', 'post', 'side', 'high');
}
add_action('add_meta_boxes', 'link_to_related_register_meta_box');

//Register Meta Box
function maxi_version_register_meta_box()
{
    add_meta_box('maxi-version-meta-box-id', esc_html__('Maxi version', 'text-domain'), 'maxi_version_meta_box_callback', 'post', 'side', 'high');
}
add_action('add_meta_boxes', 'maxi_version_register_meta_box');

//Add field
function link_to_related_meta_box_callback($post)
{
    $outline = '<label for="link_to_related">'. esc_html__('The ID of related pattern with the opposite tone - light or dark', 'text-domain') .'</label>';
    $link_to_related =  get_post_meta($post->ID, 'link_to_related', true);
    wp_nonce_field('save_link_to_related', 'link_to_related_nonce');
    
    $outline .= '<input type="text" name="link_to_related" id="link_to_related" class="link_to_related" value="'. esc_attr($link_to_related) .'"/>';
 
    echo $outline;
}

//Add field
function maxi_version_meta_box_callback($post)
{
    $outline = '<label for="maxi_version">'. esc_html__('Maxi plugin version the item was created in', 'text-domain') .'</label>';
    $maxi_version =  get_post_meta($post->ID, 'maxi_version', true);
    wp_nonce_field('save_maxi_version', 'maxi_version_nonce');
    
    $outline .= '<input type="text" name="maxi_version" id="maxi_version" class="maxi_version" value="'. esc_attr($maxi_version) .'"/>';
 
    echo $outline;
}

// Save your meta box content
add_action('save_post', 'save_link_to_related');
add_action('save_post', 'save_maxi_version');

function save_link_to_related($post_id)
{

    // Check if nonce is set
    if (! isset($_POST['link_to_related_nonce'])) {
        return $post_id;
    }

    if (! wp_verify_nonce($_POST['link_to_related_nonce'], 'save_link_to_related')) {
        return $post_id;
    }

    // Check that the logged in user has permission to edit this post
    if (! current_user_can('edit_post')) {
        return $post_id;
    }

    $link_to_related = sanitize_text_field($_POST['link_to_related']);
    update_post_meta($post_id, 'link_to_related', $link_to_related);
}

function save_maxi_version($post_id)
{

    // Check if nonce is set
    if (! isset($_POST['maxi_version_nonce'])) {
        return $post_id;
    }

    if (! wp_verify_nonce($_POST['maxi_version_nonce'], 'save_maxi_version')) {
        return $post_id;
    }

    // Check that the logged in user has permission to edit this post
    if (! current_user_can('edit_post')) {
        return $post_id;
    }

    $maxi_version = sanitize_text_field($_POST['maxi_version']);
    update_post_meta($post_id, 'maxi_version', $maxi_version);
}

add_action('init', 'maxi_register_post_meta');

function maxi_register_post_meta()
{
    $post_type = 'post';
    register_post_meta($post_type, 'link_to_related', [
        'auth_callback' => function () {
            return current_user_can('edit_posts');
        },
        'sanitize_callback' => 'sanitize_textarea_field',
        'show_in_rest'      => true,
        'single'            => true,
        'type'              => 'string',
    ]);
}

add_action('init', 'maxi_register_post_meta_maxi_version');

function maxi_register_post_meta_maxi_version()
{
    $post_type = 'post';
    
    if (! function_exists('get_plugin_data')) {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }

    $maxi_plugin_info = get_plugin_data(WP_PLUGIN_DIR  . '/maxi-blocks/plugin.php');

    if (!empty($maxi_plugin_info)) {
        $maxi_plugin_version = $maxi_plugin_info['Version'];
    }

    register_post_meta($post_type, 'maxi_version', [
        'auth_callback' => function () {
            return current_user_can('edit_posts');
        },
        'sanitize_callback' => 'sanitize_textarea_field',
        'show_in_rest'      => true,
        'single'            => true,
        'type'              => 'string',
        'default'           => $maxi_plugin_version,

    ]);
}
// Remove query string from static resources
function remove_cssjs_ver($src)
{
    if(strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

add_filter('style_loader_src', 'remove_cssjs_ver', 10, 2);
add_filter('script_loader_src', 'remove_cssjs_ver', 10, 2);

// Disable WordPress image compression
add_filter('wp_editor_set_quality', function ($arg) {
    return 100;
});

function enqueue_roboto_font()
{
    wp_enqueue_style('roboto-font', 'https://fonts.bunny.net/css?family=roboto:300,400,500,700&display=swap');
}
add_action('wp_enqueue_scripts', 'enqueue_roboto_font');

if (!is_admin()) {
    function replace_image_urls_in_content($content)
    {
        return str_replace('https://maxiblocks.com/demo/wp-content/uploads', 'https://img.maxiblocks.com', $content);
    }
    add_filter('the_content', 'replace_image_urls_in_content');

    function replace_image_srcset_urls($sources)
    {
        foreach ($sources as &$source) {
            $source['url'] = str_replace('https://maxiblocks.com/demo/wp-content/uploads', 'https://img.maxiblocks.com', $source['url']);
        }
        return $sources;
    }
    add_filter('wp_calculate_image_srcset', 'replace_image_srcset_urls');
}

if (is_admin()) {
    function rewrite_image_url($url)
    {
        return str_replace('https://maxiblocks.com/demo/wp-content/uploads', 'https://img.maxiblocks.com', $url);
    }
    add_filter('wp_get_attachment_url', 'rewrite_image_url');
}
