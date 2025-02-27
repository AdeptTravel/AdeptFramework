<?php

namespace Adept\Document\HTML\Elements\Form;

defined('_ADEPT_INIT') or die();

use Adept\Application;
use \Adept\Document\HTML\Elements\Button;
use \Adept\Document\HTML\Elements\Div;
use \Adept\Document\HTML\Elements\TextArea;
use \Adept\Document\HTML\Elements\Script;
use \Adept\Document\HTML\Elements\Select;
use \Adept\Document\HTML\Elements\Span;

class WYSIWYG extends \Adept\Document\HTML\Elements\Div
{
  // Form element attributes
  public string $name;
  public bool   $required = false;
  public bool
    $autocomplete;
  public bool   $autofocus;
  public bool   $disabled;
  public string $value = '';

  public int    $height;
  public int    $maxlength;
  public int    $minlength;
  public string $placeholder;
  public int    $width;
  // Custom param
  public array  $optionShowOn = [];
  public array  $optionHideOn = [];
  // Duplicate, here for reference only
  //public bool   $required; 

  public bool $font = false;
  public bool $fontsize = false;
  public bool $bold = true;
  public bool $italic = true;
  public bool $underline = true;
  public bool $strike = false;
  public bool $color = false;
  public bool $background = false;
  public bool $subscript = false;
  public bool $superscript = false;
  public bool $h1 = false;
  public bool $h2 = false;
  public bool $h3 = false;
  public bool $h4 = false;
  public bool $h5 = false;
  public bool $blockquote = false;
  public bool $codeblock = false;
  public bool $list = false;
  public bool $indent = false;
  public bool $align = false;
  public bool $link = false;
  public bool $image = false;
  public bool $video = false;
  public bool $clean = true;

  public function getBuffer(): string
  {
    if (empty($this->id)) {
      $id = 'formWUSIWYG' . $this->name;
      $this->id = $id;
    } else {
      $id = $this->id;
    }

    $html = $this->value;
    unset($this->value);

    $app = Application::getInstance();
    $app->html->head->javascript->addFile('https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js');

    $app->html->head->css->addAsset('Core/Form/WYSIWYG');
    $app->html->head->css->addFile('https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css');
    //$app->html->head->css->addAsset('Core/Form/WYSIWYG/Snow');
    //$app->html->head->javascript->addAsset('Core/Form/WYSIWYG/Quill');


    $this->css[]   = 'wysiwyg';

    //
    // Toolbar
    //
    $toolbar = new Div(['css' => ['toolbar']]);

    if ($this->font || $this->fontsize) {
      $toolbar->children[] = new Span(['css' => ['ql-formats']]);

      if ($this->font) {
        end($toolbar->children)->children[] = new Select(['css' => ['ql-font'], 'title' => 'Font Family']);
      }

      if ($this->fontsize) {
        end($toolbar->children)->children[] = new Select(['css' => ['ql-size'], 'title' => 'Font Size']);
      }
    }

    if ($this->bold || $this->italic || $this->underline || $this->strike) {
      $toolbar->children[] = new Span(['css' => ['ql-formats']]);

      if ($this->bold) {
        end($toolbar->children)->children[] = new Button(['css' => ['ql-bold'], 'title' => 'Bold']);
      }

      if ($this->italic) {
        end($toolbar->children)->children[] = new Button(['css' => ['ql-italic'], 'title' => 'Italic']);
      }

      if ($this->underline) {
        end($toolbar->children)->children[] = new Button(['css' => ['ql-underline'], 'title' => 'Underline']);
      }

      if ($this->strike) {
        end($toolbar->children)->children[] = new Button(['css' => ['ql-strike'], 'title' => 'Strike Through']);
      }
    }

    if ($this->color || $this->background) {
      $toolbar->children[] = new Span(['css' => ['ql-formats']]);

      if ($this->color) {
        end($toolbar->children)->children[] = new Select(['css' => ['ql-color']]);
      }

      if ($this->background) {
        end($toolbar->children)->children[] = new Select(['css' => ['ql-background']]);
      }
    }

    if ($this->subscript || $this->superscript) {
      $toolbar->children[] = new Span(['css' => ['ql-formats']]);

      if ($this->subscript) {
        end($toolbar->children)->children[] = new Button(['css' => ['ql-script'], 'value' => 'sub']);
      }

      if ($this->superscript) {
        end($toolbar->children)->children[] = new Button(['css' => ['ql-script'], 'value' => 'super']);
      }
    }

    if ($this->h1 || $this->h2 || $this->h3 || $this->h4 || $this->h5) {
      $toolbar->children[] = new Span(['css' => ['ql-formats']]);

      if ($this->h1)
        end($toolbar->children)->children[] = new Button(['css' => ['ql-header'], 'value' => '1']);

      if ($this->h2)
        end($toolbar->children)->children[] = new Button(['css' => ['ql-header'], 'value' => '2']);

      if ($this->h3)
        end($toolbar->children)->children[] = new Button(['css' => ['ql-header'], 'value' => '3']);

      if ($this->h4)
        end($toolbar->children)->children[] = new Button(['css' => ['ql-header'], 'value' => '4']);

      if ($this->h5)
        end($toolbar->children)->children[] = new Button(['css' => ['ql-header'], 'value' => '5']);
    }


    if ($this->blockquote || $this->codeblock) {
      $toolbar->children[] = new Span(['css' => ['ql-formats']]);

      if ($this->blockquote)
        end($toolbar->children)->children[] = new Button(['css' => ['ql-blockquote']]);

      if ($this->codeblock)
        end($toolbar->children)->children[] = new Button(['css' => ['ql-code-block']]);
    }

    $toolbar->children[] = new Span(['css' => ['ql-formats']]);
    end($toolbar->children)->children[] = new Button(['css' => ['ql-list'], 'value' => 'bullet']);
    end($toolbar->children)->children[] = new Button(['css' => ['ql-list'], 'value' => 'ordered']);
    end($toolbar->children)->children[] = new Button(['css' => ['ql-indent'], 'value' => '-1']);
    end($toolbar->children)->children[] = new Button(['css' => ['ql-indent'], 'value' => '+1']);

    $toolbar->children[] = new Span(['css' => ['ql-formats']]);
    end($toolbar->children)->children[] = new Select(['css' => ['ql-align']]);

    $toolbar->children[] = new Span(['css' => ['ql-formats']]);
    end($toolbar->children)->children[] = new Button(['css' => ['ql-link']]);
    end($toolbar->children)->children[] = new Button(['css' => ['ql-image']]);
    end($toolbar->children)->children[] = new Button(['css' => ['ql-video']]);

    $toolbar->children[] = new Span(['css' => ['ql-formats']]);
    end($toolbar->children)->children[] = new Button(['css' => ['ql-clean'], 'title' => 'Cleanup ']);

    $editor = new Div([
      'css'  => ['editor'],
      'html' => $html
    ]);

    //$source = new TextArea(['name' => $this->name, 'css' => ['source']]);
    $source = new TextArea([
      'name' => $this->name,
      'css'  => ['source'],
      'html' => $html
    ]);

    $toggle = new Div(['css' => ['toggle']], [
      new Button([
        'css'  => ['toggle'],
        'text' => 'Toggle Editor'
      ])
    ]);
    /*
modules: {
    toolbar: [
      [{ header: [1, 2, false] }],
      ['bold', 'italic', 'underline'],
      ['image', 'code-block'],
    ],
  },
  placeholder: 'Compose an epic...',
  theme: 'snow', // or 'bubble'
});
    */

    $params = (object)[
      'theme' => 'snow',
      'placeholder' => (!empty($this->placeholder)) ? $this->placeholder : '',
      'modules' => (object) [
        'toolbar' => "#$id .toolbar"
      ]
    ];

    $script = new Script();
    $script->text = "

    const $id = new Quill('#$id .editor', " . json_encode($params) . ");
    
    // d = Delta, o = Old Delta, s = Source
    $id.on('text-change', (d, o, s) => {
      if (s == 'user') {
        q('#$id .source').value = $id.getSemanticHTML();
      }
    });

    q('#$id button.toggle').addEventListener('click', (ev) => {
      ev.preventDefault();

      var toolbar = q('#$id .toolbar');
      var editor = q('#$id .editor');
      var source = q('#$id .source');

      if (source.style.display == 'none' || source.style.display == '') {
        source.style.width = editor.offsetWidth+ 'px';
        source.style.height = (toolbar.clientHeight + editor.clientHeight) + 'px';

        source.style.display = 'block';
        editor.style.display = 'none';
        toolbar.style.display = 'none';
      } else {
        source.style.display = 'none';
        editor.style.display = 'block';
        toolbar.style.display = 'block';
        $id.clipboard.dangerouslyPasteHTML(q('#$id .source').value, 'api');
      }

    });
    ";

    $this->children = [
      $toolbar,
      $editor,
      $source,
      $toggle,
      $script
    ];

    return parent::getBuffer();
  }
}
