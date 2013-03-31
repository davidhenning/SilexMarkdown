<?php

namespace SilexMarkdown\Twig\Extension;

use SilexMarkdown\Parser\ParserInterface;

use Twig_Extension;

class Markdown extends Twig_Extension
{
    protected $_helper;

    public function __construct(ParserInterface $helper)
    {
        $this->_helper = $helper;
    }

    public function getFilters()
    {
        return array(
            'sm.markdown' => new \Twig_Filter_Method($this, 'sm.markdown', array('is_safe' => array('html'))),
        );
    }

    public function markdown($text)
    {
        return $this->_helper->transform($text);
    }

    public function getName()
    {
        return 'sm.markdown';
    }
}