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
$app->register(new \SilexMarkdown\Provider\SilexMarkdownServiceProvider());
~~~

Render markdown with PHP:

~~~ php
$app['markdown']->transform('# Headline');
~~~

Render Markdown with Twig:

~~~ twig
{{ variable|markdown }}
~~~