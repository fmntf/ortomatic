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

class Controller_Preview extends Controller
{
    public function run()
	{
		$fuzziness = $this->params[0];
		$colors = explode('-', $this->params[1]);
		
		$originalImage = __DIR__ . '/../../public/webcam.jpg';
		$tmpFile = "/tmp/" . uniqid('preview') . ".jpg";
		
		$command = "convert $originalImage -fuzz $fuzziness% -fill red ";
		foreach ($colors as $color) {
			$command .= "-opaque \"#$color\" ";
		}
		$command .= "-quality 100 $tmpFile";
		
		system($command);
		
		header("Content-Type: image/jpeg");
		echo file_get_contents($tmpFile);
		unlink($tmpFile);
	}
}