<div class=" container wrap sob-admin-container">
    <div class="header-settings-page-custom">
      <h1 class="title-sob"><?php echo esc_html(get_admin_page_title());?></h1>
      <ul>
        <li><a href="https://www.paypal.com/paypalme/marcodeoficial?country.x=MX&locale.x=es_XC"><?php esc_html_e('Donate 🍺','sob') ?></a></li>
      </ul>
    </div>
</div>

<section>
  <div class="container">
    <form action="options.php" method="post">

      <?php
      settings_fields('sob_group');
      do_settings_sections('sob_page1');

      submit_button(esc_html__('Save Settings','sob'));

      ?>

    </form>
  </div>
</section>