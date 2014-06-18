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

$exported = is_dir("/sys/class/gpio/gpio25/");
if (!$exported) {
	echo "Exporting GPIO 25..." . PHP_EOL;
	system("echo 25 > /sys/class/gpio/export");
}

system("echo out > /sys/class/gpio/gpio25/direction");

echo "Blinking forever..." . PHP_EOL;

while (true) {
	system("echo 1 > /sys/class/gpio/gpio25/value");
	usleep(300000);
	system("echo 0 > /sys/class/gpio/gpio25/value");
	usleep(300000);
}
