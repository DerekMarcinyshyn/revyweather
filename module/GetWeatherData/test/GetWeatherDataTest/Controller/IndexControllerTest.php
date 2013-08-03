<?php

namespace GetWeatherDataTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class GetWeatherDataControllerTest extends AbstractHttpControllerTestCase {

    public function setUp() {
        $this->setApplicationConfig(
            include '/home/web/public_html/revyweather/config/application.config.php'
        );

        parent::setUp();
    }

    public function testGetWeatherDataActionCanBeAccessed() {

        $this->dispatch('/getweatherdata');
    }

    public function testPostRainfall() {

        $postData = array(
            'rainfall' => 'true'
        );
        $this->dispatch('/postrainfall', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/');
    }
}