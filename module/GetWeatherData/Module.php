<?php
namespace GetWeatherData;

/**
 * Class Module
 * Gets the data from Netduino Plus 2 as json and saves the json file
 * @package Monashee\GetData
 * @author  Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date    July 31, 2013
 */


class Module {

    public function getAutoloaderConfig() {

        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig() {

        return include __DIR__ . '/config/module.config.php';
    }
}