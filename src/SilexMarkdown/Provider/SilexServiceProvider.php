<?php

namespace SilexMarkdown\Provider;

use Silex\Application,
    Silex\ServiceProviderInterface;

use SilexMarkdown\MarkdownExtraParser,
    SilexMarkdown\Twig\Extension\Markdown;

class SilexServiceProvider implements ServiceProviderInterface {

    public function register(Application $app) {
        $app['markdown'] = $app->share(function () use ($app) {
            return new MarkdownExtraParser();
        });

        if(isset($app['twig'])) {
            $app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
                $twig->addExtension(new Markdown($app['markdown']));

                return $twig;
            }));
        }
    }


    public function boot(Application $app) {

    }
}
