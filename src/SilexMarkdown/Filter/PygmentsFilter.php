<?php

namespace SilexMarkdown\Filter;

use Buzz\Browser;

class PygmentsFilter implements FilterInterface
{
    public function transform($code, $language)
    {
        $browser = new Browser();
        $response = $browser->submit(
            'http://pygmentizr.herokuapp.com',
            array(
                'language' => $language,
                'code' => $code,
                'nowrap' => 1
            )
        );

        $content = $response->getContent();

        return "<div class=\"pygments\">{$content}</div>";
    }

    public function getName()
    {
        return 'transform';
    }
}
