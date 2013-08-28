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

                'getwebcamimage' => array(
                    'options' => array(
                        'route'     => 'getwebcamimage',
                        'defaults'  => array(
                            '__NAMESPACE__' => 'GetWeatherData\Controller',
                            'controller'    => 'Index',
                            'action'        => 'getwebcamimage'
                        ),
                    ),
                ),
            ),
        ),
    ),

    'router' => array(
        'routes' => array(
            'postrainfall' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/postrainfall[/:params]',
                    'constraints' => array(
                        'params' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'GetWeatherData\Controller',
                        'controller'    => 'Rainfall',
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