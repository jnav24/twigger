<?php

namespace Twigger;

use \Twig_Environment;
use \Twig_Loader_Filesystem;
use \Twig_SimpleFunction;
use \Twig_SimpleFilter;
use Twigger\Builders\FormBuilder;
use Twigger\Builders\HtmlBuilder;
use Twigger\Builders\TestBuilder;
use Twigger\Exceptions\TwigException;

class Twigger
{
    /**
     * @var string
     */
    private $htmlPath;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Twig Environment implementation
     * @var Twig Environment
     */
    protected $twig;


    public function __construct($htmlPath)
    {
        $this->setHtmlPath($htmlPath);
        $this->initTwig();
    }

    private function setHtmlPath($htmlPath)
    {
        if(!file_exists($htmlPath))
        {
            throw new TwigException('Path does not exist');
        }

        $this->htmlPath = $htmlPath;
    }

    public function render($file, $options)
    {
        $options = array_merge($this->options, $options);
        $options['all'] = array_keys($options);
        return $this->twig->render($file, $options);
    }

    protected function createSimpleFunc($name, $function, array $options = array())
    {
        if(!is_callable($function))
        {
            throw new TwigException('Second param must be a function');
        }

        return $this->twig->addFunction(new Twig_SimpleFunction($name, $function, $options));
    }

    protected function createSimpleFilter($name, $function, array $options = array())
    {
        if(!is_callable($function))
        {
            throw new TwigException('Second param must be a function');
        }

        return $this->twig->addFilter(new Twig_SimpleFilter($name, $function, $options));
    }

    private function initTwig()
    {
        // Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem($this->htmlPath);
        $this->twig = new Twig_Environment($loader, array());
        $this->twig->addExtension(new FormBuilder());
        $this->twig->addExtension(new HtmlBuilder());
    }
}