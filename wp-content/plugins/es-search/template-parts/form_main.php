<?php
/**
 * The template for displaying search results.
 *
 * @package es-search
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

$content_type = 'all';
if (isset($args['data']['form']['content_type'])) {
  $content_type = $args['data']['form']['content_type'];
}
?>

<form method="post">
  <div class="input-group p-1 rounded-pill overflow-hidden border border-1  mb-md-5 mb-3">
    <input id="filter_query"
           class="form-control border-0 rounded-end rounded-pill"
           placeholder="¿Qué estas buscando?" aria-label="¿Qué estas buscando?" aria-describedby="button-search"
           type="text" value="<?= $args['data']['form']['s'] ?>">
    <button id="filter_button"
            class="btn btn-primary rounded-start rounded-pill"
            type="button" >Buscar</button>
  </div>

  <div class="d-none d-md-block">
    <div class="row mx-0 mb-4 bg-light">
      <?php foreach ($args['data']['contentType'] as $i) : ?>
        <?php
        $checked = 'btn-outline-primary';
        if ($i['name'] == $content_type) {
          $checked = 'btn-primary';
        }
        ?>
        <div class="col p-0">
          <button type="button"
                  id = "filter_contentType_<?= $i['name'] ?>"
                  class="btn btn-sm  <?= $checked ?> m-0 border-end-0 border-start-0 border-bottom-0 rounded-0 bg-grey w-100 filter_contentType"
                  data-value="<?= $i['name'] ?>"
                  data-toggle="button">
            <?= $i['label'] ?>
          </button>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</form>
