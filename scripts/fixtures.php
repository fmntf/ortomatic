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

$humidity = new Service_Humidity();
$temperature = new Service_Temperature();

$db = new Service_Database();

$now = new DateTime();
$date = new DateTime();
$date->sub(new DateInterval('P2D'));
$interval = new DateInterval('PT15M');

echo "Starting to fill DB from " . $date->format('Y-m-d H:i:s') . '...' . PHP_EOL;

while ($date<$now) {
	$ts = $date->format('Y-m-d H:i:s');
	$db->insertTemperature(0, rand(20,25), $ts);
	$db->insertTemperature(1, rand(22,27), $ts);
	$db->insertHumidity(0, rand(60,80), $ts);

	$date->add($interval);
	echo '.';
}

echo PHP_EOL . "Done!" . PHP_EOL;

