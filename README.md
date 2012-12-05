# SilexMarkdown

[![Build Status](https://secure.travis-ci.org/MadCatme/SilexMarkdown.png)](http://travis-ci.org/MadCatme/SilexMarkdown)

SilexMarkdown provides a service provider class and twig extension to easily render markdown within Silex/Twig based projects.

The markdown renderer is based on [php-markdown by Michel Fortin](https://github.com/michelf/php-markdown/).

## Usage

Register the service provider to your Silex application object:

~~~ php
<?php

require 'vendor/autoload.php';

$app = new \Silex\Application;
$app->register(new \Silex\Provider\TwigServiceProvider());
$app->register(new \SilexMarkdown\Provider\MarkdownServiceProvider());
~~~

### Amplifyr

Amplifyr is REST based markdown rendering service with rich syntax highlighting capabilities. It is written in Ruby and based on
Redcarpet. The syntax highlighter is based on the Python library Pygments and supports most common languages, even templating and markup languages.

Using Amplifyr is quite simple:

~~~ php
<?php

require 'vendor/autoload.php';

$app = new \Silex\Application;
$app->register(new \Silex\Provider\TwigServiceProvider());
$app->register(new \SilexMarkdown\Provider\MarkdownServiceProvider(), array(
    'markdown.parser' => new \SilexMarkdown\Parser\AmplifyrParser()
));
~~~

**Attention!** Amplifyr is a web service. If you want to render markdown on every page refresh, it would be quite slow and
creates heavy load on the serves. Please cache the transformed HTML in some way. For example, my blog system CodrPress
stores the transformed HTML in a separate database field only on saving a post. It's much faster this way.

Render markdown with PHP:

~~~ php
$app['markdown']->transform('# Headline');
~~~

Render Markdown with Twig:

~~~ jinja2
{{ variable|markdown }}
~~~