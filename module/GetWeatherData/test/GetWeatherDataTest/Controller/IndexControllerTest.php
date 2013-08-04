<?php

namespace GetWeatherDataTest\Controller;

use GetWeatherData\Controller\IndexController;
use GetWeatherDataTest\Bootstrap;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class GetWeatherDataControllerTest extends AbstractHttpControllerTestCase {

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    public function setUp() {
        $this->setApplicationConfig(
            include '/home/web/public_html/revyweather/config/application.config.php'
        );

        $serviceManager = Bootstrap::getServiceManager();
        $this->request = new Request();
        $this->controller = new IndexController();
        $this->routeMatch = new RouteMatch(array('controller' => '/postrainfall'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);

        parent::setUp();
    }

    public function testPostRainfall() {

        $post_data = array('rainfall' => true);
        $this->dispatch('/postrainfall', 'POST', $post_data);
        /**
        $this->request->setMethod('post');
        $this->request->getPost()->set('artist', 'foo');
        $this->request->getPost()->set('title', 'bar');

        $result   = $this->controller->dispatch($this->request);*/
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

    }
}