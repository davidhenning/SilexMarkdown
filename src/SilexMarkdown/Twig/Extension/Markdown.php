<?php

namespace SilexMarkdown\Twig\Extension;

use SilexMarkdown\Parser\MarkdownParser;

use Twig_Extension;

class Markdown extends Twig_Extension
{
    protected $_helper;

    public function __construct(MarkdownParser $helper)
    {
        $this->_helper = $helper;
    }

    public function getFilters()
    {
        return array(
            'markdown' => new \Twig_Filter_Method($this, 'markdown', array('is_safe' => array('html'))),
        );
    }

    public function markdown($text)
    {
        return $this->_helper->transform($text);
    }

    public function getName()
    {
        return 'markdown';
    }
}