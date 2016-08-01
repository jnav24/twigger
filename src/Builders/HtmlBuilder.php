<?php
namespace Twigger\Builders;

use \Twig_Extension;
use \Twig_SimpleFunction;

class HtmlBuilder extends Twig_Extension
{
	public function getName()
	{
		return 'html_builder';
	}

	public function getGlobals()
    {
        return array(
            'food' => 'pizza',
        );
    }

	public function getFunctions()
	{
		return array(
			new Twig_SimpleFunction('style', [$this, 'style'], ['is_safe' => ['html']]),
			new Twig_SimpleFunction('script', [$this, 'script'], ['is_safe' => ['html']]),
			new Twig_SimpleFunction('image', [$this, 'image'], ['is_safe' => ['html']]),
		);
	}

	public function style($url, $attributes = array())
	{
		$defaults = array(
			'media' => 'all',
			'type' => 'text/css',
			'rel' => 'stylesheet'
		);
		$attributes = $attributes + $defaults;
		$attributes['href'] = $url;
		return '<link' . $this->attributes($attributes) . '>' . PHP_EOL;
	}

	public function script($url, $attributes = array())
	{
		$attributes['src'] = $url;
		return '<script ' . $this->attributes($attributes) . '></script>' . PHP_EOL;
	}

	public function image($url, $alt = null, $attributes = array())
	{
		$attributes['alt'] = $alt;
		return '<img src="' . $url . '" ' . $this->attributes($attributes) . '>' . PHP_EOL;
	}

	public function attributes($attributes)
	{
		$html = array();

		foreach ((array) $attributes as $key => $value)
		{
			$element = $this->attributeElement($key, $value);

			if ( ! is_null($element)) $html[] = $element;
		}

		return count($html) > 0 ? ' '.implode(' ', $html) : '';
	}

	private function attributeElement($key, $value)
	{
		if (is_numeric($key)) $key = $value;

		if ( ! is_null($value)) return $key.'="'. $value .'"';
	}
}