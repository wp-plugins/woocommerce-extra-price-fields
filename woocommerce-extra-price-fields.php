<?php
/*
  Plugin Name: Woocoomerce Extra Price Fields
  Description: Add Extra Fields to Price required to show in certain countries/region
  Author: Aman Saini
  Author URI: https://amansaini.me
  Plugin URI: http://amansaini.me/plugins/woocommerce-extra-price-fields/
  Version: 1.0
  Requires at least: 3.0.0
  Tested up to: 3.5.1

 */

/*
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */



function add_custom_price_box() {

    woocommerce_wp_text_input(array('id' => '_per100gm_price', 'class' => 'wc_input_per100gmprice short', 'label' => __('Per 100g Price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')', 'type' => 'number', 'custom_attributes' => array(
            'step' => 'any',
            'min' => '0'
            )));

    woocommerce_wp_text_input(array('id' => '_per100_pieces', 'class' => 'wc_input__per100_pieces short', 'label' => __('Per 100 Pieces', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')', 'type' => 'number', 'custom_attributes' => array(
            'step' => 'any',
            'min' => '0'
            )));
}

add_action('woocommerce_product_options_pricing', 'add_custom_price_box');

function custom_woocommerce_process_product_meta($post_id, $post) {

    update_post_meta($post_id, '_per100gm_price', stripslashes($_POST['_per100gm_price']));

    update_post_meta($post_id, '_per100_pieces', stripslashes($_POST['_per100_pieces']));
}

add_action('woocommerce_process_product_meta', 'custom_woocommerce_process_product_meta', 2, 2);

function add_custom_price_front($p, $obj) {

    $post_id = $obj->post->ID;


    $link = get_permalink($post_id);
    $per100gm = get_post_meta($post_id, '_per100gm_price', true);

    $per100piece = get_post_meta($post_id, '_per100_pieces', true);

    if (is_admin()) {
        $tag = 'div'; //show in new line
    } else {
        $tag = 'span';
    }

    if (!empty($per100gm)) {
        $additional_price.= "<$tag style='font-size:80%' class='price_per100gm'> (" . get_woocommerce_currency_symbol() . "100g=$per100gm)</$tag>";
    }
    if (!empty($per100piece)) {
        $additional_price.= "<$tag style='font-size:80%' class='price_per100piece'> (" . get_woocommerce_currency_symbol() . "100 Pieces=$per100piece)</$tag>";
    }


    return "<a href='$link'>" . $p . $additional_price . "</a>";
}

add_filter('woocommerce_get_price_html', 'add_custom_price_front', 10, 2);
add_filter('woocommerce_get_price_html', 'add_custom_price_front', 10, 2);


?>