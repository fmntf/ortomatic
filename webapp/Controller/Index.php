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
		$this->viewVars = array(
			'humidity' => 87.3,
			't0' => 23.3,
			't1' => 25.1,
			'serieH' => [5.5,4,1,6,8,5,3],
			'serieT0' => [5.5,4,1,6,8,5,3],
			'serieT1' => [1,6,8,5,3,5.5,4],
		);
		$this->render('index');
	}
}