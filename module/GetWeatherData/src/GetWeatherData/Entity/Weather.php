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
    protected $tempdht;

    /**
     * @ORM\Column(type="string")
     */
    protected $humiditydht;

    /**
     * @ORM\Column(type="string")
     */
    protected $tempbmp;

    /**
     * @ORM\Column(type="string")
     */
    protected $pressurebmp;

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
     * Set tempdht
     *
     * @param string $tempdht
     * @return Weather
     */
    public function setTempdht($tempdht) {
        $this->tempdht = $tempdht;

        return $this;
    }

    /**
     * Get tempdht
     *
     * @return string
     */
    public function getTempdht() {
        return $this->tempdht;
    }

    /**
     * Set humiditydht
     *
     * @param $humiditydht
     * @return Weather
     */
    public function setHumiditydht($humiditydht) {
        $this->humiditydht = $humiditydht;

        return $this;
    }

    /**
     * Get humiditydht
     *
     * @return string
     */
    public function getHumiditydht() {
        return $this->humiditydht;
    }

    /**
     * Set tempbmp
     *
     * @param $tempbmp
     * @return Weather
     */
    public function setTempbmp($tempbmp) {
        $this->tempbmp = $tempbmp;

        return $this;
    }

    /**
     * Get tempbmp
     *
     * @return string
     */
    public function getTempbmp() {
        return $this->tempbmp;
    }

    /**
     * Set pressurebmp
     *
     * @param $pressurebmp
     * @return Weather
     */
    public function setPressurebmp($pressurebmp) {
        $this->pressurebmp = $pressurebmp;

        return $this;
    }

    /**
     * Get pressurebmp
     *
     * @return string
     */
    public function getPressurebmp() {
        return $this->pressurebmp;
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
