<?php

namespace AdeptCMS\Document\HTML\Body\Element\Select;

defined('_ADEPT_INIT') or die();

class Component extends \AdeptCMS\Document\HTML\Body\Element\Select
{
  public function getOptions(): array
  {
    $options = [];

    foreach (scandir(FS_COMPONENT) as $fs) {
      if ($fs == '.' || $fs == '..' || is_file($fs)) {
        continue;
      }

      if (file_exists(FS_COMPONENT . $fs . '/' . $this->params->area)) {
        $options[] = (object)[
          'value' => $fs,
          'title' => $fs
        ];
      }
    }

    return $options;
  }
}
