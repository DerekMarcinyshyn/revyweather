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
        $forecastArray = $this->convertForecastObject($forecastGroup);
        $sunrise = $xml->riseSet->dateTime[1]->hour . ':' . $xml->riseSet->dateTime[1]->minute;
        $sunset = $xml->riseSet->dateTime[3]->hour . ':' . $xml->riseSet->dateTime[3]->minute;
        $temperature = $currentConditions->temperature;
        $dewpoint = $currentConditions->dewpoint;
        $relativeHumidity = $currentConditions->relativeHumidity;
        $wind = $currentConditions->wind;
        $speed = $wind->speed;
        $direction = $wind->direction;
        $pressure = $currentConditions->pressure;
        $yesterday = $xml->yesterdayConditions;
        $highTemp = $yesterday->temperature[0];
        $lowTemp = $yesterday->temperature[1];
        $precip = $yesterday->precip;
        $almanac = $xml->almanac;
        $extremeHighTemp = $almanac->temperature[0] . "&deg;C in " . $almanac->temperature[0]['year'];
        $extremeLowTemp = $almanac->temperature[1] . "&deg;C in " . $almanac->temperature[1]['year'];

        $forecast = array(
            'location'          => $location,
            'condition'         => $condition,
            'dateTime'          => $dateTime,
            'visibility'        => $visibility,
            'iconCode'          => $currentIconCode,
            'forecast'          => $forecastArray,
            'sunrise'           => $sunrise,
            'sunset'            => $sunset,
            'temperature'       => $temperature,
            'dewpoint'          => $dewpoint,
            'relativeHumidity'  => $relativeHumidity,
            'speed'             => $speed,
            'direction'         => $direction,
            'pressure'          => $pressure,
            'highTemp'          => $highTemp,
            'lowTemp'           => $lowTemp,
            'precip'            => $precip,
            'extremeHigh'       => $extremeHighTemp,
            'extremeLow'        => $extremeLowTemp,
        );

        $this->setForecast($forecast);

        return $this;
    }

    /**
     * Get record low temp
     *
     * @return string extremeLow
     */
    public function getExtremeLow() {
        $forecast = $this->getForecast();
        return $forecast['extremeLow'];
    }

    /**
     * Get record high temp
     *
     * @return string extremeHigh
     */
    public function getExtremeHigh() {
        $forecast = $this->getForecast();
        return $forecast['extremeHigh'];
    }

    /**
     * Get yesterday precip
     *
     * @return string precip
     */
    public function getPrecip() {
        $forecast = $this->getForecast();
        return $forecast['precip'];
    }

    /**
     * Get yesterday low temp
     *
     * @return string pressure
     */
    public function getLowTemp() {
        $forecast = $this->getForecast();
        return $forecast['lowTemp'];
    }

    /**
     * Get yesterday high temp
     *
     * @return string high temp
     */
    public function getHighTemp() {
        $forecast = $this->getForecast();
        return $forecast['highTemp'];
    }

    /**
     * Get the current conditions barometric pressure
     *
     * @return string pressure
     */
    public function getPressure() {
        $forecast = $this->getForecast();
        return $forecast['pressure'];
    }

    /**
     * Get the current conditions direction
     *
     * @return string direction
     */
    public function getDirection() {
        $forecast = $this->getForecast();
        return $forecast['direction'];
    }

    /**
     * Get the speed
     *
     * @return string speed
     */
    public function getSpeed() {
        $forecast = $this->getForecast();
        return $forecast['speed'];
    }

    /**
     * Get the dewpoint
     *
     * @return string dewpoint
     */
    public function getDewpoint() {
        $forecast = $this->getForecast();
        return $forecast['dewpoint'];
    }

    /**
     * Get the current conditions relative humidity
     *
     * @return string relativeHumidity
     */
    public function getRelativeHumidity() {
        $forecast = $this->getForecast();
        return $forecast['relativeHumidity'];
    }

    /**
     * Get the current conditions temperature
     *
     * @return string temperature
     */
    public function getTemperature() {
        $forecast = $this->getForecast();
        return $forecast['temperature'];
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

    /**
     * Convert forecastGroup xml object into multi dimensional array
     *
     * @param $forecastGroup
     * @return array $forecastArray
     */
    private function convertForecastObject($forecastGroup) {

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

        return $forecastArray;
    }
}