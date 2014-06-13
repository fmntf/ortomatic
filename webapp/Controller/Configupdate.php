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

class Controller_Configupdate extends Controller
{
    public function run()
	{
		$fuzziness = $this->params[0];
		$colors = explode('-', $this->params[1]);
		
		$command = "convert ../public/webcam.jpg -fuzz $fuzziness% -fill red ";
		foreach ($colors as $color) {
			$command .= "-opaque \"#$color\" ";
		}
		
		$previewCommand = $command . " -quality 90 ../public/canopypreview.jpg";
		$previewCommand = '#!/bin/bash' . PHP_EOL . PHP_EOL . $previewCommand . PHP_EOL;
		file_put_contents(__DIR__ . '/../../scripts/canopy-preview.sh', $previewCommand);
		
		$canopyCommand = $command . " -quality 100 -format %c histogram:info: |grep FF0000 |awk '{print $1}' |sed 's/://'";
		$canopyCommand = '#!/bin/bash' . PHP_EOL . PHP_EOL . $canopyCommand . PHP_EOL;
		file_put_contents(__DIR__ . '/../../scripts/canopy.sh', $canopyCommand);
		
		usleep(300000); // per mostrare il caricamento
	}
}