<?php
/**
 * Created by PhpStorm.
 * User: sadovnikov
 * Date: 20.07.17
 * Time: 20:26
 */

namespace Helpers\Parsers\Colors;

use Helpers\Parsers\PageParser;
use Models\ColorPages;

class ColorPageParser extends AbstractColorParser
{
    protected $config;
    protected $xpath;
    protected $colorPages;
    protected $pageParser;
    protected $model;

    public function __construct()
    {
        $this->model = new ColorPages();
        $this->pageParser = new PageParser();
    }

    public function parse($attributeName)
    {
        $colorName = $this->prepareColorName($attributeName);
        return $this->findColorHex($colorName);
    }

    protected function findColorHex(string $colorName): string
    {
        $this->loadSiteByName($colorName);
        return $this->pageParser->getValueByClassName('color-img-box');
    }

    protected function loadSiteByName($color)
    {
        $html = $this->model->getBodyByColor($color);
        $this->pageParser->setHtml($html);
        unset($html);
    }
}