<?php
/**
 * Created by PhpStorm.
 * User: sadovnikov
 * Date: 20.07.17
 * Time: 20:26
 */

namespace Models;

use DOMDocument;
use Traits\LoaderConfiguration;

class ColorsParser
{
    use LoaderConfiguration;

    protected $configFile = 'parser.color';
    protected $config;
    protected $xpath;

    public function __construct()
    {
        $this->config = $this->loadConfig($this->configFile);
    }

    public function run()
    {
        $this->parseListColor();
    }

    protected function parseListColor()
    {
        $colorsList = ['cream', 'olive-green'];

        foreach ($colorsList as $color) {
            print("$color: ");
            $this->loadSiteByName($color);
            print($this->getNodeByClass() . "<br>");
        }
    }

    protected function loadSiteByName($color)
    {
        $url = $this->config['site'] . $color;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_REFERER, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $curlresponse = curl_exec($curl);
        curl_close($curl);

        $dom = new DOMDocument();
        $dom->loadHTML($curlresponse);
        $this->xpath = new \DOMXPath($dom);

        unset($curlresponse);
        unset($dom);
    }

    protected function getNodeByClass()
    {
        $nodeValue = '';

        $className = 'color-img-box';
        $results = $this->xpath->query("//*[@class='" . $className . "']");

        if ($results->length > 0) {
            $nodeValue = $results->item(0)->nodeValue;
        }

        unset($this->xpath);
        return $nodeValue;
    }
}