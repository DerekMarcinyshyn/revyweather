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
     * The forecast period name
     *
     * @var array $forecastPeriod
     */
    private $forecastPeriod;

    /**
     * The forecast text summary
     *
     * @var array $forecastTextSummary
     */
    private $forecastTextSummary;

    /**
     * The forecast icon code
     *
     * @var array $forecastIconCode
     */
    private $forecastIconCode;

    /**
     * The abbreviated text summary
     *
     * @var array $forecastAbbreviatedTextSummary
     */
    private $forecastAbbreviatedTextSummary;

    /**
     * The sunrise for that day
     *
     * @var string $sunrise
     */
    private $sunrise;

    /**
     * The sunset for that day
     *
     * @var string $sunset
     */
    private $sunset;


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

            //$client->setOptions(array('maxredirects' => 2,
            //                        'timeout' => 5));

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

        $this->httpClient = new Client($this->getServiceApiUrl());
        $this->httpClient->setOptions(array(
            'maxredirects'  => 0,
            'timeout'       => 10
        ));
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
        $visibility = $currentConditions->visibility;
        $currentIconCode = $currentConditions->iconCode;
        $forecastGroup = $xml->forecastGroup->forecast;
        $sunrise = $xml->riseSet->dateTime[1]->hour . ':' . $xml->riseSet->dateTime[1]->minute;
        $sunset = $xml->riseSet->dateTime[3]->hour . ':' . $xml->riseSet->dateTime[3]->minute;

        foreach($forecastGroup as $key => $forecastDay) {
            $this->forecastPeriod[] = $forecastDay->period['textForecastName'];
            $this->forecastTextSummary[] = $forecastDay->textSummary;
            $this->forecastIconCode[] = $forecastDay->abbreviatedForecast->iconCode;
            $this->forecastAbbreviatedTextSummary[] = $forecastDay->abbreviatedForecast->textSummary;
        }

        $forecastArray = array();
        $count = 0;
        foreach($this->forecastPeriod as $data) {
            $forecastArray[] = array(
                'forecastPeriod'        => $this->forecastPeriod[$count],
                'forecastTextSummary'   => $this->forecastTextSummary[$count],
                'forecastIconCode'      => $this->forecastIconCode[$count],
                'abbreviatedTextSummary'=> $this->forecastAbbreviatedTextSummary[$count]
            );
            $count++;
        }

        $forecast = array(
            'location'      => $location,
            'condition'     => $condition,
            'dateTime'      => $dateTime,
            'visibility'    => $visibility,
            'iconCode'      => $currentIconCode,
            'forecast'      => $forecastArray,
            'sunrise'       => $sunrise,
            'sunset'        => $sunset,
        );

        $this->setForecast($forecast);

        return $this;
    }

    /**
     * Get the sunset text summary
     *
     * @return string
     */
    public function getSunset() {
        $forecast = $this->getForecast();
        return $forecast['sunset'];
    }

    /**
     * Get the sunrise text summary
     *
     * @return string
     */
    public function getSunrise() {
        $forecast = $this->getForecast();
        return $forecast['sunrise'];
    }

    /**
     * Get the abbreviated text summary -- used for alt tag in icon code
     *
     * @param int $dayNumber
     * @return string
     */
    public function getAbbreviatedTextSummary($dayNumber) {
        $forecast = $this->getForecast();
        return $forecast['forecast'][$dayNumber]['abbreviatedTextSummary'];
    }

    /**
     * Get the forecast period icon code
     *
     * @param int $dayNumber
     * @return string
     */
    public function getForecastIconCode($dayNumber) {
        $forecast = $this->getForecast();
        return $forecast['forecast'][$dayNumber]['forecastIconCode'];
    }
    /**
     * Get the forecast text summary
     *
     * @param int $dayNumber
     * @return string
     */
    public function getForecastTextSummary($dayNumber) {
        $forecast = $this->getForecast();
        return $forecast['forecast'][$dayNumber]['forecastTextSummary'];
    }

    /**
     * Get the forecast period day
     *
     * @param int $dayNumber
     * @return string
     */
    public function getForecastPeriod($dayNumber) {
        $forecast = $this->getForecast();
        return $forecast['forecast'][$dayNumber]['forecastPeriod'];
    }

    /**
     * Get the current conditions icon code
     *
     * @return string
     */
    public function getIconCode() {
        $forecast = $this->getForecast();
        return $forecast['iconCode'];
    }

    /**
     * Get the visibility
     *
     * @return string
     */
    public function getVisibility() {
        $forecast = $this->getForecast();
        return $forecast['visibility'];
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