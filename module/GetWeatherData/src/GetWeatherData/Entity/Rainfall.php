<?php
namespace GetWeatherData\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stores the local rainfall
 *
 * To create entities automagically
 * run command ./vendor/doctrine/doctrine-module/bin/doctrine-module orm:schema-tool:update
 *
 * @ORM\Entity
 */

class Rainfall {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $rainfall;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set rainfall
     *
     * @param boolean $rainfall
     * @return Weather
     */
    public function setRainfall($rainfall) {
        $this->rainfall = $rainfall;

        return $this;
    }

    /**
     * Get rainfall
     *
     * @return boolean
     */
    public function getRainfall() {
        return $this->rainfall;
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