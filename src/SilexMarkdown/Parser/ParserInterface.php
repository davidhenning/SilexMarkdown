<?php

namespace SilexMarkdown\Parser;

use SilexMarkdown\Filter\FilterInterface;

interface ParserInterface
{
    public function transform($source);
    public function registerFilter($method, FilterInterface $filter);
    public function getFilters();
    public function hasFilter($method);
    public function useFilter($method, $content, $params);
}
