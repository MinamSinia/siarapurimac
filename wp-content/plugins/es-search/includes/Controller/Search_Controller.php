<?php


namespace EsSearch\Controller;

use WP_REST_Request;

use EsSearch\Service\SearchContentService;

defined( 'WPINC' ) or die( 'No script kiddies please!' );

class Search_Controller {

  public function __construct() {
    $this->namespace     = '/es-search/v1';
  }

  public function register_routes() {
    register_rest_route($this->namespace, 'data', [
      'methods'   => 'GET',
      'callback'  => [ $this, 'data' ],
      'permission_callback' => '__return_true',
    ]);
  }

  /**
   * rest response data.
   *
   * @param WP_REST_Request $request Current request.
   */
  public function data(WP_REST_Request $request) {
    $input = $request->get_params();

    $searchContentService = new SearchContentService();

    $data = $searchContentService->search($input);

    $agg = [];
    if ($input['pagination'] == '0') {
      $agg = $searchContentService->agg($input);
    }

    $ou = [
      // 'input' => $input,
      'status' => 'ok',
      'data' => $data,
      'aggs' => $agg,
      'msg' => '...',
    ];

    return rest_ensure_response( $ou );
  }
}