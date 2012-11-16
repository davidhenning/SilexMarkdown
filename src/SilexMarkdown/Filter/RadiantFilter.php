<?php

namespace SilexMarkdown\Filter;

use Radiant\Parser;

class RadiantFilter implements FilterInterface
{
    public function transform($code, $language)
    {
        return Parser::transform($language, $code);
    }

    public function getName()
    {
        return 'transform';
    }
}
