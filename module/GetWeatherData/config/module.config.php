<?php

namespace GetWeatherData;

return array(
    'controllers' => array(
        'invokables' => array(
            'GetWeatherData\Controller\Index' => 'GetWeatherData\Controller\IndexController',
        ),
    ),

    'console' => array(
        'router' => array(
            'routes' => array(
                'getweatherdata' => array(
                    'options' => array(
                        'route'     => 'getweatherdata',
                        'defaults'  => array(
                            '__NAMESPACE__' => 'GetWeatherData\Controller',
                            'controller'    => 'Index',
                            'action'        => 'getweatherdata'
                        ),
                    ),
                ),

                'getweatherandsave' => array(
                    'options' => array(
                        'route'     => 'getweatherandsave',
                        'defaults'  => array(
                            '__NAMESPACE__' => 'GetWeatherData\Controller',
                            'controller'    => 'Index',
                            'action'        => 'getweatherandsave'
                        ),
                    ),
                ),
            ),
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'getweatherdata_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/GetWeatherData/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'GetWeatherData\Entity' => 'getweatherdata_entities'
                ),
            ),
        ),
    ),
);