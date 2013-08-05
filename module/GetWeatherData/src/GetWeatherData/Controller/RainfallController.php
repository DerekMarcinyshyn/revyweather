<?php
namespace GetWeatherData\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

/**
 * Class RainfallController
 * @package GetWeatherData\Controller
 * @author  Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date    August 4, 2013
 *
 * to test via commandline
 * $ curl -v -i -H "Accept: application/json" -X POST -d "rainfall=true" http://revyweather.dev/postrainfall
 *
 * @reference http://hounddog.github.io/blog/getting-started-with-rest-and-zend-framework-2/
 */

class RainfallController extends AbstractRestfulController {

    public function create($data) {

        // get the json from the content
        $content = $this->getRequest()->getContent();

        // decode it
        $content_decode = json_decode($content);

        // get the values
        $timestamp = $content_decode->timestamp;
        $public_key = $content_decode->public_key;
        $nonce = $content_decode->nonce;
        $_public_key = "E661M6K52272K69m77473x12G2zK79";

        // check if has public key
        if ($public_key === $_public_key) {

            // get the private_key
            $getweatherdata = $this->getServiceLocator()->get('config')['getweatherdata'];
            $_private_key = $getweatherdata['private_key'];

            $_nonce = md5($_private_key . $_public_key . $timestamp);

            // check if the post came from netduino
            if ($_nonce === $nonce) {

                $this->recordRainfallEvent($timestamp);

                return new JsonModel(array('data' => 'Success'));
            } else {
                return new JsonModel(array('data' => 'FAILED NONCE DID NOT MATCH'));
            }

        } else {
            return new JsonModel(array('data' => 'FAILED PUBLIC KEY DID NOT MATCH'));
        }
    }

    /**
     * Record the rainfall event
     *
     * @param $timestamp
     */
    private function recordRainfallEvent($timestamp) {

        $logger = new Logger;
        $writer = new Stream('./data/logs/json.txt');
        $logger->addWriter($writer);
        $logger->log(Logger::INFO, 'Rainfall event happened at: ' . date('l, F j, Y g:i:s a', $timestamp));

        // create object manager and set the values to the columns
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        // create a rainfall entity object
        $rainfall = new \GetWeatherData\Entity\Rainfall();
        date_default_timezone_set('America/Los_Angeles');
        $rainfall->setTimestamp(new \DateTime("now"));
        $rainfall->setRainfall(true);

        $objectManager->persist($rainfall);
        $objectManager->flush();
    }
}