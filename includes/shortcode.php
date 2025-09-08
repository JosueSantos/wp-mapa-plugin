<?php
function mmi_shortcode_mapa() {
    ob_start(); ?>
    <div id="mapa-igrejas" style="height:500px;"></div>
    <script>
    document.addEventListener("DOMContentLoaded", function(){
        var map = L.map('mapa-igrejas').setView([-15.7797, -47.9297], 4);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var churchIcon = L.icon({
            iconUrl: "<?php echo MMI_URL . 'assets/church.png'; ?>",
            iconSize: [32, 37],
            iconAnchor: [16, 37],
            popupAnchor: [0, -28]
        });

        var nearbyChurchIcon = L.icon({
            iconUrl: "https://cdn-icons-png.flaticon.com/512/1076/1076337.png", // ícone dourado de igreja
            iconSize: [36, 40],
            iconAnchor: [18, 40],
            popupAnchor: [0, -28]
        });

        var userIcon = L.icon({
            iconUrl: "https://cdn-icons-png.flaticon.com/512/64/64113.png", // ícone azul do usuário
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -28]
        });

        // Função para calcular distância (em km)
        function getDistance(lat1, lon1, lat2, lon2) {
            function toRad(x) { return x * Math.PI / 180; }
            var R = 6371;
            var dLat = toRad(lat2-lat1);
            var dLon = toRad(lon2-lon1);
            var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                    Math.sin(dLon/2) * Math.sin(dLon/2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        // Carrega as igrejas do WP
        let igrejasData = [];
        fetch('<?php echo rest_url('mmi/v1/igrejas'); ?>')
            .then(response => response.json())
            .then(data => {
                igrejasData = data;
                // Mostra todas inicialmente
                data.forEach(igreja => {
                    if (igreja.latitude && igreja.longitude) {
                        L.marker([igreja.latitude, igreja.longitude], {icon: churchIcon})
                            .addTo(map)
                            .bindPopup(function() {
                                var html = `
                                <div style="
                                    display:flex;
                                    gap:10px;
                                    max-width:320px;
                                    font-family:Arial,sans-serif;
                                    border:1px solid #ddd;
                                    border-radius:8px;
                                    padding:10px;
                                    background:#fefefe;
                                    box-shadow:0 2px 6px rgba(0,0,0,0.15);
                                ">
                                    ${igreja.imagem ? `
                                    <div style="flex:0 0 100px;">
                                        <img src="${igreja.imagem}" style="width:100px; height:auto; border-radius:8px; object-fit:cover;" />
                                    </div>` : ''}
                                    <div style="flex:1; display:flex; flex-direction:column;">
                                        <div style="font-size:16px; font-weight:bold; margin-bottom:3px;">${igreja.comunidade}</div>
                                        <div style="font-weight:bold; margin-bottom:3px;">${igreja.paroquia}</div>
                                        <div style="margin-bottom:5px; color:#555;">${igreja.endereco}</div>
                                        <div style="font-weight:bold; margin-bottom:2px;">Horário:</div>
                                        <div style="margin-bottom:5px;">${igreja.horarios}</div>
                                        <div style="margin-top:auto; display:flex; gap:8px; font-size:16px;">
                                            ${igreja.youtube ? `<a href="${igreja.youtube}" target="_blank" title="YouTube" style="color:#c4302b;"><i class="fa fa-youtube-play"></i></a>` : ''}
                                            ${igreja.instagram ? `<a href="${igreja.instagram}" target="_blank" title="Instagram" style="color:#e4405f;"><i class="fa fa-instagram"></i></a>` : ''}
                                            ${igreja.facebook ? `<a href="${igreja.facebook}" target="_blank" title="Facebook" style="color:#1877f2;"><i class="fa fa-facebook"></i></a>` : ''}
                                        </div>
                                        ${igreja.detalhes ? `<div style="margin-top:5px; font-size:12px; color:#555;">${igreja.detalhes}</div>` : ''}
                                    </div>
                                </div>
                                `;
                                return html;
                            }());
                    }
                });
            });

        // Localização do usuário
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLat = position.coords.latitude;
                var userLon = position.coords.longitude;

                map.setView([userLat, userLon], 13);

                L.marker([userLat, userLon], {icon: userIcon})
                    .addTo(map)
                    .bindPopup("<b>Você está aqui</b>")
                    .openPopup();

                // Destacar igrejas até 10km
                igrejasData.forEach(igreja => {
                    if (igreja.latitude && igreja.longitude) {
                        var dist = getDistance(userLat, userLon, igreja.latitude, igreja.longitude);
                        if (dist < 10) {
                            L.marker([igreja.latitude, igreja.longitude], {icon: nearbyChurchIcon})
                                .addTo(map)
                                .bindPopup("⛪ <b>" + igreja.comunidade + "</b><br>" + 
                                           igreja.paroquia + "<br>" +
                                           igreja.endereco + "<br>" +
                                           "<i>" + igreja.horarios + "</i><br>" +
                                           "<b style='color:green'>Próximo de você (" + dist.toFixed(2) + " km)</b>" +
                                           (igreja.imagem ? "<br><img src='" + igreja.imagem + "' style='max-width:150px;border-radius:8px;margin-top:5px;'>" : "")
                                );
                        }
                    }
                });
            });
        }
    });
    </script>
    <?php return ob_get_clean();
}
add_shortcode('mapa_igrejas', 'mmi_shortcode_mapa');
