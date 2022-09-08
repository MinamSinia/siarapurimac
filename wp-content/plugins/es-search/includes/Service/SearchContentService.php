<?php


namespace EsSearch\Service;


use EsSearch\Lib\ElasticSearch;


class SearchContentService {
  public ElasticSearch $elasticSearch;

  public function __construct() {
    $this->elasticSearch = new ElasticSearch();
  }

  public function search($form) : array {
    $body = $this->search_body($form);
    $result = $this->elasticSearch->rowSearch($body);

    if (isset($form['debug'])) {
      echo '<h1>Search</h1>';
      echo '<h2>Form</h2>';
      var_dump($form);
      echo '<h2>Body</h2>';
      var_dump($body);
      echo '<h2>Result</h2>';
      var_dump($result);
      return [];
    }

    return $result;
  }

  public function agg($form) : array {
    $body = $this->search_body($form, TRUE);
    $result = $this->elasticSearch->rowSearch($body);
    $transform = $this->agg_transformation($result, $form);

    if (isset($form['debug'])) {
      echo '<h1>Agg</h1>';
      echo '<h2>Form</h2>';
      var_dump($form);
      echo '<h2>Body</h2>';
      var_dump($body);
      echo '<h2>Result</h2>';
      var_dump($result);
      echo '<h2>Transform</h2>';
      var_dump($transform);
      return [];
    }

    return $transform;
  }

  protected function agg_transformation($in, $form) : array {
    if (!isset($in['aggregations'])) {
      return [];
    }
    $ou = [];
    foreach ($in['aggregations'] as $aggName => $aggData) {
      if (!isset(ContentService::ES_AGGS[$aggName])) {
        continue;
      }
      if (!ContentService::ES_AGGS[$aggName]['enable']) {
        continue;
      }
      $ou[ContentService::ES_AGGS[$aggName]['sort'] . '-' . ContentService::ES_AGGS[$aggName]['label'] . '-' . $aggName] = [
        'name' => ContentService::ES_AGGS[$aggName]['name'],
        'label' => ContentService::ES_AGGS[$aggName]['label'],
        'data' => $this->agg_transformation_data($aggData, ContentService::ES_AGGS[$aggName]['name'], $form),
        'before' => ContentService::ES_AGGS[$aggName]['before'],
      ];
    }
    ksort($ou);

    $ou['type_agg'] = [
      'name' => 0,
      'data' => $this->agg_transformation_type($in['aggregations']['type_agg']),
    ];

    return $ou;
  }

  protected function agg_transformation_data($in, $nameIn, $form) : array {
    if (!isset($in['buckets'])) {
      return [];
    }

    $group = [];
    if (isset($form['or_group'])) {
      $group = $form['or_group'];
    }
    $ou = [];
    foreach ($in['buckets'] as $row) {
      $l = explode($this->elasticSearch->config->separator, $row['key']);
      $name = $nameIn . $this->elasticSearch->config->separator . $l[1];

      $ou[$row['key']] = [
        'value' => $l[1],
        'label' => $l[0],
        'selected' => (  array_search($name, $group) !== FALSE ? 'selected': ''),
        'count' => $row['doc_count'],
      ];
    }
    ksort($ou);

    return $ou;
  }

  protected function agg_transformation_type($in) : array {
    if (!isset($in['buckets'])) {
      return [];
    }
    $ou = [];
    foreach ($in['buckets'] as $row) {
      $ou[] = $row['key'];
    }
    return $ou;
  }

  private function search_body($form, $agg = FALSE) {
    $ou = [];
    if (isset($form['site']) && trim($form['site']) != '') {
      $ou['query']['bool']['filter'][]  = [
        'term' => [ 'display' => $form['site'] ]
      ];
    }

    if (isset($form['content_type']) && (trim($form['content_type']) != '' && trim($form['content_type']) != 'all')) {
      $ou['query']['bool']['filter'][]  = [
        'term' => [ 'type' => $form['content_type'] ]
      ];
    }

    $or_group = [];
    if (isset($form['or_group'])) {
      foreach ($form['or_group'] as $field) {
        $l = explode($this->elasticSearch->config->separator, $field);
        if (count($l) < 2) {
          continue;
        }
        $tmp = [];
        $tmp['term'][$l[0]] = $l[1];
        $or_group[] = $tmp;
      }
    }

    /*
     * AND: must
     * OR: should
     * */
    if (count($or_group) > 0) {
      $ou['query']['bool']['filter'][]  = [
        'bool' => [
          'must' => $or_group
        ]
      ];
    }

    // analysis text // falta mejorar
    if (isset($form['query']) && trim($form['query']) != '') {
      $tmp = [];
      $tmp['multi_match'] = [];
      $tmp['multi_match']['query'] = $form['query'];
      if (isset($form['query_type']) && trim($form['query_type']) != '') {
        $tmp['multi_match']['type'] = $form['query_type'];
      }
      $tmp['multi_match']['fields'] = ['title', 'body', 'numero_norma'];

      if (!isset($form['content_type']) ||
        (isset($form['content_type']) && trim($form['content_type']) == 'all')) {
        $tmp['multi_match']['fields'] = ['title', 'body', 'numero_norma'];
      }
      elseif(isset($form['content_type']) && trim($form['content_type']) == 'normatividad') {
        $tmp['multi_match']['fields'] = ['title', 'body', 'numero_norma'];
      }
      else {
        $tmp['multi_match']['fields'] = ['title', 'body'];
      }
      $ou['query']['bool']['must'][] = $tmp;

    }

    $ou['_source'] = FALSE;
    $ou['fields'] = [
      'uuid', 'title', 'type',
    ];

    if (isset($form['print']) && trim($form['print']) != '') {
      $ou['fields'][] = $form['print'];
    }

    $ou['size'] = 5;

    if (isset($form['size']) && trim($form['size']) != '') {
      $ou['size'] = intval(trim($form['size']));
    }
    if (isset($form['page']) && trim($form['page']) != '') {
      $page = intval(trim($form['page']));
      $from = $ou['size'] * ($page - 1);
      $ou['from'] = $from;
    }
    else {
      $ou['from'] = 0;
    }

    if ($ou['from'] > 0) {
      $ou['track_total_hits'] = FALSE;
    }

    if (empty($ou['query']) && !$agg) {
      $ou['query']['match_all'] = new \stdClass();
    }

    if ($agg) {
      $ou['size'] = 0;
      unset($ou['from']);
      unset($ou['fields']);
      unset($ou['_source']);
      $ou['track_total_hits'] = FALSE;
      $ou['aggs'] = $this->search_body_agg();
    }

    return $ou;
  }

  private function search_body_agg() :array {
    $ou = [];

    foreach (ContentService::ES_AGGS as $row) {
      $ou[$row['name'] . '_agg'] = [
        'terms' => [
          'field' => $row['name']. '_agg',
        ],
      ];
    }
    $ou['type_agg'] = [
      'terms' => [
        'field' => 'type',
      ],
    ];

    return $ou;
  }
}