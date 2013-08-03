<?php
namespace GetWeatherData\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RainfallController extends AbstractActionController {

    public function postrainfallAction() {

        $request = $this->getRequest();

        //$data = $this->params()->fromPost('paraname');


        if ($request->isPost()) {
            // get the data? $data = $request->getContent()


            return $this->redirect()->toRoute('home');

        } else {
            return $this->redirect()->toRoute('home');
        }

    }
}