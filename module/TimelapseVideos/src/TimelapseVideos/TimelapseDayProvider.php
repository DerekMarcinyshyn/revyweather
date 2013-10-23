<?php
/**
 * Day Provider to add states to the days
 *
 * @package     TimelapseVideos
 * @author      Derek Marcinyshyn <derek@marcinyshyn.com>
 * @date        October 22, 2013
 */

namespace TimelapseVideos;

use BmCalendar\DayProviderInterface,
    BmCalendar\Day,
    BmCalendar\Month;

class TimelapseDayProvider implements DayProviderInterface {

    /**
     * Create the day and if true add video link
     *
     * @param Month $month
     * @param int $dayNo
     * @return Day|\BmCalendar\DayInterface
     */
    public function createDay(Month $month, $dayNo) {

        $day = new Day($month, $dayNo);

        if ($this->checkDay($month, $dayNo)) {
            $day->addState(new TimelapseDayState());

            $year = $month->getYear()->value();
            $monthName = $this->getMonth($month->value());
            $dayName = sprintf("%02d", $dayNo);
            $fileName = $monthName . '-' . $dayName . '.webm';

            $day->setAction(array(
                'year'      => $year,
                'monthName' => $monthName,
                'dayName'   => $dayName,
                'fileName'  => $fileName)
            );
        }

        return $day;
    }

    private function createVideoModal($year, $monthName, $dayName, $filename) {

        $html = '<a class="">';

        return $html;
    }


    /**
     * Check to make sure the date falls between first video and current day
     *
     * @param Month $month
     * @param $day
     * @return bool
     */
    private function checkDay(Month $month, $day) {

        // start date: August 25, 2013

        $year = $month->getYear()->value();
        $month = $month->value();

        $nowMonth = date('m');
        $nowDay = date('d');

        if ($year <= 2013 && $month < 9 && $day < 25) {
            return FALSE;
        } else {
            if ($month == $nowMonth && $day >= $nowDay) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    /**
     * Quick little get month and convert to text
     *
     * @param $month
     * @return string
     */
    private function getMonth($month) {
        switch($month) {
            case 1:
                return 'January';
            case 2:
                return 'February';
            case 3:
                return 'March';
            case 4:
                return 'April';
            case 5:
                return 'May';
            case 6:
                return 'June';
            case 7:
                return 'July';
            case 8:
                return 'August';
            case 9:
                return 'September';
            case 10:
                return 'October';
            case 11:
                return 'November';
            case 12:
                return 'December';
            default:
                return '';
        }
    }

}