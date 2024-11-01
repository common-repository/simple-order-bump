<?php
if ( sanitize_text_field( SOB_Settings::$sob_options['sob_active_sob'] ) === '' || empty( sanitize_text_field( SOB_Settings::$sob_options['sob_active_sob'] ) ) || sanitize_text_field( SOB_Settings::$sob_options['sob_active_sob'] ) == 'Desactive' ) {
  return;
}
$impressive_title = sanitize_text_field( SOB_Settings::$sob_options['sob_impressive_title'] );
$text_add_to_cart = sanitize_text_field( SOB_Settings::$sob_options['sob_text_to_add_to_cart'] );
$custom_product_title = sanitize_text_field( SOB_Settings::$sob_options['sob_product_title'] );
$custom_product_description = sanitize_text_field( SOB_Settings::$sob_options['sob_product_description'] );
$product_id = absint( SOB_Settings::$sob_options['sob_product_bump'] );
$product = wc_get_product( $product_id );
$product_in_cart = false;

foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
  if ( absint( $cart_item['product_id'] ) == $product_id ) {
    $product_in_cart = true;
    break;
  }
}

if (!$product_in_cart){
  if ( $product ) {
    // Accede a los datos del producto
    $product_title = esc_html( $product->get_title() );
    $product_price = esc_html( $product->get_price() );
    $product_sku = esc_html( $product->get_sku() );
    $product_description = esc_html( $product->get_description() );
    $product_image = esc_url( wp_get_attachment_image_src( $product->get_image_id(), 'full' )[0] );
    ?>
    <div id="sob-container">
      <div class="sob-box-product ">
        <div class="title">
          <h3 class="sob-title"><?php echo esc_html( $impressive_title );?></h3>
        </div>
        <div class="sob-content">
          <div class="sob-add-to-cart">
            <?php
            $nonce = wp_create_nonce( 'add_product_to_cart_action' );
            $cartArray =[];
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
              array_push($cartArray, esc_html($cart_item['product_id']) );
            }
            ?>
            <input type="checkbox" data-nonce="<?php echo esc_attr( $nonce );?>" cart="<?php echo esc_attr( json_encode($cartArray) );?>" data-ajax="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" class="sob_input_product" product-id="<?php echo esc_html( $product_id );?>" id="orderbump-<?php echo esc_html( $product_id );?>" />
            <label for="orderbump-<?php echo esc_html( $product_id );?>"><?php echo esc_html( $text_add_to_cart ) . esc_html( $product_price );?></label>
          </div>
          <div class="sob-product">
            <div class="sob-image">
              <img src="<?php echo $product_image;?>" alt="<?php echo esc_html( $custom_product_title );?>">
            </div>
            <div class="sob-product-info">
              <h4 class="sob-product-title"><?php echo esc_html( $custom_product_title );?></h4>
              <p class="sob-product-description"><?php echo esc_html( $custom_product_description );?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php }
}


?>

