<?php

namespace App\Http\Helpers;
class Calendar
{

    private $active_year, $active_month, $active_day;
    private $events = [];

    public function __construct($date = null)
    {
        $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
        $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
        $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
    }

    public function add_event($txt, $date, $days = 1, $activity_id, $color = '')
    {
        $color = $color ? ' ' . $color : ' default';
        //$color = ' orange';
        $this->events[] = [$txt, $date, $days, $color, $activity_id];
    }

    public function __toString()
    {
        $num_days = date('t', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year));
        $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year)));
        $days = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
        $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1')), $days);
        $cellNum = 0;

        $html = '<table style="margin: 20px 0" id="calendar">';
        $html .= '<tr style="background-color: #102F38; border: 1px solid black">';
        $html .= '<th style="border-right: 0px"><button id="prevNext" onclick="changeMonth(false)">⇦</button></th>';
        $html .= '<th id="header" colspan="5">';
        $html .= date('F Y', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day));
        $html .= '</th>';
        $html .= '<th style="border-left: 0px"><button id="prevNext" onclick="changeMonth(true)">⇨</button></th>';
        $html .= '<tr>';

        foreach ($days as $day) {
            $html .= '
                <td class="day_name">
                    ' . $day . '
                </td>
            ';
        }

        $html .= "</tr><tr>";

        for ($i = $first_day_of_week; $i > 0; $i--) {
            $html .= '
                <td class="day_num ignore">
                    ' . ($num_days_last_month - $i + 1) . '
                </td>
            ';
            $cellNum++;
        }

        for ($i = 1; $i <= $num_days; $i++) {
            $num_events = 0;
            $selected = '';
            if ($i == $this->active_day) {
                $selected = ' selected';
            }

            if ($cellNum == 7) {
                $html .= "</tr><tr>";
                $cellNum = 0;
            }

            $html .= '<td class="day_num' . $selected . '">';
            $html .= '<span>' . $i . '</span>';

            foreach ($this->events as $key => $event) {
                for ($d = 0; $d <= ($event[2] - 1); $d++) {
                    $date = date('y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i . ' -' . $d . ' day'));
                    $eventDate = date('y-m-d', strtotime($event[1]));

                    if ($date == $eventDate) {
                        $current_day = substr($event[1], 0, 10);

                        if ($num_events < 2) {
                            $html .= "<div class='event $event[3]' tabindex='0' onclick='showMoreActivities(this, \"$current_day\")' data-value='$event[4]'>$event[0]</div>";
                        } else if ($num_events == 2) {
                            $html .= "<span class='expand' title='Show More' onclick='showMoreActivities(this, \"$current_day\")'>
                                            <img src='img/ExpandButton.png' height='20px' width='20px'>
                                      </span>";
                        }

                        unset($this->events[$key]);

                        $num_events++;
                    }
                }
            }
            $html .= '</td>';

            $cellNum++;
        }

        for ($i = 1; $i <= (35 - $num_days - max($first_day_of_week, 0)); $i++) {
            if ($cellNum == 7) {
                $html .= "</tr><tr>";
                $cellNum = 0;
            }

            $html .= '
                <td class="day_num ignore">
                    ' . $i . '
                </td>
            ';

            $cellNum++;
        }
        $html .= '</tr>';
        $html .= '</table>';
        return $html;
    }
}