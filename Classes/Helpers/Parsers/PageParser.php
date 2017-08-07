<?php

namespace Helpers\Parsers;

use DOMDocument;
use DOMXPath;

class PageParser
{
    protected $xpath;

    public function setHtml($html): void
    {
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $this->xpath = new DOMXPath($dom);

        unset($dom);
    }

    public function getValueByClassName(string $className): string
    {
        $nodeValue = '';

        $tags = $this->xpath->query("//*[@class='" . $className . "']");
        unset($this->xpath);

        if ($tags->length > 0) {
            $nodeValue = $tags->item(0)->nodeValue;
        }

        return $nodeValue;
    }
}