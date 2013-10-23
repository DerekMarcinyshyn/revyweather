<?php
/**
 * Calendar Controller
 *
 * @package TimelapseVideos
 * @author  Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date    October 21, 2013
 */

namespace TimelapseVideos\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\JsonModel,
    Zend\Http\Request;

class CalendarController extends AbstractActionController {

    public function getcalendarAction() {

        $request = $this->getRequest();

        if ($request->isPost()) {
            $helper = $this->getServiceLocator()->get('viewhelpermanager')->get('calendarhelper');
            $year = $this->getRequest()->getPost('year');
            $month = $this->getRequest()->getPost('month');

            $html = array('data' => $helper($year, $month));
            $json = new JsonModel($html);

            return $json;
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
}