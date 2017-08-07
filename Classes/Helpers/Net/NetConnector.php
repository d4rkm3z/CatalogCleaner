<?php

namespace Helpers\Net;

class NetConnector
{
    protected static function setProxyHeaders($curl)
    {
        curl_setopt($curl, CURLOPT_PROXY, '8.8.8.8');
        curl_setopt($curl, CURLOPT_PROXYPORT, '80');
    }

    public static function query($url, bool $proxy = false)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, false);

        if ($proxy) {
            self::setProxyHeaders($curl);
        }

        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_REFERER, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        $curlResponse = curl_exec($curl);
        curl_close($curl);

        return $curlResponse;
    }
}