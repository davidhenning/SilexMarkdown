<?php

namespace SilexMarkdown\Tests;

use Silex\Application;

use SilexMarkdown\Provider\SilexServiceProvider;

class SilexMarkdownTest extends \PHPUnit_Framework_TestCase {

    public function testTransform() {
        $app = new Application();
        $app->register(new SilexServiceProvider());
        $text = "# Headline";

        $this->assertInstanceOf('\SilexMarkdown\MarkdownParser', $app['markdown']);
        $this->assertContains('<h1>Headline</h1>', $app['markdown']->transform($text));
    }

}
