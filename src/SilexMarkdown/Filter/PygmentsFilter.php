<?php

namespace SilexMarkdown\Filter;

use Buzz\Browser;

class PygmentsFilter implements FilterInterface
{
    public function transform($code, $language)
    {
        $browser = new Browser();
        $response = $browser->submit(
            'http://pygments.appspot.com/',
            array(
                'lang' => $language,
                'code' => $code
            )
        );

        return $response->getContent();
    }

    public function getName()
    {
        return 'transform';
    }
}
