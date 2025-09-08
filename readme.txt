=== Mapa de Igrejas ===
Contributors: Josué Santos
Tags: igreja, mapa, missas, católico, paróquia
Requires at least: 5.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin que cadastra igrejas e exibe um mapa interativo com seus endereços, horários de Missa e redes sociais.

== Description ==

O **Mapa de Igrejas** é um plugin que permite cadastrar informações de igrejas católicas (nome, endereço, latitude/longitude, horários de Missa e redes sociais) diretamente no WordPress.  
Esses dados são exibidos em um mapa interativo baseado no [Leaflet](https://leafletjs.com/), integrado ao OpenStreetMap.  

### Recursos principais:
- Cadastro de igrejas via painel administrativo do WordPress.
- Campos personalizados para endereço, coordenadas, horários e redes sociais.
- API REST integrada para expor os dados em JSON.
- Shortcode `[mapa_igrejas]` para exibir o mapa em qualquer página/post.
- Compatível com OpenStreetMap (sem necessidade de chave de API paga).

== Installation ==

1. Faça o upload da pasta `mapa-igrejas` para o diretório `/wp-content/plugins/`.
2. Ative o plugin no menu "Plugins" do WordPress.
3. No painel administrativo, vá em **Igrejas → Adicionar Nova** para cadastrar igrejas.
4. Para exibir o mapa, adicione o shortcode `[mapa_igrejas]` em qualquer página ou post.

== Frequently Asked Questions ==

= Preciso de uma chave de API do Google Maps? =  
Não. O plugin utiliza o **OpenStreetMap via Leaflet**, totalmente gratuito.

= Como obtenho latitude e longitude da minha igreja? =  
Você pode usar ferramentas como Google Maps ou OpenStreetMap para copiar as coordenadas.  
Exemplo: clique com o botão direito no mapa e selecione "Qual é este local?" para obter os números.

= Posso usar este plugin para outros tipos de locais (ex: capelas, comunidades)? =  
Sim. Basta cadastrar como "igreja" e adaptar os campos.


== Changelog ==

= 1.0 =
* Versão inicial do plugin.
* Cadastro de igrejas como Custom Post Type.
* API REST para retorno em JSON.
* Shortcode `[mapa_igrejas]` para exibir mapa interativo.

== Upgrade Notice ==

= 1.0 =
Primeira versão estável do plugin.

== Credits ==

Inspirado no projeto [Católico Cristão](https://catolicocristao.github.io/).  
Desenvolvido com [Leaflet](https://leafletjs.com/) e [OpenStreetMap](https://www.openstreetmap.org/).
