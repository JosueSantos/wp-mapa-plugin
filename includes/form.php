<?php
function mmi_formulario_cadastro() {
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mmi_cadastro_nonce'])) {
        if (wp_verify_nonce($_POST['mmi_cadastro_nonce'], 'mmi_cadastro_igreja')) {
            $post_id = wp_insert_post([
                'post_title'  => sanitize_text_field($_POST['comunidade']),
                'post_type'   => 'igreja',
                'post_status' => 'publish'
            ]);
            if ($post_id) {
                $campos = ['comunidade','paroquia','endereco','latitude','longitude','youtube','instagram','facebook','detalhes','horarios'];
                foreach ($campos as $campo) {
                    if (isset($_POST[$campo])) {
                        update_post_meta($post_id, $campo, sanitize_text_field($_POST[$campo]));
                    }
                }

                if (!empty($_FILES['imagem']['name'])) {
                    $file = $_FILES['imagem'];
                    if ($file['size'] <= 2 * 1024 * 1024) {
                        $allowed_types = ['image/jpeg','image/png','image/gif'];
                        if (in_array($file['type'], $allowed_types)) {
                            require_once(ABSPATH . 'wp-admin/includes/file.php');
                            require_once(ABSPATH . 'wp-admin/includes/media.php');
                            $attachment_id = media_handle_upload('imagem', $post_id);
                            if (!is_wp_error($attachment_id)) set_post_thumbnail($post_id, $attachment_id);
                        }
                    }
                }

                echo "<div class='mmi-success'>✔ Igreja cadastrada com sucesso!</div>";
            } else echo "<div class='mmi-error'>✖ Erro ao cadastrar igreja.</div>";
        }
    }
    ?>

    <style>
    /* Container responsivo */
    .mmi-form { max-width: 700px; margin: auto; padding: 20px; background: #fefefe; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); font-family: Arial, sans-serif; }
    .mmi-form p { margin-bottom: 15px; }
    .mmi-form label { display: block; font-weight: bold; margin-bottom: 5px; color:#333; }
    .mmi-form input[type=text], .mmi-form input[type=url], .mmi-form input[type=file], .mmi-form textarea { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc; transition: border 0.2s; box-sizing: border-box; }
    .mmi-form input:focus, .mmi-form textarea:focus { border-color: #4A90E2; outline: none; box-shadow: 0 0 5px rgba(74,144,226,0.4); }
    .mmi-form button { background: #4A90E2; color: white; padding: 12px 20px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; transition: background 0.3s; }
    .mmi-form button:hover { background: #357ABD; }
    .mmi-form #map-cadastro { height: 400px; border-radius: 12px; margin-bottom: 10px; }
    .mmi-success { color: green; margin-bottom: 15px; font-weight: bold; }
    .mmi-error { color: red; margin-bottom: 15px; font-weight: bold; }
    @media(max-width: 600px){ .mmi-form #map-cadastro { height: 300px; } }
    </style>

    <form method="post" class="mmi-form" enctype="multipart/form-data">
        <?php wp_nonce_field('mmi_cadastro_igreja', 'mmi_cadastro_nonce'); ?>

        <p><label>Nome da Comunidade</label><input type="text" name="comunidade" required></p>
        <p><label>Nome da Paróquia</label><input type="text" name="paroquia"></p>
        <p><label>Endereço</label><input type="text" id="endereco" name="endereco" placeholder="Digite o endereço"></p>
        <div id="map-cadastro"></div>
        <small>Insira um endereço acima e um marcador aparecerá. Clique no mapa se estiver errado.</small>

        <p><label>Latitude</label><input type="text" id="latitude" name="latitude" readonly></p>
        <p><label>Longitude</label><input type="text" id="longitude" name="longitude" readonly></p>

        <p><label>Foto da Igreja (JPG, PNG ou GIF até 2MB)</label><input type="file" name="imagem" accept="image/jpeg,image/png,image/gif"></p>
        <p><label>YouTube</label><input type="url" name="youtube"></p>
        <p><label>Instagram</label><input type="url" name="instagram"></p>
        <p><label>Facebook</label><input type="url" name="facebook"></p>
        <p><label>Detalhes</label><textarea name="detalhes"></textarea></p>
        <p><label>Horário de Missas</label><input type="text" name="horarios"></p>
        <p><button type="submit">Cadastrar Igreja</button></p>
    </form>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var map = L.map('map-cadastro').setView([-15.7797, -47.9297], 4);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
        var marker;
        function setMarker(lat, lng){
            if(marker) map.removeLayer(marker);
            marker = L.marker([lat,lng],{draggable:true}).addTo(map);
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;
            marker.on('dragend',function(e){var pos = marker.getLatLng(); document.getElementById("latitude").value = pos.lat; document.getElementById("longitude").value = pos.lng; });
        }
        document.getElementById("endereco").addEventListener("blur", function(){
            var endereco = this.value;
            if(endereco.length>5){
                fetch("https://nominatim.openstreetmap.org/search?format=json&q="+encodeURIComponent(endereco))
                    .then(resp=>resp.json())
                    .then(data=>{ if(data && data.length>0){ var lat=data[0].lat; var lon=data[0].lon; map.setView([lat,lon],15); setMarker(lat,lon); }});
            }
        });
        map.on('click', function(e){ setMarker(e.latlng.lat,e.latlng.lng); });
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('cadastro_igreja','mmi_formulario_cadastro');
