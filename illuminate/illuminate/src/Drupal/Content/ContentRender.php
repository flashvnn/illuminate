<?php
namespace Drupal\Content;


class ContentRender implements ContentRenderInterface{
  private $data;
  /**
   * Render content.
   *
   * @param  string|array $data
   *   Content to render.
   *
   * @return string Rendered data.
   */
  public function render($data = "") {
    $this->data = $data;
    return $this->build();
  }

  private function build(){
    if (is_string($this->data)) {
      return $this->data;
    }
    $return = array();

    if (is_array($this->data)) {
      if ($controller = array_get($this->data, 'content._controller')) {
        $controller_data = illuminate_controller($controller);
        if ($controller_data instanceof \Illuminate\Contracts\View\View) {
          /** @var $controller_data \Illuminate\Contracts\View\View */
          $return['#markup'] = $controller_data->render();
        }
      }
      else if ($view = array_get($this->data, 'content.view')) {
        $return['#markup'] = view($view)->render();
      }
      else if ($content_data = array_get($this->data, 'content.data')) {
        $return['#markup'] = $content_data;
      }else{
        $return['content'] = $this->data;
      }

      if (isset($this->data['attached'])) {
        $return['#attached'] = $this->buildAttached();
      }
    }

    return $return;
  }

  /**
   * Build attached css and js for content.
   *
   * @return mixed
   */
  protected function buildAttached() {
    foreach (array_keys($this->data['attached']) as $type) {
      foreach ($this->data['attached'][$type] as $k => $item) {
        if (is_string($item)) {
          $this->data['attached'][$type][$k] = array(
            'data' => \Drupal::realPath($item),
            'group' => ($type == 'css') ? CSS_THEME : JS_THEME
          );
        }
      }
    }

    return $this->data['attached'];
  }
}