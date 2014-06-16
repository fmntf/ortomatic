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

require_once "../webapp/autoloader.php";
system("php blink-led.php > /dev/null 2>&1 &"); // start led blink

$humidity = new Service_Humidity();
$temperature = new Service_Temperature();

$db = new Service_Database();

$db->insertTemperature(0, $temperature->getActualValue(0));
$db->insertTemperature(1, $temperature->getActualValue(1));
$db->insertHumidity(0, $humidity->getActualValue(0));
$db->insertHumidity(1, $humidity->getActualValue(1));

// stop the led
system("kill `ps u |grep blink\-led\.php |grep -v grep |awk '{print $2}'`");
