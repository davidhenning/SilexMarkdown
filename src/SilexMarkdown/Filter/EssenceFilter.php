<?php

namespace SilexMarkdown\Filter;

use fg\Essence\Essence;

class EssenceFilter implements FilterInterface
{
    public function transform($link, $title, $altText)
    {
        $essence = new Essence();
        $media = $essence->embed($link);

        if ($media === null) {
            throw new \InvalidArgumentException("URL '{$link} could not be embedded!'");
        }

        return "<div class=\"embed\">{$media->html}</div>";
    }

    public function getName()
    {
        return 'transform';
    }
}
