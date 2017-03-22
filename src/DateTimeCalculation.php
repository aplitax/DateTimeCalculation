<?php

namespace ApliTax\DateTimeCalculation;

/**
 * DateTimeCalculation
 *
 * @author Karel Uhlík, ApliTax s.r.o.
 * @license MIT
 */
class DateTimeCalculation {

	/** @var \Datetime */
	private $date;

	public function __construct(\DateTime $date = NULL) {
		if (!$date) {
			$date = new \DateTime();
			$date->setTime(0, 0, 0);
		}
		$this->date = $date;
	}

	/**
	 * Calculates first day of quarter
	 * 
	 * @param DateTime $date
	 * @return DateTime
	 */
	public function firstDayOfQuarter() {
		$m = $this->firstMonthOfQuarter();
		return new \DateTime($this->date->format("Y") . "-" . $m . "-01 00:00:00");
	}

	/**
	 * Calculates last day of quarter
	 * 
	 * @param DateTime $date
	 * @return DateTime
	 */
	public function lastDayOfQuarter() {
		$m = $this->lastMonthOfQuarter();
		$return = new \DateTime($this->date->format("Y") . "-" . $m . "-01 23:59:59");
		return $return->modify('last day of');
	}

	/**
	 * Calculates first month of quarter
	 * 
	 * @param DateTime $date
	 * @return integer
	 */
	public function firstMonthOfQuarter() {
		$q = $this->quarter($this->date);
		return 1 + ($q - 1) * 3;
	}

	/**
	 * Calculates last month of quarter
	 * 
	 * @param DateTime $date
	 * @return integer
	 */
	public function lastMonthOfQuarter() {
		$q = $this->quarter($this->date);
		return $q * 3;
	}

	/**
	 * Calculates quarter from date
	 * 
	 * @param DateTime $date
	 * @return integer
	 */
	public function quarter() {
		return floor(($this->date->format("n") - 1) / 3) + 1;
	}

	/**
	 * Calculates total days in quarter
	 * 
	 * @param DateTime $date
	 * @return integer
	 */
	public function daysInQuarter() {
		$d1 = $this->firstDayOfQuarter();
		$d2 = $this->lastDayOfQuarter();
		$period = $d1->diff($d2);
		return $period->format("%a") + 1;
	}

	/**
	 * Calculates days passed in quarter
	 * 
	 * @param DateTime $date
	 * @return integer
	 */
	public function daysPassedInQuarter() {
		$d1 = $this->firstDayOfQuarter();
		$period = $d1->diff($this->date);
		return $period->format("%a");
	}

	public function info() {
		echo "<pre>";
		echo "Date                   : " . $this->date->format("d.m.Y H:i:s") . "\n";
		echo "Qarter                 : " . $this->quarter() . ".\n";
		echo "First day of quarter   : " . $this->firstDayOfQuarter()->format("d.m.Y") . "\n";
		echo "Last  day of quarter   : " . $this->lastDayOfQuarter()->format("d.m.Y") . "\n";
		echo "First month of quarter : " . $this->firstDayOfQuarter()->format("F") . "\n";
		echo "First month of quarter : " . $this->firstMonthOfQuarter() . "\n";
		echo "Last  month of quarter : " . $this->lastDayOfQuarter()->format("F") . "\n";
		echo "Last  month of quarter : " . $this->lastMonthOfQuarter() . "\n";
		echo "Days in quarter        : " . $this->daysInQuarter() . "\n";
		echo "Days passed in quarter : " . $this->daysPassedInQuarter() . "\n";
		echo "</pre>";
	}

	/**
	 * Calculates first date of week from year and week number
	 * @return \DateTime
	 */
	public function firstDayOfWeek() {
		$date = clone $this->date;
		return $date->modify("monday this week");
	}

	/**
	 * Calculates last date of week from year and week number
	 * @return \DateTime
	 */
	public function lastDayOfWeek() {
		$date = clone $this->date;
		return $date->modify("sunday this week");
	}

	/**
	 * Calculates first date of week from year and week number
	 * @param integer $year
	 * @param integer $weekNr
	 * @return \DateTime
	 */
	static function firstDayOfWeekNum($year = NULL, $weekNr = NULL) {
		if (!$year) {
			$year = date("Y");
		}
		if (!$weekNr) {
			$weekNr = date("W");
		}

		$date = new \DateTime();
		$date->setISODate($year, $weekNr);
		$date->setTime(0, 0, 0);
		return $date;
	}

	/**
	 * Calculates first date of week from year and week number
	 * @param integer $year
	 * @param integer $weekNr
	 * @return \DateTime
	 */
	static function lastDayOfWeekNum($year = NULL, $weekNr = NULL) {
		if (!$year) {
			$year = date("Y");
		}
		if (!$weekNr) {
			$weekNr = date("W");
		}

		$date = new \DateTime();
		$date->setISODate($year, $weekNr, 7);
		$date->setTime(0, 0, 0);
		return $date;
	}

	/**
	 * Calculates next week and year from year and week number
	 * @param integer $year
	 * @param integer $weekNr
	 * @return array [week, year]
	 */
	static function nextWeek($year = NULL, $weekNr = NULL) {
		if (!$year) {
			$year = date("Y");
		}
		if (!$weekNr) {
			$weekNr = date("W");
		}

		$date = new \DateTime();
		$date->setISODate($year, $weekNr, 1);
		$date->setTime(0, 0, 0);
		$date->modify("+1 week");

		return ["week" => $date->format("W"), "year" => $date->format("Y")];
	}

	/**
	 * Calculates previous week and year from year and week number
	 * @param integer $year
	 * @param integer $weekNr
	 * @return array [week, year]
	 */
	static function previousWeek($year = NULL, $weekNr = NULL) {
		if (!$year) {
			$year = date("Y");
		}
		if (!$weekNr) {
			$weekNr = date("W");
		}

		$date = new \DateTime();
		$date->setISODate($year, $weekNr, 1);
		$date->setTime(0, 0, 0);
		$date->modify("-1 week");

		return ["week" => $date->format("W"), "year" => $date->format("Y")];
	}

	/**
	 * Calculates number of weeks in year
	 * @param integer $year
	 * @return integer
	 */
	static function weeksInYear($year = NULL) {
		if (!$year) {
			$year = date("Y");
		}

		$date = new \DateTime;
		$date->setISODate($year, 53);
		return ($date->format("W") === "53" ? 53 : 52);
	}
	
	/**
	 * Calculate number of total seconds in DateInterval
	 * @param \DateInterval $interval
	 * @return integer
	 */
	static function intervalToSeconds(\DateInterval $interval) {
        $seconds = (string) $interval->s;
        if ($interval->i) {
            $seconds = bcadd($seconds, bcmul($interval->i, 60));
        }
        if ($interval->h) {
            $seconds = bcadd($seconds, bcmul($interval->h, 60 * 60));
        }
        if ($interval->d) {
            $seconds = bcadd($seconds, bcmul($interval->d, 60 * 60 * 24));
        }
        if ($interval->m) {
            $seconds = bcadd($seconds, bcmul($interval->m, 2629740));
        }
        if ($interval->y) {
            $seconds = bcadd($seconds, bcmul($interval->y, 31556874));
        }
        return $seconds;		
	}
	
	/**
	 * Calculate number of total minutes in DateInterval
	 * @param \DateInterval $interval
	 * @return integer
	 */
	static function intervalToMinutes(\DateInterval $interval) {
        $minutes = (string) $interval->i;
        if ($interval->h) {
            $minutes = bcadd($minutes, bcmul($interval->h, 60));
        }
        if ($interval->d) {
            $minutes = bcadd($minutes, bcmul($interval->d, 60 * 24));
        }
        if ($interval->m) {
            $minutes = bcadd($minutes, bcmul($interval->m, 2629740 / 60));
        }
        if ($interval->y) {
            $minutes = bcadd($minutes, bcmul($interval->y, 31556874 / 60));
        }
        return $minutes;		
	}
	
}
