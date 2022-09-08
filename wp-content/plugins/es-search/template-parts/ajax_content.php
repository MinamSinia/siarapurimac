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
<div id="searchAjax"
     data-url="<?= $args['uri']['api'] ?>"
     data-url-home="<?= $args['uri']['base'] ?>">
  <div id="searchAjax__count" class="py-2 mb-3" style="display: none">
    Mostrando <span class="_from"></span> de <span class="_last"></span> resultados de <span class="_count"></span>
  </div>
  <div id="searchAjax__body"></div>
  <div id="searchAjax__paginate"
       class="align-right"
       data-count="0"></div>
</div>