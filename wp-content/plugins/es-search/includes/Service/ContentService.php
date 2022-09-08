<?php


namespace EsSearch\Service;


use EsSearch\Lib\ElasticSearch;

class ContentService
{

  CONST ES_AGGS = [
    'tipo_agg' => [ 'name' => 'tipo',  'label' => 'Sub-tipo de información', 'enable' => TRUE, 'before' => '', 'sort' => '01'],
    'rep_territorial_agg' => [ 'name' => 'rep_territorial',  'label' => 'Ámbito geográfico', 'enable' => TRUE, 'before' => '<hr style="color: #9f9f9f;">', 'sort' => '02'],
    'descriptores_tematicos_agg' => [ 'name' => 'descriptores_tematicos',  'label' => 'Temáticas Ambiental', 'enable' => TRUE, 'before' => '', 'sort' => '03'],
    'tag_agg' => [ 'name' => 'tag',  'label' => 'Descriptor o etiqueta', 'enable' => TRUE, 'before' => '<hr style="color: #9f9f9f;">', 'sort' => '04'],
    'fuentes_informacion_agg' => [ 'name' => 'fuentes_informacion',  'label' => 'Fuentes o Autor', 'enable' => TRUE, 'before' => '', 'sort' => '05'],
    'created_year_agg' => [ 'name' => 'created_year',  'label' => 'Año de producción o elaboración', 'enable' => TRUE, 'before' => '', 'sort' => '06'],
    // 'tipo_norma_agg' => [ 'name' => 'tipo_norma',  'label' => 'Tipo de Norma', 'enable' => TRUE, 'sort' => '04'],
    // 'tipo_documento_agg' => [ 'name' => 'tipo_documento',  'label' => 'Tipo de Publicación', 'enable' => TRUE, 'sort' => '05'],
    // 'tipo_novedad_agg' => [ 'name' => 'tipo_novedad',  'label' => 'Tipo de Novedad', 'enable' => TRUE, 'sort' => '06'],
    // 'tipo_mapa_agg' => [ 'name' => 'tipo_mapa',  'label' => 'Tipo de Mapa', 'enable' => TRUE, 'sort' => '07'],

  ];

  protected ElasticSearch $elasticSearch;

  public function __construct() {
    $this->elasticSearch = new ElasticSearch();
  }

  public function filterInputAggName(array $in) : array {
    $ou = [];
    $names = [];

    foreach (static::ES_AGGS as $row) {
      $names[] = $row['name'];
    }

    foreach ($in as $name => $value) {
      if (array_search($name, $names) !== FALSE) {
        $ou[] = $name . $this->elasticSearch->config->separator . $value;
      }
    }

    return $ou;
  }
}