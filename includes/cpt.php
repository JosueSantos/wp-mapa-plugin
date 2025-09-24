<?php
function mmi_register_cpt() {
    register_post_type('igreja', array(
        'labels' => array(
            'name' => 'Igrejas',
            'singular_name' => 'Igreja'
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-location',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt')
    ));
}
add_action('init', 'mmi_register_cpt');
