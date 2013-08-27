<?php
namespace ECWeather;

use ECWeather\View\Helper\ECWeather;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'ECWeather' => 'ECWeather\Service\Factory',
            ),
        );
    }

    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                'getWeather' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $config = $locator->get('Configuration');
                    $params = $config['ECWeather']['params'];

                    $viewHelper = new View\Helper\ECWeather();
                    $viewHelper->setService($locator->get('ECWeather'));
                    $viewHelper->setParams($params);

                    return $viewHelper;
                },
            ),
        );
    }
}
