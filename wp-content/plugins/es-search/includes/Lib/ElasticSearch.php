<?php


namespace EsSearch\Lib;

use ElasticSearch\Client;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\TransportException;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\Missing404Exception;

use EsSearch\Config\ElasticSearchConfig;

class ElasticSearch {
  protected Client $client;
  protected string $index;
  public string $indexStructure;
  public bool $deleteIfPublished;

  public ElasticSearchConfig $config;

  public function __construct(ElasticSearchConfig $config = NULL) {
    if (is_null($config)) {
      $this->config = new ElasticSearchConfig();
    }
    else {
      $this->config = $config;
    }

    $this->indexStructure = $this->config->indexStructure;
    $this->deleteIfPublished = $this->config->deleteIfPublished;

    $this->setClient();
  }

  private function setClient() : void {
    if ($this->config->isCloud) {
      $this->client = ClientBuilder::create()
        ->setElasticCloudId($this->config->cloud['cloudId'])
        ->setBasicAuthentication(
          $this->config->cloud['username'],
          $this->config->cloud['password']
        )
        ->setRetries($this->config->retries)
        ->setElasticMetaHeader(false)
        ->build();
    }
    else {
      $builder = ClientBuilder::create()
        ->setHosts($this->config->hosts)
        ->setRetries($this->config->retries)
        ->setSSLVerification(False)
      ;
      if ($this->config->isNotSSL) {
        $builder->setSSLVerification(FALSE);
      }
      $this->client = $builder->build();
    }
  }

  public function rowSave($id, $body) {
    if (!$this->config->enableES) {
      return NULL;
    }
    $msg = 'in rowSave ';
    try {
      return $this->client->index([
        'index' => $this->config->index,
        'id'    => $id,
        'body'  => $body
      ]);
    }
    catch (TransportException $e) {

    }
    catch (BadRequest400Exception $e) {

    }
    return NULL;
  }

  public function rowSaveBulk($body) {
    if (!$this->config->enableES) {
      return NULL;
    }
    $msg = 'in rowSaveBulk ';
    try {
      return $this->client->bulk([
        'index' => $this->config->index,
        'body'  => $body
      ]);
    }
    catch (TransportException $e) {
    }
    catch (BadRequest400Exception $e) {
    }
    return NULL;
  }

  public function rowGet($id) {
    if (!$this->config->enableES) {
      return NULL;
    }
    $msg = 'in rowGet ';
    try {
      return $this->client->get([
        'index' => $this->config->index,
        'id'    => $id,
      ]);
    }
    catch (TransportException $e) {
    }
    catch (BadRequest400Exception $e) {
    }
    return NULL;
  }

  public function rowSearch($body) {
    if (!$this->config->enableES) {
      return NULL;
    }
    $msg = 'in rowSearch ';
    try {
      return $this->client->search([
        'index' => $this->config->index,
        'body'  => $body,
      ]);
    }
    catch (TransportException $e) {
    }
    catch (BadRequest400Exception $e) {
    }
    return NULL;
  }

  public function rowDelete($id) {
    if (!$this->config->enableES) {
      return NULL;
    }
    $msg = 'in rowDelete ';
    try {
      return $this->client->delete([
        'index' => $this->config->index,
        'id'    => $id,
      ]);
    }
    catch (TransportException $e) {
    }
    catch (BadRequest400Exception $e) {
    }
    catch (Missing404Exception $e) {
    }
    return NULL;
  }

  public function indexDelete($id = NULL) {
    if (!$this->config->enableES) {
      return NULL;
    }
    $msg = 'in indexDelete ';
    if (!$id) {
      $id = $this->config->index;
    }
    try {
      return $this->client->indices()->delete([
        'index' => $id,
      ]);
    }
    catch (TransportException $e) {
    }
    catch (BadRequest400Exception $e) {
    }
    catch (Missing404Exception $e) {
    }
    return NULL;
  }

  public function indexCreate($body = NULL, $id = NULL) {
    if (!$this->config->enableES) {
      return NULL;
    }

    $msg = 'in indexCreate ';
    if (!$id) {
      $id = $this->config->index;
    }
    if (!$body) {
      $body = $this->indexStructure;
    }

    try {
      return $this->client->indices()->create([
        'index' => $id,
        'body' => $body,
      ]);
    }
    catch (TransportException $e) {
    }
    catch (BadRequest400Exception $e) {
    }
    catch (Missing404Exception $e) {
    }
    return NULL;
  }
}