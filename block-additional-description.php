<?php
/**
 * Block Name: Additional Description
 *
 * Description: A custom block to display the additional description.
 */

$post_id = get_the_ID();
$additional_description = get_post_meta($post_id, 'additional_description', true);
$block_id = 'additional-description-' . $block['id'];
$align_class = $block['align'] ? 'align' . $block['align'] : '';

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
?>

<div id="<?php echo esc_attr($block_id); ?>" class="additional-description hidden-in-iframe maxi-block--use-sc <?php echo esc_attr($align_class); ?>">
    <?php
    if (!empty($additional_description)) {
        $description_content = is_array($additional_description) ? $additional_description[0] : $additional_description;
        $formatted_content = wpautop($description_content); // Apply wpautop()
        echo wp_kses($formatted_content, custom_allowed_html());
    }
?>
</div>
