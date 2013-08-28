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
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        // get weather data json
        $getweatherdata = $this->getServiceLocator()->get('config')['getweatherdata'];
        $key = $getweatherdata['key'];

        // get weather data
        $url = 'http://derek.is-a-rockstar.com/weather-station/data.php?key=' . $key;
        $json = $this->getJson($url);

        // get the json into an object
        $json_decode = json_decode($json);

        // create object manager and set the columns
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        // create a weather entity object
        $weather = new \GetWeatherData\Entity\Weather();
        $weather->setTemp($json_decode->temp);
        $weather->setHumidity($json_decode->humidity);
        $weather->setRelativehumidity($json_decode->relativehumidity);
        $weather->setDirection($json_decode->direction);
        $weather->setSpeed($json_decode->speed);
        $weather->setBarometer($json_decode->barometer);
        $weather->setBmp_temperature($json_decode->bmp_temperature);
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
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $getweatherdata = $this->getServiceLocator()->get('config')['getweatherdata'];
        $key = $getweatherdata['key'];

        // get weather data
        $url = 'http://derek.is-a-rockstar.com/weather-station/data.php?key=' . $key;
        $json = $this->getJson($url);

        if (!empty($json)) {
            // save it to data/json/current.json
            $directory = getcwd() . '/public/data/json/';
            $filename = 'current.json';
            $result = file_put_contents($directory . $filename, $json);

            if ($result)
                echo "file saved\n";
            else
                echo "oh shit. it did not save.\n";
        }
    }

    /**
     * Get the latest webcam image from local Selkirk server
     *
     * @throws \RuntimeException
     */
    public function getwebcamimageAction() {
        $request = $this->getRequest();

        // make sure again that it is only callable from a console as to not overload the netduino
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $url = 'http://derek.is-a-rockstar.com/weather-station/latest.jpg';
        $image = file_get_contents($url);

        if ($image) {
            $latest = getcwd() . '/public/img/latest.jpg';
            $result = file_put_contents($latest, $image);

            if ($result)
                echo "file saved\n";
            else
                echo "image not saved\n";
        } else {
            echo "could not get image\n\n";
        }

    }

    /**
     * Get json file from local Selkirk server
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