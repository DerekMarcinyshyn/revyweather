<?php
/**
 * Calendar View Helper
 *
 * @package     TimelapseVideos
 * @subpackage  View\Helper
 * @author      Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date        October 22, 2013
 */

namespace TimelapseVideos\View\Helper;

use Zend\View\Helper\AbstractHelper,
    BmCalendar\Calendar,
    TimelapseVideos\View\Helper\TimelapseRenderer,
    TimelapseVideos\TimelapseDayProvider;

class CalendarHelper extends AbstractHelper {

    /**
     * Create and return the calendar
     *
     * @param $year
     * @param $month
     * @return string
     */
    public function __invoke($year, $month) {

        $provider = new TimelapseDayProvider();
        $calendar = new Calendar($provider);
        $cal = new TimelapseRenderer($calendar);
        $html = $cal->setCalendar($calendar)->renderMonth($year, $month);

        return $html;
    }
}