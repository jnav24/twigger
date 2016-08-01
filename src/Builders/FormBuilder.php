<?php
namespace Twigger\Builders;

use \Twig_Extension;
use \Twig_SimpleFunction;

class FormBuilder extends Twig_Extension
{
	private $html;

	public function getName()
	{
		return 'form_builder';
	}

	public function getFunctions()
	{
		return array(
			new Twig_SimpleFunction('select', [$this, 'select'], ['is_safe' => ['html']]),
			new Twig_SimpleFunction('input', [$this, 'input'], ['is_safe' => ['html']]),
			new Twig_SimpleFunction('text', [$this, 'text'], ['is_safe' => ['html']]),
			new Twig_SimpleFunction('password', [$this, 'password'], ['is_safe' => ['html']]),
			new Twig_SimpleFunction('email', [$this, 'email'], ['is_safe' => ['html']]),
			new Twig_SimpleFunction('hidden', [$this, 'hidden'], ['is_safe' => ['html']]),
			new Twig_SimpleFunction('radio', [$this, 'radio'], ['is_safe' => ['html']]),
		);
	}

	public function __construct()
	{
		$this->html = new HtmlBuilder();
	}

	public function select($name, $list = array(), $selected = null, $options = array())
	{
		$options['name'] = $name;
		$html = array();

		foreach ($list as $value => $display)
		{
			$html[] = $this->getSelectOption($display, $value, $selected);
		}

		$options = $this->html->attributes($options);

		$list = implode('', $html);

		return "<select{$options}>{$list}</select>";
	}

	public function input($type, $name, $value = null, $options = array())
	{
		$options['name'] = $name;
		$options['value'] = $value;
		$options['type'] = $type;
		$options['id'] = $this->getIdAttribute($name, $options);

		return '<input ' . $this->html->attributes($options) . '>';
	}

	public function text($name, $value = null, $options = array())
	{
		return $this->input('text', $name, $value, $options);
	}

	public function password($name, $options = array())
	{
		return $this->input('password', $name, '', $options);
	}

	public function email($name, $value = null, $options = array())
	{
		return $this->input('email', $name, $value, $options);
	}

	public function hidden($name, $value = null, $options = array())
	{
		return $this->input('hidden', $name, $value, $options);
	}

	public function radio($name, $value = null, $options = array())
	{
		return $this->input('radio', $name, $value, $options);
	}

	private function getIdAttribute($name, $options)
	{
		if(array_key_exists('id', $options))
		{
			return $options['id'];
		}

		return $name;
	}

	private function getSelectOption($display, $value, $selected)
	{
		if (is_array($display))
		{
			return $this->optionGroup($display, $value, $selected);
		}

		return $this->option($display, $value, $selected);
	}

	private function optionGroup($list, $label, $selected)
	{
		$html = array();

		foreach ($list as $value => $display)
		{
			$html[] = $this->option($display, $value, $selected);
		}

		return '<optgroup label="'. $label .'">'.implode('', $html).'</optgroup>';
	}

	private function option($display, $value, $selected)
	{
		$selected = $this->getSelectedValue($value, $selected);

		$options = array('value' => $value, 'selected' => $selected);

		return '<option'.$this->html->attributes($options).'>'. $display .'</option>';
	}

	private function getSelectedValue($value, $selected)
	{
		if (is_array($selected))
		{
			return in_array($value, $selected) ? 'selected' : null;
		}

		return ((string) $value == (string) $selected) ? 'selected' : null;
	}
}