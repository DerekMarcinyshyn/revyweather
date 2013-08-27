<?php
namespace ECWeather\EnvironmentCanada;

use Zend\Http\Client;

/**
 * Class Weather
 * @package ECWeather\EnvironmentCanada
 * @author  Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date    August 26, 2013
 */
class Weather {

    /**
     * Environment Canada's API URL
     *
     * @var
     */
    protected $serviceApiUrl;

    /**
     * The HTTP Client object
     *
     * @var \Zend\Http\Client
     */
    protected $httpClient;

    /**
     * Container for the obtained forecast
     *
     * @var array $forecast
     */
    protected $forecast;

    /**
     * The city of weather forecast
     *
     * @var string $cityName
     */
    private $cityName;

    /**
     * Setter for Environment Canada's service API URL
     *
     * @param string $url
     */
    public function setServiceApiUrl($url) {
        $this->serviceApiUrl = $url;
    }

    /**
     * Getter for Environment Canada's service API URL
     *
     * @return string
     */
    public function getServiceApiUrl() {
        return $this->serviceApiUrl;
    }

    /**
     * Sets the Zend_Http_Client object to use in requests. If not provided a default will be used.
     *
     * @param \Zend\Http\Client $client The HTTP client instance to use
     * @return $this
     */
    public function setHttpClient(\Zend\Http\Client $client) {
        $this->httpClient = $client;
        return $this;
    }

    /**
     * Returns the instance of Zend\Http\Client which will be used. Creates an instance of Zend_Http_Client
     * if no previous client was set
     *
     * @return \Zend\Http\Client The HTTP client which will be used
     */
    public function getHttpClient() {
        if (!($this->httpClient instanceof \Zend\Http\Client)) {
            $client = new \Zend\Http\Client();

            $client->setOptions(array('maxredirects' => 2,
                                    'timeout' => 5));

            $this->setHttpClient($client);
        }

        $this->httpClient->resetParameters();

        return $this->httpClient;
    }

    /**
     * Setter for the forecast
     *
     * @param string $forecast
     */
    public function setForecast($forecast) {
        $this->forecast = $forecast;
    }

    /**
     * Getter for the forecast
     *
     * @return array
     */
    public function getForecast() {
        return $this->forecast;
    }

    /**
     * Sets the city name to be used in requests
     *
     * @param string $cityName
     * @return $this
     */
    public function setCityName($cityName) {
        $this->cityName = $cityName;
        return $this;
    }

    /**
     * Gets the city name to be used in requests
     *
     * @return string
     */
    public function getCityName() {
        return $this->cityName;
    }



    /**
     * Main constructor
     *
     * @TODO build out changeable url based on $cityName
     */
    public function __construct($cityName) {
        $this->serviceApiUrl = "http://dd.weatheroffice.gc.ca/citypage_weather/xml/BC/s0000679_e.xml";

        $this->httpClient = new \Zend\Http\Client($this->getServiceApiUrl());
    }

    public function fetch() {
        $client = $this->getHttpClient();
        //$params

        $response = NULL;

        try {
            $response = $client->send();
        } catch (\Exception $e) {
            throw new \Exception("No weather information available");
        }

        if ($response->getStatusCode() != 200) {
            throw new \Exception("No weather information available");
        }

        $xml = new \SimpleXMLElement(utf8_encode($response->getBody()));
        $currentConditions = $xml->currentConditions;
        $location = $currentConditions->station;
        $condition = $currentConditions->condition;
        $dateTime = $xml->dateTime[1]->textSummary;

        //print_r($dateTime);

        $forecast = array(
            'location'      => $location,
            'condition'     => $condition,
            'dateTime'      => $dateTime,
        );

        $this->setForecast($forecast);

        return $this;
    }

    /**
     * Get the location of the forecast station
     *
     * @return string
     */
    public function getLocation() {
        $forecast = $this->getForecast();
        return $forecast['location'];
    }

    /**
     * Get the condition
     *
     * @return string $condition
     */
    public function getCondition() {
        $forecast = $this->getForecast();
        return $forecast['condition'];
    }

    /**
     * Get the date and time of weather station reading
     *
     * @return string $dateTime
     */
    public function getDateTime() {
        $forecast = $this->getForecast();
        return $forecast['dateTime'];
    }
}