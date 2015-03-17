<?php
/**
 * Created by PhpStorm.
 * User: thaohuynh
 * Date: 3/12/15
 * Time: 15:46
 */

namespace Drupal\Content;


interface ContentRenderInterface {
  /**
   * Render content.
   *
   * @param  string|array $data
   *   Content to render.
   *
   * @return string Rendered data.
   */
  public function render($data = "");
}