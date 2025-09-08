<?php
function mmi_add_metaboxes() {
    add_meta_box('mmi_dados', 'Dados da Igreja', 'mmi_render_metabox', 'igreja', 'normal', 'default');
}
add_action('add_meta_boxes', 'mmi_add_metaboxes');

function mmi_render_metabox($post) {
    $fields = [
        'comunidade' => 'Nome da Comunidade',
        'paroquia'   => 'Nome da Paróquia',
        'endereco'   => 'Endereço',
        'latitude'   => 'Latitude',
        'longitude'  => 'Longitude',
        'youtube'    => 'YouTube',
        'instagram'  => 'Instagram',
        'facebook'   => 'Facebook',
        'detalhes'   => 'Detalhes',
        'horarios'   => 'Horário de Missas'
    ];
    foreach ($fields as $key => $label) {
        $value = get_post_meta($post->ID, $key, true);
        echo "<p><label><strong>{$label}</strong><br>";
        echo "<input type='text' name='{$key}' value='".esc_attr($value)."' style='width:100%'></label></p>";
    }
}

function mmi_save_metabox($post_id) {
    if (get_post_type($post_id) != 'igreja') return;
    $keys = ['comunidade','paroquia','endereco','latitude','longitude','youtube','instagram','facebook','detalhes','horarios'];
    foreach ($keys as $key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
        }
    }
}
add_action('save_post', 'mmi_save_metabox');
