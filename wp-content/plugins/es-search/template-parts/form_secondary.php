<?php
/**
 * The template for displaying search results.
 *
 * @package es-search
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}
?>

<div class="searchAjax__aggs custom-select2" type="<?= $args['type'] ?>"></div>

<div class="text-align-right d-none searchAjax__otherBtns">
  <?php if ($args['type'] == 'mobile'):  ?>
    <button class="btn btn-sm btn-primary btn-aplicar" data-bs-dismiss="offcanvas">Aplicar</button>
  <?php endif; ?>
  <button class="btn btn-secondary btn-sm filter_button_reset">Limpiar</button>
</div>