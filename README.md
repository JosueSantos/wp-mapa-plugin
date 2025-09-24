# Mapa de Igrejas

**Contributors:** Josué Santos  
**Tags:** igreja, mapa, missas, católico, paróquia  
**Requires at least:** 5.0  
**Tested up to:** 6.6  
**Requires PHP:** 7.4  
**Stable tag:** 1.0.0
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

O **Mapa de Igrejas** é um plugin que permite cadastrar igrejas católicas no WordPress e exibir um mapa interativo com seus endereços, horários de Missa, redes sociais e imagens. É baseado em [Leaflet](https://leafletjs.com/) com integração ao OpenStreetMap.

---

## Decrição

O plugin oferece uma forma prática de gerenciar informações de igrejas e disponibilizá-las visualmente em um mapa:

### Recursos principais:
- Cadastro de igrejas via painel administrativo ou formulário frontend.
- Campos personalizados: Nome da Comunidade, Paróquia, Endereço, Latitude, Longitude, Horário de Missa, YouTube, Instagram, Facebook, Detalhes, e Foto da Igreja.
- API REST para retorno de dados em JSON.
- Shortcode `[mapa_igrejas]` para exibir o mapa interativo em páginas ou posts.
- Shortcode `[cadastro_igreja]` para permitir cadastro de novas igrejas pelo frontend.
- Shortcode `[editar_igreja id="123"]` para permitir edição de um registro existente (usuários autorizados).
- Mapas responsivos e interativos, com marcadores personalizados para igrejas e localização do usuário.
- Destaque visual para igrejas próximas à localização do visitante.
- Compatível com OpenStreetMap (sem necessidade de chave de API paga).

---

## Instalação

1. Faça o upload da pasta `mapa-igrejas` para o diretório `/wp-content/plugins/`.
2. Ative o plugin no menu **Plugins** do WordPress.
3. No painel administrativo, vá em **Igrejas → Adicionar Nova** para cadastrar igrejas.
4. Para exibir o mapa, adicione o shortcode `[mapa_igrejas]` em qualquer página ou post.
5. Para permitir cadastro de novas igrejas pelo frontend, use `[cadastro_igreja]`.
6. Para edição de um registro existente, use `[editar_igreja id="ID_DO_POST"]` (somente para usuários autorizados).

---

## Perguntas Frequentes

### Preciso de uma chave de API do Google Maps?
Não. O plugin utiliza **OpenStreetMap via Leaflet**, totalmente gratuito.

### Como obtenho latitude e longitude da minha igreja?
Você pode usar ferramentas como Google Maps ou OpenStreetMap para copiar as coordenadas.  
Exemplo: clique com o botão direito no mapa e selecione "Qual é este local?" para obter os números.

### Posso usar este plugin para outros tipos de locais (ex: capelas, comunidades)?
Sim. Basta cadastrar como "igreja" e adaptar os campos conforme necessário.

### O mapa pode mostrar as igrejas mais próximas do usuário?
Sim. O mapa detecta a localização do visitante (com permissão) e destaca visualmente as igrejas próximas.

---

## Changelog

### 1.0
- Versão inicial do plugin.
- Cadastro de igrejas como Custom Post Type.
- API REST para retorno em JSON.
- Shortcodes `[mapa_igrejas]`, `[cadastro_igreja]` e `[editar_igreja]`.
- Mapa interativo com OpenStreetMap e Leaflet.
- Suporte a upload de imagem, horários e redes sociais.

---

## Créditos

Inspirado no projeto [Católico Cristão](https://catolicocristao.github.io/).  
Desenvolvido com [Leaflet](https://leafletjs.com/) e [OpenStreetMap](https://www.openstreetmap.org/).  
