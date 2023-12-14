<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Video extends \Adept\Abstract\Document\HTML\Element
{

	protected string $tag = 'video';

	// Element Specific Attributes
	public bool   $autoplay;
	public bool   $controls;
	public string $crossorigin;
	public int    $height;
	public bool   $loop;
	public bool   $muted;
	public string $poster;
	public string $preload;
	public string $src;
	public int    $width;

}