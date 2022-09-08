<?php
/*
	Plugin Name: ElasticSearch Drinux
	Plugin URI: https://drinux.com/

	Description: Api connect ElasticSearch
	Tags: api, json, http
	Author: Claudio Rodriguez
	Version: 1.0.0.2
	Requires PHP: 7.4
*/

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

use EsSearch\Controller\Search_Controller;
use EsSearch\Form\ESSearchForm;

add_action('rest_api_init', 'es_search__register_routes');

function es_search__register_routes() {
  $controller = new Search_Controller();
  $controller->register_routes();
}

if ( is_admin() )
  $es_search = new ESSearchForm();