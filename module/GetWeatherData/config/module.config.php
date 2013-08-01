<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'GetWeatherData\Controller\Index' => 'GetWeatherData\Controller\IndexController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'getweatherdata' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'     => '/getweatherdata',
                    'defaults'  => array(
                        '__NAMESPACE__' => 'GetWeatherData\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'  => 'Segment',
                        'options' => array(
                            'route' => '/[:action[/]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                        ),
                    ),
                ),
            ),
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