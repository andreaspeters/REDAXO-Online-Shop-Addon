<?php

/**
 * LogbookCalendar Classes
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */
 
class OOLogbookCalendar {
	/**
	 * Fill the days into an array
	 * 
	 * This gives back the month in an array with weeks and weekdays as indexes.
	 * For June 2008 it would look like this:
	 * Array (
     * [18] => Array(
     *       [0] => 
     *       [1] => 
     *       [2] => 
     *       [3] => 1
     *       [4] => 2
     *       [5] => 3
     *       [6] => 4
     *   )
     * [19] => Array(
     *       [0] => 5
     *       [1] => 6
     *  ...
     *       [3] => 29
     *       [4] => 30
     *       [5] => 31
     *       [6] => 
     *   )
     * )
	 * 
	 * @param int $month The month we shall fill the array for.
	 * @param int $year  The year for the month.
	 * @return Array An array in the form $month[week][weekday]=date of day
	 */
	public function getMonthArray($month=null, $year=null) {
		// Take today if no month or year is given
		$now=time();
		if($month==null || $year==null) {
			$month=date("n", $now);
			$year=date("Y", $now);
		}
		
		// Get timestamps for the first and the last day of the month  
		$firstdayunixstamp=mktime(0,0,0,$month,1,$year);
		$lastdayunixstamp=mktime(0,0,0,$month,cal_days_in_month(CAL_GREGORIAN,$month,$year),$year);

		// Get the weeks numbers
		$firstweek=intval(date("W",$firstdayunixstamp));
		$lastweek=intval(date("W",$lastdayunixstamp));

		// We want to have a rectangular display. This is the padding at the beginning and the end
		$firstweekday=intval(date("N",$firstdayunixstamp));
		$lastweekday=intval(date("N",$lastdayunixstamp));
				
		
		// Numweeks is not linear at the start or end of a year!
		// Calculate days in month plus paddings
		$daystoshow=cal_days_in_month(CAL_GREGORIAN,$month,$year)+($firstweekday-1)+(7-$lastweekday);
		
		// Calculate how much weeks we display
		$numweeks=$daystoshow/7;
		
		$montharray=array();
		$dayofmonth=1;
		// Now loop through every week
		for($iweek=0;$iweek<$numweeks;$iweek++) {
			$week=intval(date("W",mktime(0,0,0,$month,$dayofmonth,$year)));
			for($dayofweek=0; $dayofweek<7; $dayofweek++) {
				// First week and month has not yet started or last week and month has ended?
				if(($week==$firstweek && $dayofweek<$firstweekday-1)
				      || ($week==$lastweek && $dayofweek>$lastweekday-1)) {
					$day="";				
				} else {
					$day=$dayofmonth++;
				}
				$montharray[$week][$dayofweek]=$day;
			}	
		}
		return $montharray;
	}
	
	/**
	 * Generates HTML for a given month
	 * 
	 * @param int $month The month of the event.
	 * @param int $year  The year of the event.
	 * @param int $day   The day of the event.
	 * @param String $url The url for the link of the dates
	 * @param String $parameters Parameters to append to a link (already urlencoded)
	 * @param Boolean $marktoday Shall todays date be marked if in this month?
	 * @return String HTML-Representation of the month
	 */
	public function getMonthHTML($month=null, $year=null, $day=null, $url="", $parameters="", $marktoday=true) {
		// Take today if no month or year is given
		$now=time();
		if($month==null || $year==null) {
			$month=date("n", $now);
			$year=date("Y", $now);
			$day=date("j", $now);
		}

		if($marktoday) {
			$thismonth=date("n", $now);
			$thisyear=date("Y", $now);
			$thisday=date("j", $now);
		}
		
		// Get the dates in an array
		$montharray=$this->getMonthArray($month, $year);
		$htmlarray=array();

		// Spice up the array with links and marks for today/event
		// Loop through weeks
		foreach($montharray as $week=>$days) {
			// Loop through days
			for($dayofweek=0; $dayofweek<7; $dayofweek++) {
				// Just 
				if($montharray[$week][$dayofweek]) {
					// Mark today
					$currentday=$montharray[$week][$dayofweek];
					if($year==$thisyear&&$month==$thismonth&&$montharray[$week][$dayofweek]==$thisday&&$marktoday) {
						$currentday='<span class="today">'.$currentday."</span>";
					} 				
					// The event
					if($day && $montharray[$week][$dayofweek]==$day) {
						$currentday='<span class="marked">'.$currentday."</span>";
					} 					
					
					// Create the link
					if($url) { 
						$showurl = $url."?".($parameters?$parameters."&amp;":"")."day=".urlencode($montharray[$week][$dayofweek])."&amp;month=".urlencode($month)."&amp;year=".urlencode($year);
						$currentday='<a href="'.$showurl.'">'.$currentday.'</a>';
					}
					$htmlarray[$week][$dayofweek]=$currentday;
				} else {
					$htmlarray[$week][$dayofweek]='';
				}
			}
		}
		
		//Create Table
		$firstdayunixstamp=mktime(0,0,0,$month,1,$year);
		$html = '<table class="month">'."\n";
		// Header
		$html .= '  <thead><tr><th colspan="8">'.strftime("%B",$firstdayunixstamp).' '.$year.'</th></tr></thead>'."\n";
                $html .= '  <tr><th></th>';
		// Names of the weekdays
		for($i=0;$i<7;$i++) {
			//Ugly Hack, September 2008 starts with a monday.
			$html.='<th class="weekday">'.strftime("%a", mktime(0,0,0,9,$i+1,2008))."</th>";
		}
		$html .= "  </tr>\n";
	
		// Loop through weeks
		foreach($htmlarray as $week=>$days) {
			$html .= '    <tr><th class="monthweek">'.$week.'</th><td class="monthday">'.join('</td><td class="monthday">',$days).'</td></tr>'."\n";
		}
		// We are done
		$html .= "</table>\n";
		return $html;
	}
}
