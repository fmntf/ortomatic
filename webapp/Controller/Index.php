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

class Controller_Index extends Controller
{
    public function run()
	{
		$humidity = new Service_Humidity();
		$temperature = new Service_Temperature();
		
		if (file_exists("/etc/udoo-config.conf")) {
			system("stty -F /dev/ttymxc3 cs8 9600 ignbrk -brkint -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl noflsh -ixon -crtscts");
			$serial = $this->createPhpSerial();

			$serial->sendMessage("u",5);
			$serial->readPort();

			$humidity->setPhpSerial($serial);
			$temperature->setPhpSerial($serial);
		}
		
		$db = new Service_Database();
		
		$webcam = new Service_Image();
		$webcam->shot();
		$webcam->previewCanopy();
		
		$this->viewVars = array(
			'humidity' => $humidity->getActualValue(0),
			't0' => $temperature->getActualValue(0),
			't1' => $temperature->getActualValue(1),
			'serieH' => $db->getLastDayHumidities(0),
			'serieC' => $db->getLastWeekCanopies(0),
			'serieT0' => $db->getLastDayTemperatures(0),
			'serieT1' => $db->getLastDayTemperatures(1),
		);
		$this->render('index');
	}
	
	private function createPhpSerial()
	{
		$serial = new Service_PhpSerial;
		$serial->deviceSet("/dev/ttymxc3");
		$serial->confBaudRate(9600);
		$serial->deviceOpen('w+');
		stream_set_timeout($serial->_dHandle, 10);
		$serial->serialFlush();	

		return $serial;
	}

}