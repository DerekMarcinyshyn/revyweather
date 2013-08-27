<?php
namespace ECWeather\View\Helper;

use Zend\View\Helper\AbstractHelper,
    Zend\Http\Request;

/**
 * Class ECWeather
 * @package ECWeather\View\Helper
 * @author  Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date    August 26, 2013
 */
class ECWeather extends AbstractHelper {

    /**
     * @var ECWeather Service
     */
    protected $service;

    /**
     * @var array $params
     */
    protected $params;

    /**
     * Called upon invoke
     *
     * @param $cityName
     * @return mixed
     */
    public function __invoke($cityName) {
        $forecast = $this->service->getWeather($cityName);

        return $forecast;
    }

    /**
     * Get ECWeather service
     *
     * @return ECWeather
     */
    public function getService() {
        return $this->service;
    }

    /**
     * Set the ECWeather service
     *
     * @param $service
     * @return $this
     */
    public function setService($service) {
        $this->service = $service;
        return $this;
    }

    /**
     * Get ECWeather params
     *
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Set ECWeather params
     *
     * @param array $params
     * @return $this
     */
    public function setParams(Array $params) {
        $this->params = $params;
        return $this;
    }
}