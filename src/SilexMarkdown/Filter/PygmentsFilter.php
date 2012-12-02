<?php

namespace SilexMarkdown\Filter;

use Buzz\Browser;

class PygmentsFilter implements FilterInterface
{
    public function transform($code, $language)
    {
        $code = $this->_prepareCode($code, $language);
        $browser = new Browser();
        $response = $browser->submit(
            'http://pygments.appspot.com/',
            array(
                'lang' => $language,
                'code' => $code
            )
        );

        $content = $response->getContent();

        return '<div class="pygments">' . $this->_cleanUp($content) . '</div>';
    }

    protected function _prepareCode($code, $language)
    {
        if($language === 'php') {
            if(strpos($code, '<?php') === false) {
                $code = "<?php\n\n" . $code;
            }
        }

        return $code;
    }

    protected function _cleanUp($content)
    {
        preg_match('/<pre>(.*)<\/pre>/s', $content, $matches);

        return $matches[1];
    }

    public function getName()
    {
        return 'transform';
    }
}
