<?php
/**
 * Plugin Name: Mapa de Igrejas
 * Description: Cadastro e exibição de igrejas em um mapa interativo.
 * Version: 1.0.0
 * Author: Josué Santos
 * Author URI: https://josuesantos.github.io/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'MMI_PATH', plugin_dir_path(__FILE__) );
define( 'MMI_URL', plugin_dir_url(__FILE__) );

// Includes
require_once MMI_PATH . 'includes/cpt.php';
require_once MMI_PATH . 'includes/metaboxes.php';
require_once MMI_PATH . 'includes/shortcode.php';
require_once MMI_PATH . 'includes/rest.php';
require_once MMI_PATH . 'includes/form.php';
require_once MMI_PATH . 'includes/edit-form.php';

// Carrega Leaflet e CSS
function mmi_enqueue_scripts() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', [], '6.4.0');
    wp_enqueue_style('leaflet-css', "https://unpkg.com/leaflet/dist/leaflet.css");
    wp_enqueue_script('leaflet-js', "https://unpkg.com/leaflet/dist/leaflet.js", array(), null, true);
}
add_action('wp_enqueue_scripts', 'mmi_enqueue_scripts');
