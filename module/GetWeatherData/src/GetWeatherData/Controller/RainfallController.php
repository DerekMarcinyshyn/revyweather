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

        $content = $this->getRequest()->getContent();

        //$nonce = json_decode($data);

        $logger = new Logger;
        $writer = new Stream('./data/logs/json.txt');
        $logger->addWriter($writer);
        $logger->log(Logger::INFO, 'data: ' . print_r($content,1));


        // check if the post came from netduino

        return new JsonModel(array(
            'data' => 'success',
        ));
    }
}