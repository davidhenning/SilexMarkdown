<?php

namespace SilexMarkdown\Tests;

use Silex\Application,
    Silex\Provider\TwigServiceProvider;

use SilexMarkdown\Provider\MarkdownServiceProvider;

class SilexMarkdownTest extends \PHPUnit_Framework_TestCase {

    public function testTransform() {
        $app = new Application();
        $app->register(new MarkdownServiceProvider());
        $text = "# Headline";

        $this->assertInstanceOf('\SilexMarkdown\MarkdownParser', $app['markdown']);
        $this->assertContains('<h1>Headline</h1>', $app['markdown']->transform($text));
    }

    public function testTwigExtension() {
        $app = new Application();
        $app->register(new TwigServiceProvider());
        $app->register(new MarkdownServiceProvider());

        $twig = $app['twig'];
        $ext = $twig->getExtension('markdown');

        $this->assertInstanceOf('\Twig_Environment', $twig);
        $this->assertInstanceOf('\Twig_Extension', $ext);
        $this->assertTrue(method_exists($ext, 'markdown'));
        $this->assertTrue(array_key_exists('markdown', $ext->getFilters()));
    }
}
