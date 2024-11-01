<?php
if (!class_exists('SOB_Settings')){
  class SOB_Settings{
    public static $sob_options;

    public function __construct(){
      self::$sob_options=get_option('sob_options');
      add_action('admin_init',array($this,'admin_init'));
    }
    public function admin_init(){
      register_setting(
        'sob_group',
        'sob_options',
        array($this,'sob_validate')
      );

//      array($this,'sob_validate')
      add_settings_section(
        'sob_main_section',
        esc_html__('Simple Order Bump','sob'),
        null,
        'sob_page1'
      );

      // Where do you want to display the order bump?
      add_settings_field(
        'sob_active_sob',
        esc_html__('Active/Desactive Simple Order Bump','sob'),
        array($this,'sob_active_sob_callback'),
        'sob_page1',
        'sob_main_section',
        array(
          'items' => array(
            esc_html__('Active','sob'),
            esc_html__('Desactive','sob'),
          ),
          'label_for' => 'sob_active_sob',
        )
      );

      //Get simple products
      $args = array(
        'type' => 'simple',
        'return' => 'objects',
      );
      $products = wc_get_products( $args );
      $productArray =[];
      foreach ( $products as $product ) {
        $productImage = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'single-post-thumbnail' );
        $productArray[] = [
          'id' => $product->get_id(),
          'titulo' => $product->get_name(),
          'image' =>$productImage[0]
        ];
      }
      add_settings_field(
        'sob_product_bump',
        esc_html__('Select the product that will appear as an order bump','sob'),
        array($this,'sob_product_bump_callback'),
        'sob_page1',
        'sob_main_section',
        array(
          'items' => $productArray,
          'label_for' => 'sob_product_bump',
        )
      );

      // Title Order Bump
      add_settings_field(
        'sob_product_title',
        esc_html__('Enter the product title.','sob'),
        array($this,'sob_product_title_callback'),
        'sob_page1',
        'sob_main_section',
        array(
          'label_for' => 'sob_product_title',
        )
      );

      // Title Order Bump
      add_settings_field(
        'sob_product_description',
        esc_html__('Enter the product description.','sob'),
        array($this,'sob_product_description_callback'),
        'sob_page1',
        'sob_main_section',
        array(
          'label_for' => 'sob_product_description',
        )
      );

      // Title Order Bump
      add_settings_field(
        'sob_impressive_title',
        esc_html__('Enter the impressive title.','sob'),
        array($this,'sob_impressive_title_callback'),
        'sob_page1',
        'sob_main_section',
        array(
          'label_for' => 'sob_impressive_title',
        )
      );

      // Text to add to cart
      add_settings_field(
        'sob_text_to_add_to_cart',
        esc_html__('Text to add to cart.','sob'),
        array($this,'sob_text_to_add_to_cart_callback'),
        'sob_page1',
        'sob_main_section',
        array(
          'label_for' => 'sob_text_to_add_to_cart',
        )
      );

      // Where do you want to display the order bump?
      add_settings_field(
        'sob_where_display',
        esc_html__('Where do you want to display the order bump?','sob'),
        array($this,'sob_where_display_callback'),
        'sob_page1',
        'sob_main_section',
        array(
          'items' => array(
            esc_html__('After billing details','sob'),
            esc_html__('Before payment methods','sob'),
            esc_html__('Before order review table','sob'),
          ),
          'label_for' => 'sob_where_display',
        )
      );

    }//END ADMIN INIT

    public function sob_product_bump_callback($args){
      ?>
        <select id="sob_product_bump" name="sob_options[sob_product_bump]" class="product-bump">
          <?php
          foreach( $args['items'] as $item ):
            ?>
            <option value="<?php echo esc_attr( $item['id'] ); ?>" image="<?php echo $item['image']?>"
              <?php
              isset( self::$sob_options['sob_product_bump'] ) ? selected( $item['id'], self::$sob_options['sob_product_bump'], true ) : '';
              ?>
            ><?php echo esc_html( ucfirst( $item['titulo'] ) ); ?></option>
          <?php endforeach; ?>
        </select>
      <?php
    }

    // Product Title Order Bump
    public function sob_product_title_callback($args){
      ?>
      <input type="text" name="sob_options[sob_product_title]" id="sob_product_title" value="<?php echo isset(self::$sob_options['sob_product_title']) ? esc_attr(self::$sob_options['sob_product_title']) : ''; ?>"><br>
      <?php
    }

    // Product Description Order Bump
    public function sob_product_description_callback($args){
      ?>
      <textarea name="sob_options[sob_product_description]" id="sob_product_description"><?php echo isset(self::$sob_options['sob_product_description']) ? esc_attr(self::$sob_options['sob_product_description']) : ''; ?></textarea>
      <?php
    }

    // Title Order Bump
    public function sob_impressive_title_callback($args){
      ?>
      <input type="text" name="sob_options[sob_impressive_title]" id="sob_impressive_title" value="<?php echo isset(self::$sob_options['sob_impressive_title']) ? esc_attr(self::$sob_options['sob_impressive_title']) : ''; ?>" placeholder="Only for you"><br>
      <small>Leave this field blank if you don't want to display an impressive title.</small>
      <?php
    }

    // Text to add to cart
    public function sob_text_to_add_to_cart_callback($args){
      ?>
      <input type="text" name="sob_options[sob_text_to_add_to_cart]" id="sob_text_to_add_to_cart" value="<?php echo isset(self::$sob_options['sob_text_to_add_to_cart']) ? esc_attr(self::$sob_options['sob_text_to_add_to_cart']) : ''; ?>" placeholder="Yes! I want this!">
      <?php
    }

    public function sob_where_display_callback($args){
      ?>
      <select id="sob_where_display" name="sob_options[sob_where_display]" >
        <?php
        foreach( $args['items'] as $item ):
          ?>
          <option value="<?php echo esc_attr( $item); ?>"
            <?php
            isset( self::$sob_options['sob_where_display'] ) ? selected( $item, self::$sob_options['sob_where_display'], true ) : '';
            ?>
          ><?php echo esc_html( ucfirst( $item ) ); ?></option>
        <?php endforeach; ?>
      </select>
      <?php
    }

    public function sob_active_sob_callback($args){
      ?>
      <select id="sob_active_sob" name="sob_options[sob_active_sob]" >
        <?php
        foreach( $args['items'] as $item ):
          ?>
          <option value="<?php echo esc_attr( $item); ?>"
            <?php
            isset( self::$sob_options['sob_active_sob'] ) ? selected( $item, self::$sob_options['sob_active_sob'], true ) : '';
            ?>
          ><?php echo esc_html( ucfirst( $item ) ); ?></option>
        <?php endforeach; ?>
      </select>
      <?php
    }
    public function sob_validate( $input ){
      $new_input = array();
      foreach( $input as $key => $value ){
        switch ($key){
          case 'sob_product_description':
            $new_input[$key] = sanitize_textarea_field( $value );
            break;
          default:
            $new_input[$key] = sanitize_text_field( $value );
            break;
        }
      }
      return $new_input;
    }

  }//END CLASS SOB
}