<?php
/**
 * ortomatic - botanic research laborator monitor
 * Copyright (C) 2014 Francesco Montefoschi <francesco.monte@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package ortomatic
 * @author  Francesco Montefoschi
 * @license http://www.gnu.org/licenses/gpl-3.0.html  GNU GPL 3.0
 */

class Service_Temperature
{
	public function getActualValue($sensorId)
	{
		if (file_exists("/etc/udoo-config.conf")) {
			
			if ($sensorId == 0) {
				$this->phpSerial->sendMessage("t", 0.5);
				return trim($this->phpSerial->readPort());
			}

			if ($sensorId == 1) {
				$this->phpSerial->sendMessage("T", 1.5);
				return trim($this->phpSerial->readPort());
			}
		} else {
			
			if ($sensorId == 0) {
				chdir(__DIR__ . "/../../scripts");
				$result = trim(exec("perl i2cread"));
				$parts = explode(' --- ', $result);

				return number_format((float)$parts[1], 3);
			}

			if ($sensorId == 1) {
				$raw = file_get_contents("/sys/bus/w1/devices/28-000005036b8b/w1_slave");
				$parts = explode(" t=", trim($raw));

				return $parts[1] / 1000;
			}
		}
			
		throw new Exception("Sensore $sensorId non disponibile");
	}
	
	private $phpSerial;
	
	public function setPhpSerial(Service_PhpSerial $serial)
	{
		$this->phpSerial = $serial;
	}
}