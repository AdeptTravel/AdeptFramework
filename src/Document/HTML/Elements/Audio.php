<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Audio extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'audio';

	// Element Specific Attributes
	public bool   $autoplay;
	public bool   $controls;
	public string $crossorigin;
	public bool   $loop;
	public bool   $muted;
	public string $preload;
	public string $src;

}