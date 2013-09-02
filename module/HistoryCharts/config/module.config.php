<?php

//namespace HistoryCharts;

return array(
    'controllers' => array(
        'invokables' => array(
            'HistoryCharts\Controller\WeatherData' => 'HistoryCharts\Controller\WeatherDataController'
        ),
    ),

    'router' => array(
        'routes' => array(
            'getcustomdates' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/getcustomdates',
                    'defaults' => array(
                        '__NAMESPACE__' => 'HistoryCharts\Controller',
                        'controller'    => 'WeatherData',
                        'action'        => 'getcustomdates',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'strategies' => array('ViewJsonStrategy'),
    ),

    'view_helpers' => array(
        'factories' => array(
            'HistoryCharts\View\Helper\Charts'  => 'HistoryCharts\Service\Factory',
        ),

        'aliases' => array(
            'historydata'  => 'HistoryCharts\View\Helper\Charts'
        ),
    ),
);