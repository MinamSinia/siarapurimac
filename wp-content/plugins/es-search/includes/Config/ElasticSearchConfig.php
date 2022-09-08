<?php


namespace EsSearch\Config;


class ElasticSearchConfig {
  public bool $enableES = FALSE;
  public bool $isCloud;
  public bool $isNotSSL;
  public array $cloud;
  public array $hosts;
  public $index;
  public int $retries;
  public bool $deleteIfPublished = TRUE;
  public string $separator = '<|-|>';

  public string $indexStructure;

  public function __construct() {
    $es_search_options = get_option( 'es_search_option_name' );


    $this->enableES = TRUE;
    $this->isNotSSL = TRUE;
    $this->isCloud = FALSE;
    $this->cloud = [
      'cloudId' => '',
      'username' => 'elastic',
      'password' => '123',
    ];

    $json = json_decode(htmlspecialchars_decode($es_search_options['host_connections_2']), TRUE);
    if (!$json) {
      echo '{"msg": "host_connections is incorrect", "status": "error"}';
      die();
    }


    $this->hosts = $json;

//    $this->hosts = [
//      [
//        'host' => '161.35.119.180',
//        'scheme' => 'https',
//        'port' => '9200',
//        'user' => 'elastic',
//        'pass' => 'PtbSagVtwNPvISTxj03T'
//      ],
//    ];
    $this->index = $es_search_options['index_name_0'];
    $this->retries = 3;

    $this->deleteIfPublished = FALSE;

    $this->separator = '<|-|>';

    $this->indexStructure = '';
  }

}