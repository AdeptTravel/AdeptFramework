<?php

namespace Adept\Abstract\Configuration\Component;

defined('_ADEPT_INIT') or die();

class Controls
{

  //
  // List view
  //


  public bool $delete     = false;
  public bool $duplicate  = false;
  public bool $edit       = false;
  public bool $new        = false;
  public bool $publish    = false;
  public bool $unpublish  = false;

  //
  // Item View
  //
  public bool $save       = false;
  public bool $saveclose  = false;
  public bool $savecopy   = false;
  public bool $savenew    = false;
  public bool $close      = false;

  //
  // Media List
  //
  public bool $upload     = false;
  public bool $newdir     = false;
}
