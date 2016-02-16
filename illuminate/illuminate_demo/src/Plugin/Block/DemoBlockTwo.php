<?php
/**
 * Created by PhpStorm.
 * User: thaohuynh
 * Date: 3/20/15
 * Time: 10:53
 */

namespace Drupal\illuminate_demo\Plugin\Block;


use Drupal\Core\Block\BlockBase;

class DemoBlockTwo extends BlockBase{

  /**
   * Define block settings.
   *
   * @return array
   */
  public function defineSettings() {
    return array(
      'info' => t("DemoBlockTwo"),
      'cache' => DRUPAL_CACHE_PER_ROLE,
    );
  }


  /**
   * Block title.
   *
   * @return string
   */
  public function title() {
    return "Illuminate Demo Block Title";
  }

  public function build() {
    $build = array();
    $build['#markup'] = "DEMO BLOCK CONTENT";

    return $build;
  }

}