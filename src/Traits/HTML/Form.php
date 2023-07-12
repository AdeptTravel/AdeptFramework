<?php

namespace AdeptCMS\Traits\HTML;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Traits\HTML\Form
 *
 * Methods to assist with the rendering of HTML for form elements
 *
 * @package AdeptCMS
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
trait Form
{
  /**
   * Get a formatted list of element attribures
   *
   * @param   string  Element type, ie. input, textarea, form, etc.
   * @param   object  Element parameters
   *
   * @return  string  Formatted string of elements attribs to be dropped into
   *                  the HTML tag.
   */
  public static function getAttributes(object $params): string
  {

    $html = '';

    // Note: Some attributes are not set here such as class, data-*, id, and
    //       style.  These are generated elsewhere.
    //
    // name    - Attribute name
    // type    - The value of the attribute
    //           Accepted values:
    //           bool        Displays attribute with a true/false value.  Example <input draggable="true">
    //           attrib      Displays the just the attribute if true.  Example  <input hidden>
    //           int         Displays the attribute as an int.  Example <input tabindex="1">
    //           on-off      Displays the attribute with an on/off value.  Example <input autocomplete="on">
    //           string      Displays the attribute with a string.  Example <input placeholder="This is a place holder.">
    //
    // element - Type of element such as input, select, textarea, etc.
    // for     - Filter based on the input tag, if the input 'type' attribute
    $attributes = [

      // Global html attributes
      /*
       'datalist',
       'fieldset',
       'form',
       */
      ['name' => 'accesskey',       'type' => 'string'],
      ['name' => 'contenteditable', 'type' => 'bool'],
      ['name' => 'dir',             'type' => 'string'],
      ['name' => 'draggable',       'type' => 'bool'],
      ['name' => 'hidden',          'type' => 'attrib'],
      ['name' => 'lang',            'type' => 'string'],
      ['name' => 'spellcheck',      'type' => 'bool'],
      ['name' => 'tabindex',        'type' => 'int'],
      ['name' => 'title',           'type' => 'string'],
      ['name' => 'translate',       'type' => 'yes-no'],

      // Element specific attributes
      ['name' => 'accept',          'type' => 'string'],
      ['name' => 'accept-charset',  'type' => 'string'],
      ['name' => 'alt',             'type' => 'string'],
      ['name' => 'action',          'type' => 'string'],
      ['name' => 'autocomplete',    'type' => 'on-off'],
      ['name' => 'autofocus',       'type' => 'attrib'],
      ['name' => 'checked',         'type' => 'attrib'],
      ['name' => 'cols',            'type' => 'int'],
      ['name' => 'dirname',         'type' => 'string'],
      ['name' => 'disabled',        'type' => 'attrib'],
      ['name' => 'enctype',         'type' => 'string'],
      ['name' => 'form',            'type' => 'string'],
      ['name' => 'formaction',      'type' => 'string'],
      ['name' => 'formenctype',     'type' => 'string'],
      ['name' => 'formmethod',      'type' => 'string'],
      ['name' => 'formnovalidate',  'type' => 'attrib'],
      ['name' => 'formtarget',      'type' => 'string'],
      ['name' => 'height',          'type' => 'string'],
      ['name' => 'indeterminate',   'type' => 'string'],
      ['name' => 'max',             'type' => 'int'],
      ['name' => 'maxlength',       'type' => 'int'],
      ['name' => 'method',          'type' => 'string'],
      ['name' => 'min',             'type' => 'int'],
      ['name' => 'multiple',        'type' => 'attrib'],
      ['name' => 'novalidate',      'type' => 'attrib'],
      ['name' => 'pattern',         'type' => 'string'],
      ['name' => 'placeholder',     'type' => 'string'],
      ['name' => 'readonly',        'type' => 'attrib'],
      ['name' => 'rel',             'type' => 'string'],
      ['name' => 'required',        'type' => 'attrib'],
      ['name' => 'rows',            'type' => 'int'],
      ['name' => 'size',            'type' => 'string'],
      ['name' => 'src',             'type' => 'string'],
      ['name' => 'step',            'type' => 'string'],
      ['name' => 'target',          'type' => 'string'],
      ['name' => 'width',           'type' => 'string'],
      ['name' => 'wrap',            'type' => 'string']
    ];


    foreach ($attributes as $attribute) {

      $key = $attribute['name'];

      if (!empty($params->$key)) {

        $html .= ' ' . $key;

        if ($attribute['type'] != 'attrib') {

          switch ($attribute['type']) {

            case 'bool':
              $html .= '="' . (($params->$key) ? 'true' : 'false') . '"';
              break;

            case 'string':
              $html .= '="' . (string)$params->$key . '"';
              break;

            case 'int':
              $html .= '="' . (int)$params->$key . '"';
              break;

            case 'yes-no':
              $html .= '="' . (($params->$key) ? 'on' : 'off') . '"';
              break;

            case 'none':
              $html .= '="' . (($params->$key) ? '' : 'none') . '"';
              break;
          }
        }
      }
    }

    return ' ' . trim($html);
  }
}
