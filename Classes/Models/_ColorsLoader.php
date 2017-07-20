<?php
/**
 * Created by PhpStorm.
 * User: sadovnikov
 * Date: 20.07.17
 * Time: 20:26
 */

namespace Models;

use Traits\LoaderConfiguration;
use \DOMDocument;

class ColorsLoader
{
    use LoaderConfiguration;

    protected $configFile = 'parser.color';
    protected $config, $html;

    public function __construct()
    {
        $this->config = $this->loadConfig($this->configFile);
    }

    protected function loadSiteByName($color){
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

        foreach($dom->find('a') as $element) {
            echo "<pre>";
            print_r( $element->href );
            echo "</pre>";
        }

        unset($curlresponse);
        unset($dom);
    }

    protected function findNeededColor(){

    }

    public function run(){
        $this->loadSiteByName('cream');
        $this->findNeededColor();
    }
}