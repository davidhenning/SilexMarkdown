<?php

namespace SilexMarkdown\Tests;

use Silex\Application,
    Silex\Provider\TwigServiceProvider;

use SilexMarkdown\Parser\AmplifyrParser,
    SilexMarkdown\Parser\MarkdownExtraExtendedParser,
    SilexMarkdown\Filter\RadiantFilter,
    SilexMarkdown\Filter\EssenceFilter,
    SilexMarkdown\Filter\PygmentsFilter,
    SilexMarkdown\Provider\MarkdownServiceProvider;

class SilexMarkdownTest extends \PHPUnit_Framework_TestCase
{

    public function testTransform()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider(), array(
            'sm.markdown.parser' => new MarkdownExtraExtendedParser()
        ));
        $app->register(new TwigServiceProvider());
        $text = "# Headline";

        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['sm.markdown']);
        $this->assertContains('<h1>Headline</h1>', $app['sm.markdown']->transform($text));
    }

    public function testTransformWithoutParserInjection()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider());
        $text = "# Headline";

        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['sm.markdown']);
        $this->assertContains('<h1>Headline</h1>', $app['sm.markdown']->transform($text));
    }

    public function testAmplifyr()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider(), array(
            'sm.markdown.parser' => new AmplifyrParser()
        ));
        $text = "# Headline";

        $this->assertInstanceOf('\SilexMarkdown\Parser\AmplifyrParser', $app['sm.markdown']);
        $this->assertContains('<h1>Headline</h1>', $app['sm.markdown']->transform($text));
    }

    public function testAmplifyrWithTwigExtension()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider(), array(
            'sm.markdown.parser' => new AmplifyrParser()
        ));
        $text = "# Headline";

        $this->assertInstanceOf('\SilexMarkdown\Parser\AmplifyrParser', $app['sm.markdown']);
        $this->assertContains('<h1>Headline</h1>', $app['sm.markdown']->transform($text));
    }

    public function testImage()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider(), array(
            'sm.markdown.parser' => new MarkdownExtraExtendedParser()
        ));
        $text = '![Alt text](http://test.org/image.jpg "Test title")';

        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['sm.markdown']);
        $this->assertContains('<p><img src="http://test.org/image.jpg" alt="Alt text" title="Test title" /></p>', $app['sm.markdown']->transform($text));
    }

    public function testCode()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider(), array(
            'sm.markdown.parser' => new MarkdownExtraExtendedParser()
        ));
        $text = "~~~php\n" . '$posts = new PostCollection($app);' ."\n~~~";

        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['sm.markdown']);
        $this->assertContains('<code class="language-php">', $app['sm.markdown']->transform($text));
    }

    public function testRadiantFilter()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider(), array(
            'sm.markdown.parser' => new MarkdownExtraExtendedParser(),
            'sm.markdown.filter' => array(
                'block_code' => new RadiantFilter()
            )
        ));
        $text = "~~~php\n" . 'echo $posts-&gt;find()->head()->title;' ."\n~~~";
        echo $app['sm.markdown']->transform($text);
        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['sm.markdown']);
        $this->assertContains('<span class="radiant_keyword">', $app['sm.markdown']->transform($text));
    }

    public function testPygmentsFilter()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider(), array(
            'sm.markdown.parser' => new MarkdownExtraExtendedParser(),
            'sm.markdown.filter' => array(
                'block_code' => new PygmentsFilter()
            )
        ));
        $text = "~~~php\n" . 'echo $posts->find()->head()->title;' ."\n~~~";
        echo $app['sm.markdown']->transform($text);
        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['sm.markdown']);
        $this->assertContains('<div class="pygments">', $app['sm.markdown']->transform($text));
    }

    public function testEssenceFilter()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider(), array(
            'sm.markdown.parser' => new MarkdownExtraExtendedParser(),
            'sm.markdown.filter' => array(
                'image' => new EssenceFilter()
            )
        ));
        $text = "![My favorite video](http://www.youtube.com/watch?v=CmDcWr1yqCc)";
        $result = $app['sm.markdown']->transform($text);

        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['sm.markdown']);
        $this->assertContains('<div class="embed">', $result);
        $this->assertContains('src="http://www.youtube.com/embed/CmDcWr1yqCc?feature=oembed"', $result);
    }

    public function testEssenceFilterWithImage()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider(), array(
            'sm.markdown.parser' => new MarkdownExtraExtendedParser(),
            'sm.markdown.filter' => array(
                'image' => new EssenceFilter()
            )
        ));
        $text = '![Alt text](http://test.org/image.jpg "Test title")';

        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['sm.markdown']);
        $this->assertContains('<p><img src="http://test.org/image.jpg" alt="Alt text" title="Test title" /></p>', $app['sm.markdown']->transform($text));
    }

    public function testTwigExtension()
    {
        $app = new Application();
        $app->register(new TwigServiceProvider());
        $app->register(new MarkdownServiceProvider(), array(
            'sm.markdown.parser' => new MarkdownExtraExtendedParser()
        ));

        $twig = $app['twig'];
        $ext = $twig->getExtension('sm.markdown');

        $this->assertInstanceOf('\Twig_Environment', $twig);
        $this->assertInstanceOf('\Twig_Extension', $ext);
        $this->assertTrue(array_key_exists('sm.markdown', $ext->getFilters()));
    }
}
