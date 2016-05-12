<?php

namespace SSENSE\HiringTest\Models;

use Silex\Application;
use SSENSE\HiringTest\Models\Curl\Connection;

/**
 * Class Weather
 * @package SSENSE\HiringTest\Models
 */
class Weather
{

    protected $cache;

    /**
     * Class constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->cache = $app['caches']['filesystem'];
        $this->config = $app['weather'];
    }

    /**
     * @param $latitude
     * @param $longitude
     * @return bool|mixed|string
     * @throws \Exception
     */
    public function retrieveForecast($latitude, $longitude)
    {
        if (empty($latitude) || empty($longitude)) {
            throw new \Exception("You must provide latitude and longitude");
        }

        $pattern = ['[api_key]', '[latitude]', '[longitude]'];
        $replacement = [$this->config['api_key'], $latitude, $longitude];
        $url = str_replace($pattern, $replacement, $this->config['api_url']);

        $cachingEnabled = $this->config['caching'];

        if ($cachingEnabled) {
            return $this->retrieveFromCache($url);
        }

        return $this->executeCurl($url);
    }

    /**
     * @param $url
     * @return mixed
     * @throws \Exception
     */
    public function retrieveFromCache($url)
    {
        // Check if it is in Cache first
        $cacheKey = 'forecast:' . md5($url);

        $data = $this->cache->fetch($cacheKey);
        if (!empty($data)) {
            $data = json_decode($data, true);
            return $data;
        }

        $content = $this->executeCurl($url, false);
        $this->cache->store($cacheKey, $content, 3600);

        $data = json_decode($content, true);
        return $data;
    }

    /**
     * @param $url
     * @param bool $decode
     * @return bool|mixed|string
     * @throws \Exception
     */
    public function executeCurl($url, $decode = true)
    {
        $curl = new Connection();
        $content = $curl->get($url);

        if (!$curl->isSuccess()) {
            throw new \Exception("Something wrong happened: " . $curl->getError());
        }

        if ($decode) {
            $data = json_decode($content, true);
            return $data;
        }

        return $content;
    }
}
