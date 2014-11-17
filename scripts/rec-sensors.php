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

chdir(__DIR__);
require_once "../webapp/autoloader.php";

if (!file_exists("/etc/udoo-config.conf")) {
	system("php blink-led.php > /dev/null 2>&1 &"); // start led blink
}

$date = date('Y-m-d H:i');

$humidity = new Service_Humidity();
$temperature = new Service_Temperature();
$db = new Service_Database();

if (file_exists("/etc/udoo-config.conf")) {
	system("stty -F /dev/ttymxc3 cs8 9600 ignbrk -brkint -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl noflsh -ixon -crtscts");
	$serial = createPhpSerial();
	
	$serial->sendMessage("u",5);
	$serial->readPort();
	
	$humidity->setPhpSerial($serial);
	$temperature->setPhpSerial($serial);
}


$db->insertTemperature(0, $temperature->getActualValue(0), $date);
$db->insertTemperature(1, $temperature->getActualValue(1), $date);
$db->insertHumidity(0, $humidity->getActualValue(0), $date);


function createPhpSerial()
{
	$serial = new Service_PhpSerial;
	$serial->deviceSet("/dev/ttymxc3");
	$serial->confBaudRate(9600);
	$serial->deviceOpen('w+');
	stream_set_timeout($serial->_dHandle, 10);
	$serial->serialFlush();	

	return $serial;
}
