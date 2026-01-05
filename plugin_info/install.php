<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function networks_install() {
	$pathMobile = dirname(__FILE__) . '/../mobile';
	if (file_exists($pathMobile)) {
		shell_exec('rm -r ' . $pathMobile);
	}
	$cron = cron::byClassAndFunction('networks', 'update');
	if (!is_object($cron)) {
		$cron = new cron();
		$cron->setClass('networks');
		$cron->setFunction('update');
		$cron->setEnable(1);
		$cron->setDeamon(0);
		$cron->setSchedule('* * * * *');
		$cron->setTimeout(30);
		$cron->save();
	}
}

function networks_update() {
	$pathMobile = dirname(__FILE__) . '/../mobile';
	if (file_exists($pathMobile)) {
		shell_exec('rm -r ' . $pathMobile);
	}
	$cron = cron::byClassAndFunction('networks', 'update');
	if (!is_object($cron)) {
		$cron = new cron();
	}
	$cron->setClass('networks');
	$cron->setFunction('update');
	$cron->setEnable(1);
	$cron->setDeamon(0);
	$cron->setSchedule('* * * * *');
	$cron->setTimeout(30);
	$cron->save();
	$cron->stop();
	foreach (eqLogic::byType('networks') as $eqLogic) {
		$ping = $eqLogic->getCmd(null, 'ping');
		$ping->setConfiguration('repeatEventManagement', 'never');
		$ping->save();
	}
}

function networks_remove() {
	$cron = cron::byClassAndFunction('networks', 'update');
	if (is_object($cron)) {
		$cron->remove();
	}
}
