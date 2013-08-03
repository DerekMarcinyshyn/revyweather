<?php
namespace GetWeatherData\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Console\Request as ConsoleRequest;

class IndexController extends AbstractActionController {

    /**
     * Should not be accessible from http call but ya never know
     * it will throw a 404 error
     *
     * @return array|ViewModel
     */
    public function indexAction() {
        return new ViewModel();
    }

    /**
     * Gets the weather from Netduino and saves it to the database
     *
     * @throws \RuntimeException
     */
    public function getweatherandsaveAction() {
        $request = $this->getRequest();

        // make sure again that it is only callable from a console as to not overload the netduino
        // or give away its location
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        // get netduino json
        $json = $this->getJson('http://192.168.1.50/');

        // inject a timestamp into the json
        $json_decode = json_decode($json);

        // create object manager and set the columns
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        // create a weather entity object
        $weather = new \GetWeatherData\Entity\Weather();
        $weather->setTempdht($json_decode->tempdht);
        $weather->setHumiditydht($json_decode->humiditydht);
        $weather->setTempbmp($json_decode->tempbmp);
        $weather->setPressurebmp($json_decode->pressurebmp);
        $weather->setDirection($json_decode->direction);
        $weather->setSpeed($json_decode->speed);

        // set timezone
        date_default_timezone_set('America/Los_Angeles');
        $weather->setTimestamp(new \DateTime("now"));

        $objectManager->persist($weather);
        $objectManager->flush();
    }

    /**
     * Grabs the data stream from the Netduino and saves the json to the filesystem
     *
     * @throws \RuntimeException
     */
    public function getweatherdataAction() {
        $request = $this->getRequest();

        // make sure again that it is only callable from a console as to not overload the netduino
        // or give away its location
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        // get netduino json
        $json = $this->getJson('http://192.168.1.50/');

        // inject a timestamp into the json
        $json_decode = json_decode($json);
        date_default_timezone_set('America/Los_Angeles');
        $right_now = date('l, F j, Y g:i:s', time()) . ' PST';
        $key = 'timestamp';
        $json_decode->$key = $right_now;
        $json_encode = json_encode($json_decode);

        // save it to data/json/current.json.php
        $directory = getcwd() . '/data/json/';
        $filename = 'current.json';
        $result = file_put_contents($directory . $filename, $json_encode);

        if ($result)
            echo "file saved\n";
        else
            echo "oh shit. it did not save.\n";
    }

    /**
     * Get json file from Netduino
     *
     * @param $url
     * @return mixed
     */
    private function getJson($url) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $json = curl_exec($ch);
        curl_close($ch);

        return $json;
    }

}