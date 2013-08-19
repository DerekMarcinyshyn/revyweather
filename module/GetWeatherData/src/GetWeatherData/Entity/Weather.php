<?php
namespace GetWeatherData\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stores the local weather
 *
 * @ORM\Entity
 */

class Weather {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $temp;

    /**
     * @ORM\Column(type="string")
     */
    protected $humidity;

    /**
     * @ORM\Column(type="string")
     */
    protected $relativehumidity;

    /**
     * @ORM\Column(type="string")
     */
    protected $bmp_temperature;

    /**
     * @ORM\Column(type="string")
     */
    protected $barometer;

    /**
     * @ORM\Column(type="string")
     */
    protected $direction;

    /**
     * @ORM\Column(type="string")
     */
    protected $speed;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;

    // getters/setters

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set temp
     *
     * @param string $temp
     * @return Weather
     */
    public function setTemp($temp) {
        $this->temp = $temp;

        return $this;
    }

    /**
     * Get temp
     *
     * @return string
     */
    public function getTemp() {
        return $this->temp;
    }

    /**
     * Set humidity
     *
     * @param $humidity
     * @return Weather
     */
    public function setHumidity($humidity) {
        $this->humidity = $humidity;

        return $this;
    }

    /**
     * Get humidity
     *
     * @return string
     */
    public function getHumidity() {
        return $this->humidity;
    }

    /**
     * Set relativehumidity
     *
     * @param $relativehumidity
     * @return Weather
     */
    public function setRelativehumidity($relativehumidity) {
        $this->relativehumidity = $relativehumidity;

        return $this;
    }

    /**
     * Get relativehumidity
     *
     * @return string
     */
    public function getRelativehumidity() {
        return $this->relativehumidity;
    }

    /**
     * Set bmp_temperature
     *
     * @param $bmp_temperature
     * @return Weather
     */
    public function setBmp_temperature($bmp_temperature) {
        $this->bmp_temperature = $bmp_temperature;

        return $this;
    }

    /**
     * Get bmp_temperature
     *
     * @return string
     */
    public function getBmp_temperature() {
        return $this->bmp_temperature;
    }

    /**
     * Set barometer
     *
     * @param $barometer
     * @return Weather
     */
    public function setBarometer($barometer) {
        $this->barometer = $barometer;

        return $this;
    }

    /**
     * Get barometer
     *
     * @return string
     */
    public function getBarometer() {
        return $this->barometer;
    }

    /**
     * Set direction
     *
     * @param $direction
     * @return Weather
     */
    public function setDirection($direction) {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get direction
     *
     * @return string
     */
    public function getDirection() {
        return $this->direction;
    }

    /**
     * Set speed
     *
     * @param $speed
     * @return Weather
     */
    public function setSpeed($speed) {
        $this->speed = $speed;

        return $this;
    }

    /**
     * Get speed
     *
     * @return string
     */
    public function getSpeed() {
        return $this->speed;
    }

    /**
     * Set timestamp
     *
     * @param $timestamp
     * @return Weather
     */
    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return mixed
     */
    public function getTimestamp() {
        return $this->timestamp;
    }
}