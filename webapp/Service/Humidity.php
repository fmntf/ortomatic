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

class Service_Humidity
{
	public function getActualValue($sensorId)
	{
		if (gethostname() == 'n550jk') {
			return rand(40, 60);
		}
		
		if ($sensorId != 0) {
			throw new Exception("Sensore $sensorId non disponibile");
		}
		
		chdir(__DIR__ . "/../../scripts");
		$result = trim(exec("perl i2cread"));
		$parts = explode(' --- ', $result);
		
		return number_format((float)$parts[0], 3);
	}
}