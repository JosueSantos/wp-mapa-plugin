<?php
function mmi_register_rest() {
    register_rest_route('mmi/v1', '/igrejas', array(
        'methods' => 'GET',
        'callback' => 'mmi_get_igrejas',
    ));
}
add_action('rest_api_init', 'mmi_register_rest');

function mmi_get_igrejas() {
    $args = array('post_type' => 'igreja', 'posts_per_page' => -1);
    $posts = get_posts($args);
    $igrejas = [];
    foreach ($posts as $post) {
        $imagem = get_the_post_thumbnail_url($post->ID, 'medium');

        $igrejas[] = [
            'id' => $post->ID,
            'comunidade' => get_post_meta($post->ID, 'comunidade', true),
            'paroquia'   => get_post_meta($post->ID, 'paroquia', true),
            'endereco'   => get_post_meta($post->ID, 'endereco', true),
            'latitude'   => get_post_meta($post->ID, 'latitude', true),
            'longitude'  => get_post_meta($post->ID, 'longitude', true),
            'youtube'    => get_post_meta($post->ID, 'youtube', true),
            'instagram'  => get_post_meta($post->ID, 'instagram', true),
            'facebook'   => get_post_meta($post->ID, 'facebook', true),
            'detalhes'   => get_post_meta($post->ID, 'detalhes', true),
            'horarios'   => get_post_meta($post->ID, 'horarios', true),
            'imagem'     => $imagem
        ];
    }
    return $igrejas;
}
