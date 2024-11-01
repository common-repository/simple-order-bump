<?php
if (!class_exists('Ajax_Add_To_Cart')) {
  class Ajax_Add_To_Cart
  {
    public function __construct()
    {
      add_action('wp_ajax_add_product_to_cart', array($this, 'add_product_to_cart'));
      add_action('wp_ajax_nopriv_add_product_to_cart', array($this, 'add_product_to_cart'));
    }

    public function add_product_to_cart()
    {
      if (!isset($_POST['security'], $_POST['chkAction'], $_POST['product'])) {
        wp_send_json_error(array(
          'success' => false,
          'message' => 'Datos inválidos'
        ));
      }

      $nonce = sanitize_text_field($_POST['security']);
      $chkAction = filter_var($_POST['chkAction'], FILTER_VALIDATE_BOOLEAN);
      $productID = sanitize_text_field($_POST['product']);

      if (!wp_verify_nonce($nonce, 'add_product_to_cart_action')) {
        wp_send_json_error(array(
          'success' => false,
          'message' => 'No es posible continuar, el nonce no es válido.'
        ));
      }

      if ($chkAction) {
        $response = WC()->cart->add_to_cart($productID, 1);
      } else {
        $cartId = WC()->cart->generate_cart_id($productID);
        $cartItemKey = WC()->cart->find_product_in_cart($cartId);
        $response = WC()->cart->remove_cart_item($cartItemKey);
      }

      wp_send_json(array(
        'success' => $response,
        'message' => '¡Éxito!'
      ));
    }
  }
}