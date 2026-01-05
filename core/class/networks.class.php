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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../php/networks.inc.php';

class networks extends eqLogic {
	/*     * ***********************Method static*************************** */

	public static function update() {
		/** @var networks */
		foreach (self::byType('networks', true) as $networks) {
			$autorefresh = $networks->getConfiguration('autorefresh');
			if ($autorefresh == '') continue;

			try {
				$c = new Cron\CronExpression($autorefresh, new Cron\FieldFactory);
				if ($c->isDue()) {
					try {
						$networks->ping();
					} catch (Exception $exc) {
						log::add('networks', 'error', __('Erreur pour ', __FILE__) . $networks->getHumanName() . ' : ' . $exc->getMessage());
					}
				}
			} catch (Exception $exc) {
				log::add('networks', 'error', __('Expression cron non valide pour ', __FILE__) . $networks->getHumanName() . ' : ' . $autorefresh);
			}
		}
	}

	/*     * ***********************Methode static*************************** */

	/*     * *********************Méthodes d'instance************************* */

	public function preInsert() {
		$this->setConfiguration('pingMode', 'ip');
	}

	public function preUpdate() {
		if ($this->getConfiguration('ip') == '') {
			throw new Exception(__('L\'adresse IP ne peut être vide', __FILE__));
		}
	}

	public function preSave() {
		if ($this->getConfiguration('autorefresh') == '') {
			$this->setConfiguration('autorefresh', '* * * * *');
		}
	}

	public function postSave() {
		$refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new networksCmd();
			$refresh->setLogicalId('refresh');
			$refresh->setIsVisible(1);
			$refresh->setName(__('Rafraîchir', __FILE__));
		}
		$refresh->setType('action');
		$refresh->setSubType('other');
		$refresh->setEqLogic_id($this->getId());
		$refresh->save();

		$ping = $this->getCmd(null, 'ping');
		if (!is_object($ping)) {
			$ping = new networksCmd();
			$ping->setLogicalId('ping');
			$ping->setIsVisible(1);
			$ping->setName(__('Statut', __FILE__));
			$ping->setOrder(1);
			$ping->setTemplate('dashboard', 'line');
			$ping->setConfiguration('repeatEventManagement', 'never');
		}
		$ping->setType('info');
		$ping->setSubType('binary');
		$ping->setEqLogic_id($this->getId());
		$ping->save();

		$latency = $this->getCmd(null, 'latency');
		if (!is_object($latency)) {
			$latency = new networksCmd();
			$latency->setLogicalId('latency');
			$latency->setIsVisible(1);
			$latency->setName(__('Latence', __FILE__));
			$latency->setOrder(2);
			$latency->setTemplate('dashboard', 'line');
		}
		$latency->setType('info');
		$latency->setSubType('numeric');
		$latency->setEqLogic_id($this->getId());
		$latency->setUnite('ms');
		$latency->save();

		$addressIP = $this->getCmd(null, 'addresseIP');
		if (!is_object($addressIP)) {
			$addressIP = new networksCmd();
			$addressIP->setLogicalId('addresseIP');
			$addressIP->setIsVisible(1);
			$addressIP->setName(__('addresseIP', __FILE__));
			$addressIP->setOrder(3);
		}
		$addressIP->setType('info');
		$addressIP->setSubType('string');
		$addressIP->setEqLogic_id($this->getId());
		$addressIP->save();
		$addressIP->event($this->getConfiguration('ip', ''));

		$addressMAC = $this->getCmd(null, 'addresseMAC');
		if (!is_object($addressMAC)) {
			$addressMAC = new networksCmd();
			$addressMAC->setLogicalId('addresseMAC');
			$addressMAC->setIsVisible(1);
			$addressMAC->setName(__('addresseMAC', __FILE__));
			$addressMAC->setOrder(4);
		}
		$addressMAC->setType('info');
		$addressMAC->setSubType('string');
		$addressMAC->setEqLogic_id($this->getId());
		$addressMAC->save();
		$addressMAC->event($this->getConfiguration('mac'), '');

		$wol = $this->getCmd(null, 'wol');
		if ($this->getConfiguration('mac') == '' || $this->getConfiguration('broadcastIP') == '') {
			if (is_object($wol)) {
				$wol->remove();
			}
		} else {
			if (!is_object($wol)) {
				$wol = new networksCmd();
				$wol->setLogicalId('wol');
				$wol->setIsVisible(1);
				$wol->setName(__('Wake-on-lan', __FILE__));
			}
			$wol->setType('action');
			$wol->setSubType('other');
			$wol->setEqLogic_id($this->getId());
			$wol->save();
		}
	}

	public function ping() {
		if ($this->getConfiguration('ip') == '') {
			return;
		}
		$ping = new networks_Ping($this->getConfiguration('ip'), $this->getConfiguration('ttl', 255), $this->getConfiguration('timeout', 3));
		$pingMode = $this->getConfiguration('pingMode', 'ip');
		if ($pingMode == 'port') {
			$ping->setPort($this->getConfiguration('port', 80));
		}

		$maxTry = max(min(10, $this->getConfiguration('maxTry', 3)), 1);
		do {
			log::add(__CLASS__, 'debug', '[' . getmypid() . '] ' . __('Tentative de ping sur : ', __FILE__) . $this->getHumanName());
			$latency_time = $ping->ping($pingMode);
			usleep(100);
		} while ($latency_time === false && --$maxTry > 0);

		if ($this->getConfiguration('notifyifko') == 1) {
			if ($latency_time === false) {
				message::add('networks', __('Echec du ping sur : ', __FILE__) . $this->getHumanName(), '', 'pingFailed' . $this->getId());
			} else {
				foreach (message::byPluginLogicalId('networks', 'pingFailed' . $this->getId()) as $message) {
					$message->remove();
				}
			}
		}
		if ($latency_time !== false) {
			log::add(__CLASS__, 'info', '[' . getmypid() . '] ' . __('Ping réussi sur : ', __FILE__) . $this->getHumanName());
			$this->checkAndUpdateCmd('ping', 1);
			$this->checkAndUpdateCmd('latency', $latency_time);
		} else {
			log::add(__CLASS__, 'info', '[' . getmypid() . '] ' . __('Ping échoué sur : ', __FILE__) . $this->getHumanName());
			$this->checkAndUpdateCmd('ping', 0);
			$this->checkAndUpdateCmd('latency', -1);
		}
	}
}

class networksCmd extends cmd {
	/*     * *************************Attributs****************************** */

	public static $_widgetPossibility = array('custom' => true);

	/*     * ***********************Method static*************************** */

	/*     * *********************Method d'instance************************* */

	public function execute($_options = array()) {
		if ($this->getType() == 'info') {
			return;
		}
		/** @var networks */
		$eqLogic = $this->getEqLogic();
		if ($this->getLogicalId() == 'wol') {
			$f = new \Phpwol\Factory();
			$magicPacket = $f->magicPacket();
			$result = $magicPacket->send(trim($eqLogic->getConfiguration('mac')), trim($eqLogic->getConfiguration('broadcastIP')));
			if (!$result) {
				$error = '';
				switch ($magicPacket->getLastError()) {
					case 1:
						$error = __('IP invalide', __FILE__);
						break;
					case 2:
						$error = __('MAC invalide', __FILE__);
						break;
					case 4:
						$error = __('SUBNET invalide', __FILE__);
						break;
					default:
						$error = $magicPacket->getLastError();
						break;
				}
				throw new Exception(__('Echec de la commande : ', __FILE__) . $error);
			}
		}
		if ($this->getLogicalId() == 'refresh') {
			$eqLogic->ping();
		}
	}
}
