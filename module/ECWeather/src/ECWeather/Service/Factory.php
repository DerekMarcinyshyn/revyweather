<?php
namespace ECWeather\Service;

use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    ECWeather\Manager;

class Factory implements FactoryInterface {

    /**
     * Factory method for ECWeather Manager service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return \ECWeather\Manager|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->get('Configuration');
        $params = $config['ECWeather']['params'];

        $manager = new Manager($params);

        return $manager;
    }
}