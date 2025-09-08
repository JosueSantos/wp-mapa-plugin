<?php
function mmi_formulario_edicao($atts) {
    ob_start();

    // Recebe o ID do post via atributo do shortcode ou query param
    $atts = shortcode_atts(['id' => 0], $atts);
    $post_id = intval($atts['id']);
    if(isset($_GET['editar_igreja'])) $post_id = intval($_GET['editar_igreja']);

    if(!$post_id || !get_post($post_id) || !current_user_can('edit_post', $post_id)){
        return "<div style='color:red'>Você não tem permissão para editar esta igreja ou ela não existe.</div>";
    }

    $post = get_post($post_id);

    // Processa envio do formulário
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mmi_editar_nonce']) && wp_verify_nonce($_POST['mmi_editar_nonce'],'mmi_editar_igreja')) {
        wp_update_post([
            'ID' => $post_id,
            'post_title' => sanitize_text_field($_POST['comunidade'])
        ]);

        $campos = ['paroquia','endereco','latitude','longitude','youtube','instagram','facebook','detalhes','horarios'];
        foreach($campos as $campo){
            if(isset($_POST[$campo])){
                update_post_meta($post_id,$campo,sanitize_text_field($_POST[$campo]));
            }
        }

        // Upload de nova imagem
        if(!empty($_FILES['imagem']['name'])){
            $file = $_FILES['imagem'];
            if($file['size'] <= 2*1024*1024){
                $allowed_types = ['image/jpeg','image/png','image/gif'];
                if(in_array($file['type'],$allowed_types)){
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    require_once(ABSPATH . 'wp-admin/includes/media.php');
                    $attachment_id = media_handle_upload('imagem',$post_id);
                    if(!is_wp_error($attachment_id)) set_post_thumbnail($post_id,$attachment_id);
                }
            }
        }

        echo "<div style='color:green;font-weight:bold;margin-bottom:10px;'>✔ Alterações salvas com sucesso!</div>";
    }

    // Valores atuais
    $comunidade = get_post_meta($post_id,'comunidade',true) ?: $post->post_title;
    $paroquia   = get_post_meta($post_id,'paroquia',true);
    $endereco   = get_post_meta($post_id,'endereco',true);
    $latitude   = get_post_meta($post_id,'latitude',true);
    $longitude  = get_post_meta($post_id,'longitude',true);
    $youtube    = get_post_meta($post_id,'youtube',true);
    $instagram  = get_post_meta($post_id,'instagram',true);
    $facebook   = get_post_meta($post_id,'facebook',true);
    $detalhes   = get_post_meta($post_id,'detalhes',true);
    $horarios   = get_post_meta($post_id,'horarios',true);
    $imagem_url = get_the_post_thumbnail_url($post_id,'medium');

    ?>
    <style>
    .mmi-form { max-width:700px;margin:auto;padding:20px;background:#fefefe;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,0.1); font-family: Arial,sans-serif;}
    .mmi-form p {margin-bottom:15px;}
    .mmi-form label {display:block;font-weight:bold;margin-bottom:5px;color:#333;}
    .mmi-form input[type=text], .mmi-form input[type=url], .mmi-form input[type=file], .mmi-form textarea {width:100%;padding:10px;border-radius:6px;border:1px solid #ccc;transition:border 0.2s;box-sizing:border-box;}
    .mmi-form input:focus, .mmi-form textarea:focus {border-color:#4A90E2;outline:none;box-shadow:0 0 5px rgba(74,144,226,0.4);}
    .mmi-form button {background:#4A90E2;color:white;padding:12px 20px;border:none;border-radius:8px;cursor:pointer;font-size:16px;transition:background 0.3s;}
    .mmi-form button:hover {background:#357ABD;}
    .mmi-form #map-cadastro {height:400px;border-radius:12px;margin-bottom:10px;}
    .mmi-form img {max-width:150px;margin-bottom:10px;border-radius:8px;}
    @media(max-width:600px){.mmi-form #map-cadastro{height:300px;}}
    </style>

    <form method="post" class="mmi-form" enctype="multipart/form-data">
        <?php wp_nonce_field('mmi_editar_igreja','mmi_editar_nonce'); ?>

        <p><label>Nome da Comunidade</label><input type="text" name="comunidade" value="<?php echo esc_attr($comunidade); ?>" required></p>
        <p><label>Nome da Paróquia</label><input type="text" name="paroquia" value="<?php echo esc_attr($paroquia); ?>"></p>
        <p><label>Endereço</label><input type="text" id="endereco" name="endereco" placeholder="Digite o endereço" value="<?php echo esc_attr($endereco); ?>"></p>

        <div id="map-cadastro"></div>
        <small>Arraste o marcador ou clique no mapa para ajustar a localização.</small>

        <p><label>Latitude</label><input type="text" id="latitude" name="latitude" readonly value="<?php echo esc_attr($latitude); ?>"></p>
        <p><label>Longitude</label><input type="text" id="longitude" name="longitude" readonly value="<?php echo esc_attr($longitude); ?>"></p>

        <p><label>Foto da Igreja atual</label>
            <?php if($imagem_url) echo "<img src='$imagem_url' alt='Foto da igreja'>"; ?>
            <input type="file" name="imagem" accept="image/jpeg,image/png,image/gif">
        </p>

        <p><label>YouTube</label><input type="url" name="youtube" value="<?php echo esc_attr($youtube); ?>"></p>
        <p><label>Instagram</label><input type="url" name="instagram" value="<?php echo esc_attr($instagram); ?>"></p>
        <p><label>Facebook</label><input type="url" name="facebook" value="<?php echo esc_attr($facebook); ?>"></p>
        <p><label>Detalhes</label><textarea name="detalhes"><?php echo esc_textarea($detalhes); ?></textarea></p>
        <p><label>Horário de Missas</label><input type="text" name="horarios" value="<?php echo esc_attr($horarios); ?>"></p>
        <p><button type="submit">Salvar Alterações</button></p>
    </form>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var lat = <?php echo $latitude ? $latitude : -15.7797; ?>;
        var lon = <?php echo $longitude ? $longitude : -47.9297; ?>;

        var map = L.map('map-cadastro').setView([lat, lon], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap'}).addTo(map);

        var marker = L.marker([lat, lon], {draggable:true}).addTo(map);

        marker.on('dragend', function(e){
            var pos = marker.getLatLng();
            document.getElementById("latitude").value = pos.lat;
            document.getElementById("longitude").value = pos.lng;
        });

        document.getElementById("endereco").addEventListener("blur", function(){
            var endereco = this.value;
            if(endereco.length>5){
                fetch("https://nominatim.openstreetmap.org/search?format=json&q="+encodeURIComponent(endereco))
                .then(resp=>resp.json())
                .then(data=>{ if(data && data.length>0){ var lat=data[0].lat; var lon=data[0].lon; map.setView([lat,lon],15); marker.setLatLng([lat,lon]); document.getElementById("latitude").value=lat; document.getElementById("longitude").value=lon; }});
            }
        });

        map.on('click', function(e){ marker.setLatLng(e.latlng); document.getElementById("latitude").value=e.latlng.lat; document.getElementById("longitude").value=e.latlng.lng; });
    });
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('editar_igreja','mmi_formulario_edicao');
