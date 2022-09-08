<?php


namespace EsSearch\Form;


class ESSearchForm {
  private $es_search_options;

  public function __construct() {
    add_action( 'admin_menu', array( $this, 'es_search_add_plugin_page' ) );
    add_action( 'admin_init', array( $this, 'es_search_page_init' ) );
  }

  public function es_search_add_plugin_page() {
    add_options_page(
      'ES Search', // page_title
      'ES Search', // menu_title
      'manage_options', // capability
      'es-search', // menu_slug
      array( $this, 'es_search_create_admin_page' ) // function
    );
  }

  public function es_search_create_admin_page() {
    $this->es_search_options = get_option( 'es_search_option_name' ); ?>

    <div class="wrap">
      <h2>ES Search</h2>
      <p></p>
      <?php settings_errors(); ?>

      <form method="post" action="options.php">
        <?php
        settings_fields( 'es_search_option_group' );
        do_settings_sections( 'es-search-admin' );
        submit_button();
        ?>
      </form>
    </div>
  <?php }

  public function es_search_page_init() {
    register_setting(
      'es_search_option_group', // option_group
      'es_search_option_name', // option_name
      array( $this, 'es_search_sanitize' ) // sanitize_callback
    );

    add_settings_section(
      'es_search_setting_section', // id
      'Settings', // title
      array( $this, 'es_search_section_info' ), // callback
      'es-search-admin' // page
    );

    add_settings_field(
      'index_name_0', // id
      'Index Name', // title
      array( $this, 'index_name_0_callback' ), // callback
      'es-search-admin', // page
      'es_search_setting_section' // section
    );

    add_settings_field(
      'id_1', // id
      'Id', // title
      array( $this, 'id_1_callback' ), // callback
      'es-search-admin', // page
      'es_search_setting_section' // section
    );

    add_settings_field(
      'host_connections_2', // id
      'Host Connections', // title
      array( $this, 'host_connections_2_callback' ), // callback
      'es-search-admin', // page
      'es_search_setting_section' // section
    );
  }

  public function es_search_sanitize($input) {
    $sanitary_values = array();
    if ( isset( $input['index_name_0'] ) ) {
      $sanitary_values['index_name_0'] = sanitize_text_field( $input['index_name_0'] );
    }

    if ( isset( $input['id_1'] ) ) {
      $sanitary_values['id_1'] = sanitize_text_field( $input['id_1'] );
    }

    if ( isset( $input['host_connections_2'] ) ) {
      $sanitary_values['host_connections_2'] = esc_textarea( $input['host_connections_2'] );
    }

    return $sanitary_values;
  }

  public function es_search_section_info() {

  }

  public function index_name_0_callback() {
    printf(
      '<input class="regular-text" type="text" name="es_search_option_name[index_name_0]" id="index_name_0" value="%s">',
      isset( $this->es_search_options['index_name_0'] ) ? esc_attr( $this->es_search_options['index_name_0']) : ''
    );
  }

  public function id_1_callback() {
    printf(
      '<input class="regular-text" type="text" name="es_search_option_name[id_1]" id="id_1" value="%s">',
      isset( $this->es_search_options['id_1'] ) ? esc_attr( $this->es_search_options['id_1']) : ''
    );
  }

  public function host_connections_2_callback() {
    printf(
      '<textarea class="large-text" rows="5" name="es_search_option_name[host_connections_2]" id="host_connections_2">%s</textarea>',
      isset( $this->es_search_options['host_connections_2'] ) ? esc_attr( $this->es_search_options['host_connections_2']) : ''
    );
  }
}