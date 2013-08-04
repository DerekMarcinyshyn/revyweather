<?php

namespace GetWeatherData;

return array(
    'controllers' => array(
        'invokables' => array(
            'GetWeatherData\Controller\Index' => 'GetWeatherData\Controller\IndexController',
            'GetWeatherData\Controller\Rainfall' => 'GetWeatherData\Controller\RainfallController',
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

    'router' => array(
        'routes' => array(
            'postrainfall' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/postrainfall[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'GetWeatherData\Controller',
                        'controller'    => 'Rainfall',
                        //'action'        => 'postrainfall'
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
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