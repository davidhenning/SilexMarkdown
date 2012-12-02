<?php

namespace SilexMarkdown\Tests;

use Silex\Application,
    Silex\Provider\TwigServiceProvider;

use SilexMarkdown\Filter\RadiantFilter,
    SilexMarkdown\Filter\EssenceFilter,
    SilexMarkdown\Filter\PygmentsFilter,
    SilexMarkdown\Provider\MarkdownServiceProvider;

class SilexMarkdownTest extends \PHPUnit_Framework_TestCase
{

    public function testTransform()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider());
        $text = "# Headline";

        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['markdown']);
        $this->assertContains('<h1>Headline</h1>', $app['markdown']->transform($text));
    }

    public function testImage()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider());
        $text = '![Alt text](http://test.org/image.jpg "Test title")';

        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['markdown']);
        $this->assertContains('<p><img src="http://test.org/image.jpg" alt="Alt text" title="Test title" /></p>', $app['markdown']->transform($text));
    }

    public function testCode()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider());
        $text = "~~~php\n" . '$posts = new PostCollection($app);' ."\n~~~";

        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['markdown']);
        $this->assertContains('<code class="language-php">', $app['markdown']->transform($text));
    }

    public function testRadiantFilter()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider(), array(
            'markdown.filter' => array(
                'block_code' => new RadiantFilter()
            )
        ));
        $text = "~~~php\n" . 'echo $posts-&gt;find()->head()->title;' ."\n~~~";
        echo $app['markdown']->transform($text);
        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['markdown']);
        $this->assertContains('<span class="radiant_keyword">', $app['markdown']->transform($text));
    }

    public function testPygmentsFilter()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider(), array(
            'markdown.filter' => array(
                'block_code' => new PygmentsFilter()
            )
        ));
        $text = "~~~php\n" . '<?php echo $posts->find()->head()->title;' ."\n~~~";
        echo $app['markdown']->transform($text);
        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['markdown']);
        $this->assertContains('<div class="highlight">', $app['markdown']->transform($text));
    }

    public function testEssenceFilter()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider(), array(
            'markdown.filter' => array(
                'image' => new EssenceFilter()
            )
        ));
        $text = "![My favorite video](http://www.youtube.com/watch?v=CmDcWr1yqCc)";
        $result = $app['markdown']->transform($text);

        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['markdown']);
        $this->assertContains('<div class="embed">', $result);
        $this->assertContains('src="http://www.youtube.com/embed/CmDcWr1yqCc?fs=1&feature=oembed"', $result);
    }

    public function testEssenceFilterWithImage()
    {
        $app = new Application();
        $app->register(new MarkdownServiceProvider(), array(
            'markdown.filter' => array(
                'image' => new EssenceFilter()
            )
        ));
        $text = '![Alt text](http://test.org/image.jpg "Test title")';

        $this->assertInstanceOf('\SilexMarkdown\Parser\MarkdownParser', $app['markdown']);
        $this->assertContains('<p><img src="http://test.org/image.jpg" alt="Alt text" title="Test title" /></p>', $app['markdown']->transform($text));
    }

    public function testTwigExtension()
    {
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
