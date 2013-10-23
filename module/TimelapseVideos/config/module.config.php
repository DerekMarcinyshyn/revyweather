<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'TimelapseVideos\Controller\Calendar' => 'TimelapseVideos\Controller\CalendarController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'getcalendar' => array(
                'type'      => 'Zend\Mvc\Router\Http\Literal',
                'options'   => array(
                    'route'     => '/getcalendar',
                    'defaults'  => array(
                        '__NAMESPACE__' => 'TimelapseVideos\Controller',
                        'controller'    => 'Calendar',
                        'action'        => 'getcalendar',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'strategies' => array('ViewJsonStrategy'),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'calendarhelper' => 'TimelapseVideos\View\Helper\CalendarHelper',
        ),
    ),
);