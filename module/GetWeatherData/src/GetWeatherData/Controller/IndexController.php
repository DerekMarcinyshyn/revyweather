<?php
namespace GetWeatherData\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Console\Request as ConsoleRequest;

class IndexController extends AbstractActionController {

    public function indexAction() {

        //echo 'hello from indexAction';

        return new ViewModel();
    }

    public function getweatherdataAction() {
        $request = $this->getRequest();

        echo "hello from here\n";
    }
}