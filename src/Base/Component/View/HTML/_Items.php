<?php

namespace AdeptCMS\Base\Component\View\HTML;

defined('_ADEPT_INIT') or die('No Access');

class Items extends \AdeptCMS\Base\Component\View\HTML
{

  public function getBuffer(string $template = ''): string
  {
    $html = '';
    $parts = explode("\\", get_class($this));

    $file = FS_COMPONENT
      . $this->app->session->request->route->component . '/'
      . $this->app->session->request->route->area . '/'
      . 'Template' . '/'
      . $parts[count($parts) - 1] . '.json';

    if (file_exists($file)) {

      $this->doc->head->css->addCSS('/admin/css/items.css');

      $json = json_decode(file_get_contents($file));



      $html = '<table class="items"><thead><tr>';

      for ($i = 0; $i < count($json->table); $i++) {
        $html .= '<th>';

        if (isset($json->table[$i]->sort) && $json->table[$i]->sort) {
          $url = $this->app->session->request->url;
          $url->setQuery('sort', $json->table[$i]->col);
          $url->setQuery('order', (($url->getQuery('order') == 'asc') ? 'desc' : 'asc'));

          $html .= '<a href="' . $url->raw . '">';
        }

        if (!empty($json->table[$i]->title)) {
          $html .= $json->table[$i]->title;
        }

        if (isset($json->table[$i]->sort) && $json->table[$i]->sort) {
          echo '</a>';
        }

        $html .= '</th>';
      }

      $html .= '</tr></thead><tbody>';

      for ($i = 0; $i < count($this->model->items); $i++) {
        $data = $this->model->items[$i];

        $html .= '<tr>';
        for ($j = 0; $j < count($json->table); $j++) {
          $inner = '';
          $css = 'data';

          if (isset($json->table[$j]->main)) {
            $css .= ' main';
          }

          if ($json->table[$j]->type == 'select') {
            $css = 'control';
            $inner .= '<input type="checkbox">';
          } else if ($json->table[$j]->type == 'publish') {
            $css = 'control';

            $url->setQuery('id', $data->id);

            $inner .= '<a href="';

            if ($data->publish) {
              $url->setQuery('action', 'unpublish');

              $inner .= $url->raw . '">';
              $inner .= '<i class="fa-solid fa-circle-check"></i>';
            } else {
              $css = 'control';

              $url->setQuery('action', 'publish');

              $inner .= $url->raw . '">';
              $inner .= '<i class="fa-solid fa-circle-xmark"></i>';
            }

            $url->delQuery('id');
            $url->delQuery('action');

            $inner .= '</a>';
          } else if ($json->table[$j]->type == 'text') {
            $col = $json->table[$j]->col;
            $inner .= $data->$col;
          } else if ($json->table[$j]->type == 'order') {
            $css = 'control';
            $inner .= '<i class="fa-solid fa-ellipsis-vertical"></i>';
          } else if ($json->table[$j]->type == 'delete') {
            $css = 'control';
            $inner .= '<a href="#">';
            $inner .= '<i class="fa-solid fa-trash-can"></i>';
            $inner .= '</a>';
          }

          $html .= '<td';

          if (!empty($css)) {
            $html .= ' class="' . $css . '"';
          }

          $html .= '>' . $inner . '</td>';
        }
        $html .= '</tr>';
      }

      $html .= '</tbody></table>';
    } else {
      $html = parent::getBuffer($template);
    }

    return $html;
  }

  public function onRenderStart()
  {
    parent::onRenderStart();
    //$this->addCSSFile('/admin/css/form/elements.css');
    $this->addCSSFile('/admin/css/items.css');
    $this->addCSSFile('/admin/css/items/filter.css');
    $this->addJavaScriptFile('/admin/js/items/filter.js');

    if ($this->getParam('sortable')) {
      $this->addJavaScriptFile('/admin/js/sortablejs.min.js');
      $this->addJavaScriptFile('/admin/js/list/sort.js');
    }
  }

  public function renderTable()
  {
  }
}
