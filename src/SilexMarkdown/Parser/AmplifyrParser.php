<?php

namespace SilexMarkdown\Parser;

use SilexMarkdown\Filter\FilterInterface;

use Buzz\Browser;

class AmplifyrParser implements ParserInterface
{
    public function transform($source)
    {
        $browser = new Browser();
        $response = $browser->submit(
            'http://amplifyr.herokuapp.com/',
            array(
                'source' => $source
            )
        );

        return $response->getContent();
    }

    public function registerFilter($method, FilterInterface $filter)
    {
        # empty
    }

    public function getFilters()
    {
        return array();
    }

    public function hasFilter($method)
    {
        return false;
    }

    public function useFilter($method, $content, $params)
    {
        return $content;
    }
}
