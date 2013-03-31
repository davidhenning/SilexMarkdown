<?php

namespace SilexMarkdown\Provider;

use Silex\Application,
    Silex\ServiceProviderInterface;

use SilexMarkdown\Parser\MarkdownExtraExtendedParser,
    SilexMarkdown\Twig\Extension\Markdown;

class MarkdownServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['sm.markdown'] = $app->share(function () use ($app) {
            $parser = (isset($app['sm.markdown.parser'])) ? $app['sm.markdown.parser'] : new MarkdownExtraExtendedParser();

            if (isset($app['sm.markdown.filter'])) {
                foreach($app['sm.markdown.filter'] as $method => $filter) {
                    $parser->registerFilter($method, $filter);
                }
            }

            return $parser;
        });

        if (isset($app['twig'])) {
            $app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
                $twig->addExtension(new Markdown($app['sm.markdown']));

                return $twig;
            }));
        }
    }


    public function boot(Application $app)
    {

    }
}
