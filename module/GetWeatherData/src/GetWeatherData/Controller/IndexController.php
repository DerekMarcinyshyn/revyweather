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

    public function getweatherdataAction() {
        $request = $this->getRequest();

        // make sure again that it is only callable from a console as to not overload the netduino
        // or give away its location
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        // get netduino json
        $url = 'http://192.168.1.50/';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $json = curl_exec($ch);
        curl_close($ch);

        // save it to data/json/current.json.php
        $directory = '/home/web/public_html/revyweather/data/json/';
        $filename = 'current.json.php';
        $result = file_put_contents($directory . $filename, $json);

        if ($result)
            echo "file saved\n";
        else
            echo "oh shit\n";

    }
}