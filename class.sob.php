<?php
if (!class_exists('SOB_View')){
  class SOB_View{
    public function __construct(){

      $whereDisplay = sanitize_text_field( SOB_Settings::$sob_options['sob_where_display'] );
      if ( sanitize_text_field( SOB_Settings::$sob_options['sob_active_sob'] ) == 'Active'){
        if ($whereDisplay == 'After billing details'){
          add_action( 'woocommerce_after_checkout_billing_form', array($this,'display_sob') );
        }elseif ($whereDisplay == 'Before payment methods'){
          add_action( 'woocommerce_review_order_before_payment', array($this,'display_sob') );
        }else{
          add_action( 'woocommerce_checkout_before_order_review', array($this,'display_sob') );
        }
      }

    }

    public function display_sob(){
      require_once ( SOB_PATH . 'views/order-bump.php' );
      wp_enqueue_style( 'sob-main-css' );
      wp_enqueue_script( 'sob-main-js' );
//      sob_options();
    }
  }
}