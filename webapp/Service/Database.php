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

class Service_Database
{
	/**
	 * @var SQLite3
	 */
	private $db;
	
    public function __construct()
	{
		$this->db = new SQLite3(__DIR__ . "/../../data/db.sqlite");
	}
	
	public function initSchema()
	{
		$this->db->exec("
			CREATE TABLE thermal (
				id INT PRIMARY KEY NOT NULL,
				sensor_id INT NOT NULL,
				value DECIMAL(5,2),
				timestamp DATETIME
			);
		");
		
		$this->db->exec("
			CREATE TABLE humidity (
				id INT PRIMARY KEY NOT NULL,
				sensor_id INT NOT NULL,
				value DECIMAL(5,2),
				timestamp DATETIME
			);
		");
		
		$this->db->exec("
			CREATE TABLE picture (
				id INT PRIMARY KEY NOT NULL,
				camera_id INT NOT NULL,
				filename VARCHAR(100),
				estimated_canopy INTEGER,
				timestamp DATETIME
			);
		");
	}
	
	public function writeTemperature($sensorId, $temperature)
	{
//		$this->db->
	}
}