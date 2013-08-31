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
    Doctrine\Orm\QueryBuilder;

class WeatherDataController extends AbstractActionController {

    public function lastweekAction() {

        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        //$repository = $entityManager->getRepository('GetWeatherData\Entity\Weather');

        //$weatherData = $entityManager->createQueryBuilder('GetWeatherData\Entity\Weather')
        //    ->getQuery()
        //    ->execute();

        $query = $entityManager->createQuery('SELECT u FROM GetWeatherData\Entity\Weather u WHERE u.timestamp BETWEEN CURRENT_TIMESTAMP() - 6048000 AND CURRENT_TIMESTAMP()');
        $weatherData = $query->getResult();

        foreach($weatherData as $weather) {

            echo $weather->getTimestamp()->format('Y-m-d H:i:s') . "<br/>" ;

        }


        //$weatherData = $entityManager->getRepository('GetWeatherData\Entity\Weather')
        //    ->findBy($criteria, array('id' => 'ASC', 200, 0));

        //print_r($weatherData);

        return true;
    }



// maybe use this for the between dates?
//$query = $em->createQuery('SELECT u.name FROM CmsUser u WHERE u.id BETWEEN ?1 AND ?2');
//$query->setParameter(1, 123);
//$query->setParameter(2, 321);
//$usernames = $query->getResult();
}