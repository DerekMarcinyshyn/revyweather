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
    Zend\View\Model\JsonModel,
    Zend\Http\Request,
    Doctrine\Orm\QueryBuilder;

class WeatherDataController extends AbstractActionController {

    /**
     * Get from Historical Weather section a date range
     * and return the weather json set
     *
     * @return JsonModel
     */
    public function getcustomdatesAction() {

        $request = $this->getRequest();

        if ($request->isPost()) {
            $startDate = $this->getRequest()->getPost('startDate');
            $endDate = $this->getRequest()->getPost('endDate');

            $json = $this->buildJson($startDate, $endDate);

            return $json;
        } else {
            return $this->redirect()->toRoute('home');
        }
    }

    /**
     * Build the Json data for High Charts
     *
     * @param $startDate
     * @param $endDate
     * @return JsonModel
     */
    private function buildJson($startDate, $endDate) {
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $query = $entityManager->createQuery('SELECT wx FROM GetWeatherData\Entity\Weather wx
            WHERE wx.timestamp
            BETWEEN ' . $startDate .
            ' AND ' . $endDate);
        $weatherData = $query->getResult();

        $queryRainfall = $entityManager->createQuery('SELECT wx FROM GetWeatherData\Entity\Rainfall wx
          WHERE wx.timestamp
          BETWEEN ' . $startDate .
          ' AND ' . $endDate);
        $rainfallData = $queryRainfall->getResult();
        $rainfallResult = array();

        foreach ($rainfallData as $rainfall) {
            $rfMilliseconds = $rainfall->getTimestamp()->format('U') * 1000;
            $rainfall = 1 * 0.2794;
            $rainfallResult[] = array($rfMilliseconds, $rainfall);
        }

        $rainfallResult = array(
            'name'      => 'Rainfall',
            'data'      => $rainfallResult,
            'type'      => 'column',
            'yAxis'     => 1,
            'tooltip'   => array(
                'valueSuffix' => ' mm'
            ),
            'color'     =>'#C7D1ED'
        );

        $temperatureResult = array();
        $barometerResult = array();
        $relativeHumidityResult = array();
        $speedResult = array();

        foreach ($weatherData as $weather) {

            $milliseconds = $weather->getTimestamp()->format('U') * 1000;

            $temperature = (float)($weather->getBmp_temperature());
            $temperatureResult[] = array($milliseconds, $temperature);

            $barometer = (float)($weather->getBarometer());
            $barometerResult[] = array($milliseconds, $barometer);

            $relativeHumidity = intval($weather->getRelativehumidity());
            $relativeHumidityResult[] = array($milliseconds, $relativeHumidity);

            $speed = (float)($weather->getSpeed() * 1.60934);
            $speedResult[] = array($milliseconds, $speed);
        }

        $temperatureResult = array(
            'name'      => 'Air Temp',
            'data'      => $temperatureResult,
            'type'      => 'spline',
            'tooltip'   => array(
                'valueSuffix' => ' °C'
            ),
            'color'     => '#B26969',
         );

        $barometerResult = array(
            'name'      => 'Barometer',
            'data'      => $barometerResult,
            'type'      => 'spline',
            'tooltip'   => array(
                'valueSuffix' => 'kPa'
            ),
            'yAxis'     => 2,
            'color'     => '#499999',
            'dashStyle' => 'solid',
            'shadow'    => true
        );

        $relativeHumidityResult = array(
            'name'      => 'Relative Humidity',
            'data'      => $relativeHumidityResult,
            'type'      => 'spline',
            'tooltip'   => array(
                'valueSuffix' => ' %'
            ),
            'color'     => '#B79468',
            'yAxis'     => 4,
            'visible'   => false
        );

        $speedResult = array(
            'name'      => 'Wind Speed',
            'data'      => $speedResult,
            'type'      => 'spline',
            'tooltip'   => array(
                'valueSuffix' => ' kmh'
            ),
            'color'     => '#7EbA86',
            'yAxis'     => 3,
            'visible'   => false
        );

        $wxResult = array();
        array_push($wxResult, $rainfallResult);
        array_push($wxResult, $temperatureResult);
        array_push($wxResult, $barometerResult);
        array_push($wxResult, $relativeHumidityResult);
        array_push($wxResult, $speedResult);
        $result = new JsonModel($wxResult);

        return $result;
    }
}
