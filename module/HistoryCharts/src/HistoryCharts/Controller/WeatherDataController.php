<?php
/**
 * History controller
 *
 * Get the data from the database
 *
 * @package HistoryCharts
 * @author  Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date    August 30, 2013
 */

namespace HistoryCharts\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\View\Model\JsonModel,
    Doctrine\Orm\QueryBuilder;

class WeatherDataController extends AbstractActionController {

    public function lastweekAction() {

        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $query = $entityManager->createQuery('SELECT wx FROM GetWeatherData\Entity\Weather wx WHERE wx.timestamp BETWEEN CURRENT_TIMESTAMP() - 6048000 AND CURRENT_TIMESTAMP()');
        $weatherData = $query->getResult();

        $temperatureResult = array();
        $barometerResult = array();
        $relativeHumidityResult = array();

        foreach($weatherData as $weather) {

            $milliseconds = $weather->getTimestamp()->format('U') * 1000;

            $temperature = intval($weather->getBmp_temperature());
            $temperatureResult[] = array($milliseconds, $temperature);

            $barometer = intval($weather->getBarometer());
            $barometerResult[] = array($milliseconds, $barometer);

            $relativeHumidity = intval($weather->getRelativehumidity());
            $relativeHumidityResult[] = array($milliseconds, $relativeHumidity);
        }

        $temperatureResult = array('name' => 'Air Temp', 'data' => $temperatureResult);
        $barometerResult = array('name' => 'Barometer', 'data' => $barometerResult);
        $relativeHumidityResult = array('name' => 'Relative Humidity', 'data' => $relativeHumidityResult);

        $wxResult = array();
        array_push($wxResult, $temperatureResult);
        array_push($wxResult, $barometerResult);
        array_push($wxResult, $relativeHumidityResult);

        $result = new JsonModel($wxResult);

        return $result;
    }



// maybe use this for the between dates?
//$query = $em->createQuery('SELECT u.name FROM CmsUser u WHERE u.id BETWEEN ?1 AND ?2');
//$query->setParameter(1, 123);
//$query->setParameter(2, 321);
//$usernames = $query->getResult();
}