<?php
namespace Drupal\Laravel\ContentRender;
/**
 * Content Render
 */
class ContentRender {
  /**
   * Data to be rendered.
   *
   * @var array
   */
  private $data;

  public function setData($data) {
    $this->data = $data;

    if (is_array($this->data) && empty($this->data['variables'])) {
      $this->data['variables'] = array();
    }

    return $this;
  }

  public function getData() {

    return $this->data;
  }
  /**
   * Render content.
   *
   * @param  string|array $data
   *   Content to render.
   *
   * @return string
   */
  public function render($data = '') {
    if (!is_null($data)) {
      $this->setdata($data);
    }

    return $this->build();
  }

  private function renderTemplate($template_file, $variables, $options = array()) {
    if (!is_file($template_file)) {
      return "";
    }
    $engines = app('view')->getExtensions();
    if (in_array($options['engine'], array_values($engines))) {
      return app('view')->make($template_file, $variables);
    } else {
      return theme_render_template($template_file, $variables);
    }
  }

  /**
   * Get template variables.
   *
   * @return array
   *   Array of variables.
   */
  function getVariables(){
    return isset($this->data['variables']) ? $this->data['variables'] : array();
  }

  /**
   * Process template.
   *
   * @return array
   *   Render array.
   */
  private function processTemplate() {
    if (isset($this->data['template_file'])) {
      $tpl = illuminate_realpath($this->data['template_file']);
      $options['engine'] = isset($this->data['engine']) ? $this->data['engine'] : 'php';

      return $this->renderTemplate($tpl, $this->getVariables() , $options);
    }

    if (isset($this->data['template'])) {
      return $this->data['template'];
    }

    return '';
  }

  public function build() {
    if (is_string($this->data)) {

      return $this->data;
    }

    $return = $this->processTemplate();
    // Attach assets.
    if (is_array($this->data) && !empty($this->data['attached'])) {
      $return = is_array($return) ? $return : array(
        '#markup' => $return
      );

      if (isset($return['#attached'])) {
        $return['#attached'] = array_merge_recursive($return['#attached'], $this->buildAttached());
      } else {
        $return['#attached'] = $this->buildAttached();
      }
    }


    return $return;
  }

  protected function buildAttached() {

    foreach (array_keys($this->data['attached']) as $type) {

      foreach ($this->data['attached'][$type] as $k => $item) {
        if (is_string($item)) {
          $this->data['attached'][$type][$k] = array(
            'data' => illuminate_realpath($item) ,
            'group' => ($type == 'css') ? CSS_THEME : JS_THEME
          );
        }
      }
    }


    return $this->data['attached'];
  }
}
