<?php
namespace ECWeather;
use ECWeather\EnvironmentCanada\Weather;

/**
 * Class Manager
 * @package ECWeather
 * @author  Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date    August 26, 2013
 */
class Manager {

    /**
     * @var Array
     */
    protected  $params;

    /**
     * @var array
     */
    protected $cache;

    /**
     * Set the Module specific configuration parameters
     *
     * @param Array $params
     */
    public function __construct($params) {
        $this->params = $params;
        $this->cache = array();
    }

    /**
     * The Environment Canada main getWeather
     *
     * @param $cityName
     * @return $this
     * @throws \Exception
     */
    public function getWeather($cityName) {
        if (isset($this->cache[$cityName])) {
            $weather = $this->cache[$cityName];
        } else {
            $weather = new Weather($cityName);
            $weather->fetch();

            // Cache the weather object so we don't have to do HTTP request on each call
            // Enables to get multiple object's properties at different times
            $this->cache[$cityName] = $weather;
        }

        if (!$weather) {
            throw new \Exception('Location does not exist');
        }

        return $weather;
    }
}