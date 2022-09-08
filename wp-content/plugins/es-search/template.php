<?php
/**
 * The template for displaying search results.
 *
 * @package HelloElementor
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

use EsSearch\Service\ContentService;

$contentService = new ContentService();
$url = [];
$uri = [];

$tpl = '../../plugins/es-search';


$uri['base'] = network_site_url('/', 'relative');
$uri['api'] = "{$uri['base']}wp-json/es-search/v1/data";
$url['base'] = get_site_url() . '';
$url['assets'] = "{$url['base']}/wp-content/plugins/es-search/assets";

$form = $_REQUEST;
$aggs = $contentService->filterInputAggName($form);


$data = [
  'form' => $form,
  'aggs' => json_encode($aggs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
  'separator' => '<|-|>',
  'contentType' => [
    [ 'name' => 'all', 'label' => 'Todo'],
    [ 'name' => 'normatividad', 'label' => 'Normas'],
    [ 'name' => 'documentos' , 'label' => 'Publicaciones'],
    [ 'name' => 'novedades' , 'label' => 'Novedades'],
    [ 'name' => 'mapas' , 'label' =>  'Mapas'],
    [ 'name' => 'indicadores_ambientales' , 'label' =>  'Indicadores'],
  ]
];

$content_type = 'all';
if (isset($data['form']['content_type'])) {
  $content_type = $data['form']['content_type'];
}

$es_search_options = get_option( 'es_search_option_name' );



wp_enqueue_style('bootstrap', "{$url['assets']}/bootstrap/5.1.3/bootstrap.min.css", FALSE, '5.1.3', 'all');
wp_enqueue_script( 'bootstrap', "{$url['assets']}/bootstrap/5.1.3/bootstrap.min.js", TRUE, '5.1.3', FALSE);
wp_enqueue_script( 'fontawesome_all', "{$url['assets']}/fontawesome/5.13.1/js/all.min.js", FALSE, '5.13.1', FALSE);
wp_enqueue_script( 'fontawesome_shims', "{$url['assets']}/fontawesome/5.13.1/js/v4-shims.min.js", FALSE, '5.13.1', FALSE);

wp_enqueue_style('select2', "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css", FALSE, '4.1.0', 'all');
wp_enqueue_script( 'select2', "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js", TRUE, '4.1.0', FALSE);

wp_enqueue_script( 'custom_libs', "{$url['assets']}/custom/lib.js", FALSE, '1.0', FALSE);
wp_enqueue_script( 'custom_search', "{$url['assets']}/custom/search.js", TRUE, '1.0.2', TRUE);

wp_enqueue_style('custom', "{$url['assets']}/custom/main.css", TRUE, '1.0.1', 'all');
?>

<input type="hidden" id="filter_siteId" value="<?= $es_search_options['id_1'] ?>">
<input type="hidden" id="filter_separator" value="<?= $data['separator'] ?>">
<main id="content" class="site-main" role="main">
  <div class="row my-5">
    <div class="col-12">
      <div class="offcanvas offcanvas-start bg-light d-lg-none zindex" tabindex="-1" id="offcanvas" data-bs-keyboard="false" data-bs-backdrop="false">
        <div class="offcanvas-header">
          <h6 class="offcanvas-title d-none d-sm-block" id="offcanvas">Filtros</h6>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body px-0">
          <div class="p-2">
            <div class="mb-3">
              <label class="d-block custom-select2">
                Tipo de Información
                <select style="width: 100%"
                        id="filter_contentType">
                  <?php foreach ($data['contentType'] as $i) : ?>
                    <?php
                    $selected = '';
                    if ($i['name'] == $content_type) {
                      $selected = 'selected';
                    }
                    ?>
                    <option id = "filter_contentType_mobile_<?= $i['name'] ?>" value="<?= $i['name'] ?>" <?= $selected ?> >
                      <?= $i['label'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </label>
            </div>

            <?php get_template_part( "{$tpl}/template-parts/form_secondary" , NULL, [ 'type' => 'mobile'] ); ?>
          </div>
        </div>
      </div>
      <textarea id ="filter_aggs" class="d-none" cols="30" rows="15"><?= $data['aggs'] ?></textarea>

      <div class="container-fluid">
        <div class="row m-0">
          <div class="col-lg-4 d-none d-lg-block">
            <div class="rounded-3 bg-light p-4">
              <?php get_template_part( "{$tpl}/template-parts/form_secondary" , NULL, [ 'type' => 'desktop'] ); ?>

            </div>
          </div>
          <div class="col-12 col-lg-8">
            <?php get_template_part( "{$tpl}/template-parts/form_main" , NULL, ['data' => $data]); ?>
            <button class="btn btn-primary d-block d-lg-none float-end"
                    data-bs-toggle="offcanvas" data-bs-target="#offcanvas"
                    title="Filtros"
                    role="button">
              <span data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-label="Filtros"><i class="fa fa-bars"></i></span>
            </button>

            <?php get_template_part( "{$tpl}/template-parts/ajax_content", NULL, ['uri' => $uri] ); ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>