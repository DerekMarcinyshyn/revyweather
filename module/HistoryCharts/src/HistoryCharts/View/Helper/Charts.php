<?php
/**
 * Charts.php
 *
 * Get the data and return as json to render in HighCharts.js
 *
 * @author      Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date        August 30, 2013
 */

namespace HistoryCharts\View\Helper;

use Zend\View\Helper\AbstractHelper,
    DoctrineModule\Stdlib\Hydrator\DoctrineObject,
    Doctrine\Orm\EntityManager,
    Doctrine\Common\Collections\Criteria,
    Doctrine\Common\Collections\ArrayCollection,
    GetWeatherData\Entity\Weather;

class Charts extends AbstractHelper {

    /**
     * @var \Doctrine\Orm\EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }


    public function __invoke() {


        //$hydrator = new DoctrineObject(
        //   $this->entityManager,
        //    'GetWeatherData\Entity\Weather'
        //);

        //$weather = new \GetWeatherData\Entity\Weather();
        //$data = array('id' => 10);

        //$weather = $hydrator->hydrate($data, $weather);

        //print_r($weather);

        $weatherData = $this->entityManager
            ->getRepository('GetWeatherData\Entity\Weather')
            ->findAll();

        //print_r($weatherData);

        return 'hello world';
    }
}