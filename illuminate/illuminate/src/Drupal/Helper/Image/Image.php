<?php
/**
 * Created by PhpStorm.
 * User: thaohuynh
 * Date: 2/25/15
 * Time: 02:54
 */

namespace Drupal\Helper\Image;


class Image {

  /**
   * Build image url from URI.
   *
   * @param $uri
   *
   * @return string
   * @throws \Exception
   */
  public function buildUrl($uri) {
    return file_create_url($uri);
  }

  /**
   * Build image url from URI.
   *
   * @param $style_name
   * @param $uri
   *
   * @return string
   * @throws \Exception
   */
  public function buildStyleUrl($style_name, $uri) {
    return image_style_path($style_name, $uri);
  }

  /**
   * @param $uri
   * @param string $alt
   * @param string $title
   * @param string $class
   *
   * @return string
   * @throws \Exception
   */
  public function render($uri, $alt = "", $title = "", $class = "", $options = array()) {
    $image = array(
      'path'       => $uri,
      'alt'        => $alt,
      'title'      => $title,
      'attributes' => array('class' => array($class)),
    );
    $image = $image + $options;

    return theme('image', $image);
  }

  /**
   * @param $uri
   * @param $style_name
   * @param string $alt
   * @param string $title
   * @param string $class
   * @param array $options
   *
   * @return string
   * @throws \Exception
   */
  public function renderStyle($uri, $style_name, $alt = "", $title = "", $class = "", $options = array()) {
    $image = array(
      'style_name' => $style_name,
      'path'       => $uri,
      'alt'        => $alt,
      'title'      => $title,
      'attributes' => array('class' => array($class)),
    );
    $image = $image + $options;

    return theme('image_style', $image);
  }
}