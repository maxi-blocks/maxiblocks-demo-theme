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

    register_taxonomy('wordpress', array(
        0 => 'post',
    ), array(
        'labels' => array(
            'name' => 'Bases',
            'singular_name' => 'Base',
            'menu_name' => 'Bases',
            'all_items' => 'All Bases',
            'edit_item' => 'Edit Base',
            'view_item' => 'View Base',
            'update_item' => 'Update Base',
            'add_new_item' => 'Add New Base',
            'new_item_name' => 'New Base Name',
            'parent_item' => 'Parent Base',
            'parent_item_colon' => 'Parent Base:',
            'search_items' => 'Search Bases',
            'not_found' => 'No bases found',
            'no_terms' => 'No bases',
            'filter_by_item' => 'Filter by base',
            'items_list_navigation' => 'Bases list navigation',
            'items_list' => 'Bases list',
            'back_to_items' => '← Go to bases',
            'item_link' => 'Base Link',
            'item_link_description' => 'A link to a base',
        ),
        'public' => true,
        'hierarchical' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'show_tagcloud' => false,
        'show_admin_column' => true,
        'rewrite' => array(
            'with_front' => false,
            'hierarchical' => true,
        ),
    ));
}

function custom_pattern_base_permalink($permalink, $post, $leavename)
{
    if (strpos($permalink, '%pattern_base%') !== false) {
        $terms = get_the_terms($post->ID, 'pattern_base');
        if ($terms && !is_wp_error($terms)) {
            $term = array_shift($terms); // Get the first term (assuming one term for simplicity)
            $term_hierarchy = array();

            // Add the term itself to the hierarchy first
            $term_hierarchy[] = $term->slug;

            // Traverse up the hierarchy to get the full term path
            while ($term->parent != 0) {
                $parent_term = get_term($term->parent, 'pattern_base');
                $term_hierarchy[] = $parent_term->slug;
                $term = $parent_term;
            }

            $term_hierarchy = array_reverse($term_hierarchy); // Reverse to get the correct order

            $full_term_slug = implode('/', $term_hierarchy); // Join terms into a path

            // Check for double slashes or empty segments and remove them
            $full_term_slug = trim($full_term_slug, '/');

            // Replace the %pattern_base% with the full hierarchy path
            $permalink = str_replace('%pattern_base%', $full_term_slug, $permalink);
        } else {
            // If no term is assigned, remove %pattern_base% from the URL
            $permalink = str_replace('%pattern_base%/', '', $permalink);
        }
    }
    return $permalink;
}
add_filter('post_link', 'custom_pattern_base_permalink', 10, 3);
add_filter('post_type_link', 'custom_pattern_base_permalink', 10, 3);

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
    if (strpos($src, '?ver=')) {
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
        $final_content = str_replace('https://maxiblocks.com/demo/wp-content/uploads', 'https://img.maxiblocks.com', $content);
        $final_content = str_replace('https://site-editor-demo.maxiblocks.com/wp-content/uploads', 'https://img.maxiblocks.com', $final_content);
        return $final_content;
    }
    add_filter('the_content', 'replace_image_urls_in_content');

    function replace_image_srcset_urls($sources)
    {
        foreach ($sources as &$source) {
            $source['url'] = str_replace('https://maxiblocks.com/demo/wp-content/uploads', 'https://img.maxiblocks.com', $source['url']);
            $source['url'] = str_replace('https://site-editor-demo.maxiblocks.com/wp-content/uploads', 'https://img.maxiblocks.com', $source['url']);
        }
        return $sources;
    }
    add_filter('wp_calculate_image_srcset', 'replace_image_srcset_urls');
}

if (is_admin()) {
    function rewrite_image_url($url)
    {
        $final_url = str_replace('https://maxiblocks.com/demo/wp-content/uploads', 'https://img.maxiblocks.com', $url);
        $final_url = str_replace('https://site-editor-demo.maxiblocks.com/wp-content/uploads', 'https://img.maxiblocks.com', $final_url);
        return $final_url;
    }
    add_filter('wp_get_attachment_url', 'rewrite_image_url');
}

function custom_post_link($permalink, $post)
{
    // Only modify permalinks for the 'post' post type
    if ($post->post_type != 'post') {
        return $permalink;
    }

    // Get the terms from the "wordpress-patterns" taxonomy
    $terms = wp_get_post_terms($post->ID, 'wordpress');

    if (!empty($terms) && !is_wp_error($terms)) {
        $term = $terms[0];
        $term_slugs = [];

        // Traverse the taxonomy hierarchy to build the path
        while ($term->parent != 0) {
            $parent = get_term($term->parent, 'wordpress');
            array_unshift($term_slugs, $parent->slug);
            $term = $parent;
        }
        $term_slugs[] = $terms[0]->slug;

        $taxonomy_path = '/' . implode('/', $term_slugs);

        // Modify the permalink to include the taxonomy path
        $permalink = home_url('/' . $taxonomy_path . '/' . $post->post_name . '/');
    }

    return $permalink;
}
add_filter('post_link', 'custom_post_link', 10, 2);

function custom_taxonomy_redirect()
{
    // Get the current request URI
    $requested_url = $_SERVER['REQUEST_URI'];

    // Check if the URL matches the pattern for the old structure for 'patterns', 'themes', or 'website-templates'
    if (preg_match('#^/(patterns|themes|website-templates)/([^/]+/)*[^0-9]+/?$#', $requested_url)) {
        // Replace the old segment with 'wordpress/<segment>'
        $new_url = preg_replace('#^/(patterns|themes|website-templates)/#', '/wordpress/$1/', $requested_url);

        // Issue the redirect (301 - permanent redirect)
        wp_redirect(home_url($new_url), 301);
        exit;
    }
}
add_action('template_redirect', 'custom_taxonomy_redirect');

// Add WYSIWYG Editor to Taxonomy Description
function add_wysiwyg_to_taxonomy_description()
{
    if (! function_exists('use_block_editor_for_post_type')) {
        return;
    }

    if (use_block_editor_for_post_type('post')) {
        add_action('wordpress_edit_form_fields', 'render_wysiwyg_editor_for_taxonomy', 10, 2);
        add_action('admin_footer', 'hide_default_description_field');
    }
}
add_action('admin_init', 'add_wysiwyg_to_taxonomy_description');

function render_wysiwyg_editor_for_taxonomy($term, $taxonomy)
{
    wp_enqueue_editor();
    ?>
    <tr class="form-field term-description-wrap">
        <th scope="row" valign="top"><label for="description"><?php _e('Description', 'maxiblocks-demo-theme'); ?></label></th>
        <td>
            <?php
            $settings = array(
                'textarea_name' => 'custom_description',
                'textarea_rows' => 10,
                'editor_height' => 300,
            );
    wp_editor(html_entity_decode($term->description), 'custom_description', $settings);
    ?>
            <p class="description"><?php _e('The description is not prominent by default; however, some themes may show it.', 'maxiblocks-demo-theme'); ?></p>
        </td>
    </tr>
    <?php
}

// Hide the default description field
function hide_default_description_field()
{
    ?>
    <style>
       body.taxonomy-wordpress .term-description-wrap:not(:has(#custom_description)),
	   body.taxonomy-wordpress .term-description-wrap:last-child{
            display: none !important;
        }
		body.taxonomy-wordpress form#edittag {
			max-width: 100%;
		}
    </style>
    <?php
}

// Define custom allowed HTML tags and attributes
function custom_allowed_html()
{
    return array(
        'a' => array(
            'href' => array(),
            'title' => array(),
            'target' => array(),
            'rel' => array(),
        ),
        'b' => array(),
        'blockquote' => array(
            'cite' => array(),
        ),
        'br' => array(),
        'div' => array(
            'class' => array(),
            'id' => array(),
            'style' => array(),
        ),
        'em' => array(),
        'h1' => array(),
        'h2' => array(),
        'h3' => array(),
        'h4' => array(),
        'h5' => array(),
        'h6' => array(),
        'i' => array(),
        'img' => array(
            'alt' => array(),
            'class' => array(),
            'height' => array(),
            'src' => array(),
            'width' => array(),
        ),
        'li' => array(
            'class' => array(),
        ),
        'ol' => array(
            'class' => array(),
        ),
        'p' => array(
            'class' => array(),
        ),
        'span' => array(
            'class' => array(),
            'style' => array(),
        ),
        'strong' => array(),
        'ul' => array(
            'class' => array(),
        ),
        'code' => array(),
        'pre' => array(),
        'table' => array(
            'class' => array(),
            'style' => array(),
        ),
        'thead' => array(),
        'tbody' => array(),
        'tr' => array(),
        'th' => array(
            'scope' => array(),
        ),
        'td' => array(
            'colspan' => array(),
        ),
    );
}

// Save the WYSIWYG Editor Content
function save_wysiwyg_editor_content($term_id)
{
    if (isset($_POST['custom_description'])) {
        $description = wp_kses($_POST['custom_description'], custom_allowed_html());
        add_filter('pre_insert_term', function ($term, $taxonomy) use ($description) {
            if ($taxonomy === 'wordpress') {
                $term['description'] = $description;
            }
            return $term;
        }, 10, 2);
    }
}
add_action('edited_wordpress', 'save_wysiwyg_editor_content');

// Update term description in database directly
function update_term_description($term_id, $tt_id, $taxonomy)
{
    if ($taxonomy === 'wordpress' && isset($_POST['custom_description'])) {
        global $wpdb;
        $description = wp_kses($_POST['custom_description'], custom_allowed_html());
        $wpdb->update(
            $wpdb->term_taxonomy,
            array('description' => $description),
            array('term_id' => $term_id, 'taxonomy' => $taxonomy)
        );
    }
}
add_action('edited_term', 'update_term_description', 10, 3);

function add_custom_fields_to_taxonomy()
{
    if (!function_exists('use_block_editor_for_post_type')) {
        return;
    }

    if (use_block_editor_for_post_type('post')) {
        add_action('wordpress_edit_form_fields', 'render_custom_fields_for_taxonomy', 10, 2);
        add_action('edited_wordpress', 'save_custom_fields_for_taxonomy', 10, 2);
        add_action('admin_footer', 'hide_default_description_field');

        // Register meta fields for REST API
        register_term_meta('wordpress', 'seo_header', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ));
        register_term_meta('wordpress', 'subheader', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ));
    }
}
add_action('admin_init', 'add_custom_fields_to_taxonomy');

function render_custom_fields_for_taxonomy($term, $taxonomy)
{
    wp_enqueue_editor();
    $seo_header = get_term_meta($term->term_id, 'seo_header', true);
    $subheader = get_term_meta($term->term_id, 'subheader', true);
    ?>
    <tr class="form-field">
        <th scope="row"><label for="seo_header"><?php _e('SEO Header', 'maxiblocks-demo-theme'); ?></label></th>
        <td>
            <?php
            $seo_header_settings = array(
                'textarea_name' => 'seo_header',
                'textarea_rows' => 5,
                'editor_height' => 150,
            );
    wp_editor(html_entity_decode($seo_header), 'seo_header', $seo_header_settings);
    ?>
            <p class="description"><?php _e('Enter a SEO Header for this term.', 'maxiblocks-demo-theme'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="subheader"><?php _e('Subheader', 'maxiblocks-demo-theme'); ?></label></th>
        <td>
            <?php
    $subheader_settings = array(
        'textarea_name' => 'subheader',
        'textarea_rows' => 5,
        'editor_height' => 150,
    );
    wp_editor(html_entity_decode($subheader), 'subheader', $subheader_settings);
    ?>
            <p class="description"><?php _e('Enter a subheader for this term.', 'maxiblocks-demo-theme'); ?></p>
        </td>
    </tr>
    <?php
}

function save_custom_fields_for_taxonomy($term_id, $tt_id)
{
    if (isset($_POST['seo_header'])) {
        update_term_meta($term_id, 'seo_header', wp_kses_post($_POST['seo_header']));
    }
    if (isset($_POST['subheader'])) {
        update_term_meta($term_id, 'subheader', wp_kses_post($_POST['subheader']));
    }
    if (isset($_POST['custom_description'])) {
        $description = wp_kses($_POST['custom_description'], custom_allowed_html());
        global $wpdb;
        $wpdb->update(
            $wpdb->term_taxonomy,
            array('description' => $description),
            array('term_id' => $term_id, 'taxonomy' => 'wordpress')
        );
    }
}

function register_custom_taxonomy_fields_block()
{
    register_block_type('maxiblocks-demo-theme/custom-taxonomy-fields', array(
        'render_callback' => 'render_custom_taxonomy_fields_block',
        'attributes' => array(
            'field' => array(
                'type' => 'string',
                'default' => 'seo_header',
            ),
        ),
    ));
}
add_action('init', 'register_custom_taxonomy_fields_block');

function render_custom_taxonomy_fields_block($attributes)
{
    $term = get_queried_object();
    if (!$term || !isset($term->term_id)) {
        return '';
    }

    $field = $attributes['field'];
    $value = get_term_meta($term->term_id, $field, true);

    if (empty($value)) {
        return '';
    }

    return '<div class="custom-taxonomy-field ' . esc_attr($field) . '">' . wp_kses_post($value) . '</div>';
}
