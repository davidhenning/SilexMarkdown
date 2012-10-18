<?php

namespace SilexMarkdown\Twig\Extension;

use SilexMarkdown\MarkdownParser;

use Twig_Extension;

class Markdown extends Twig_Extension {
    protected $_helper;

    public function __construct(MarkdownParser $helper) {
        $this->_helper = $helper;
    }

    public function getFilters() {
        return array(
            'markdown' => new \Twig_Filter_Method($this, 'markdown', array('is_safe' => array('html'))),
        );
    }

    public function markdown($txt) {
        return $this->_helper->transform($txt);
    }

    public function getName() {
        return 'markdown';
    }
}