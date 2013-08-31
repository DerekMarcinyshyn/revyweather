<?php
/**
 * Factory method for History Charts Service
 *
 * @author  Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date    August 30, 2013
 */

namespace HistoryCharts\Service;

use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    HistoryCharts\View\Helper\Charts;

class Factory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $parentLocator = $serviceLocator->getServiceLocator();
        $entityManager = $parentLocator->get('Doctrine\ORM\EntityManager');

        return new Charts($entityManager);
    }
}