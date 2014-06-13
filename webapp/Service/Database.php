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
				id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
				sensor_id INT NOT NULL,
				value DECIMAL(5,2),
				timestamp DATETIME
			);
		");
		
		$this->db->exec("
			CREATE TABLE humidity (
				id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
				sensor_id INT NOT NULL,
				value DECIMAL(5,2),
				timestamp DATETIME
			);
		");
		
		$this->db->exec("
			CREATE TABLE picture (
				id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
				camera_id INT NOT NULL,
				filename VARCHAR(100),
				estimated_canopy INTEGER,
				timestamp DATETIME
			);
		");
	}
	
	public function insertTemperature($sensorId, $temperature, $date=null)
	{
		$statement = $this->db->prepare("
			INSERT INTO thermal (sensor_id, value, timestamp)
			VALUES (:sensorId, :value, :timestamp)
		");
		
		if (!$date) {
			$date = date('Y-m-d H:i:s');
		}
		
		$statement->bindParam(':sensorId', $sensorId);
		$statement->bindParam(':value', $temperature);
		$statement->bindParam(':timestamp', $date);
		
		$statement->execute();
	}
	
	public function insertHumidity($sensorId, $humidity, $date=null)
	{
		$statement = $this->db->prepare("
			INSERT INTO humidity (sensor_id, value, timestamp)
			VALUES (:sensorId, :value, :timestamp)
		");
		
		if (!$date) {
			$date = date('Y-m-d H:i:s');
		}
		
		$statement->bindParam(':sensorId', $sensorId);
		$statement->bindParam(':value', $humidity);
		$statement->bindParam(':timestamp', $date);
		
		$statement->execute();
	}
	
	public function insertPicture($cameraId, $filename, $estimatedCanopy)
	{
		$statement = $this->db->prepare("
			INSERT INTO picture (camera_id, filename, estimated_canopy, timestamp)
			VALUES (:cameraId, :filename, :estimatedCanopy, :timestamp)
		");
		
		$statement->bindParam(':cameraId', $cameraId);
		$statement->bindParam(':filename', $filename);
		$statement->bindParam(':estimatedCanopy', $estimatedCanopy);
		$statement->bindParam(':timestamp', date('Y-m-d H:i:s'));
		
		$statement->execute();
	}
	
	public function getLastDayTemperatures($sensorId)
	{
		$statement = $this->db->prepare("
			SELECT value, timestamp
			FROM thermal
			WHERE sensor_id = :sensorId
			AND timestamp > :maxTs
		");
		
		$maxTs = $this->getOneDateAgo();
		$statement->bindParam(':sensorId', $sensorId);
		$statement->bindParam(':maxTs', $maxTs);
		return $this->resultToArray($statement->execute());
	}
	
	public function getLastDayHumidities($sensorId)
	{
		$statement = $this->db->prepare("
			SELECT value, timestamp
			FROM humidity
			WHERE sensor_id = :sensorId
			AND timestamp > :maxTs
		");
		
		$maxTs = $this->getOneDateAgo();
		$statement->bindParam(':sensorId', $sensorId);
		$statement->bindParam(':maxTs', $maxTs);
		return $this->resultToArray($statement->execute());
	}
	
	public function getStats()
	{
		$statement = $this->db->prepare("
			SELECT COUNT(*) AS count FROM thermal
				UNION ALL
			SELECT COUNT(*) AS count FROM humidity
				UNION ALL
			SELECT COUNT(*) AS count FROM picture
		");
		
		$result = $statement->execute();
		$total = 0;
		
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$total += $row['count'];
		}
		
		return $total;
	}
	
	public function exportData()
	{
		$statement = $this->db->prepare("
			SELECT timestamp, sensor_id, 'temp' AS type, value FROM thermal
				UNION ALL
			SELECT timestamp, sensor_id, 'hum' AS type, value FROM humidity
				UNION ALL
			SELECT timestamp, camera_id AS sensor_id, 'canopy' AS type, estimated_canopy AS value FROM picture

			ORDER BY timestamp
		");
		
		$result = $statement->execute();
		$header = array('timestamp');
		$data = array();
		
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) { 
			$ts = $row['timestamp'];
			if (!array_key_exists($ts, $data)) {
				$data[$ts] = array(
					'timestamp' => $ts,
				);
			}
			
			$type = $row['type'] . $row['sensor_id'];
			if (!in_array($type, $header)) {
				$header[] = $type;
			}
			
			$data[$ts][$type] = $row['value'];
		}
		
		return array($data, $header);
	}
	
	private function resultToArray(SQLite3Result $result)
	{
		$values = [];
		$labels = [];
		
		$i = 0;
		while ($row = $result->fetchArray(SQLITE3_ASSOC)) { 
			$values[] = $row['value'];
			
			if ($i % 18 == 0) {
				$date = new DateTime($row['timestamp']);
				$labels[] = $date->format('H:i');
			}
			$i++;
		}
		
		return [
			'values' => $values,
			'labels' => $labels
		];
	}
	
	private function getOneDateAgo()
	{
		$maxTs = new DateTime();
		$maxTs->sub(new DateInterval('P1D'));
		return $maxTs->format('Y-m-d H:i:s');
	}
}