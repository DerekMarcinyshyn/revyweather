<?php
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
            ),
        ),
    ),
);