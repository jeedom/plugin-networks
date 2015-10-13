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

class networks extends eqLogic {
	/*     * *************************Attributs****************************** */

	public static function cron() {
		foreach (self::byType('networks') as $networks) {
			$autorefresh = $networks->getConfiguration('autorefresh');
			if ($networks->getIsEnable() == 1 && $autorefresh != '') {
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
	}

	/*     * ***********************Methode static*************************** */

	/*     * *********************Méthodes d'instance************************* */

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
		$refresh->setEventOnly(1);
		$refresh->setEqLogic_id($this->getId());
		$refresh->save();

		$ping = $this->getCmd(null, 'ping');
		if (!is_object($ping)) {
			$ping = new networksCmd();
			$ping->setLogicalId('ping');
			$ping->setIsVisible(1);
			$ping->setName(__('Status', __FILE__));
		}
		$ping->setType('info');
		$ping->setSubType('binary');
		$ping->setEventOnly(1);
		$ping->setEqLogic_id($this->getId());
		$ping->save();

		$latency = $this->getCmd(null, 'latency');
		if (!is_object($latency)) {
			$latency = new networksCmd();
			$latency->setLogicalId('latency');
			$latency->setIsVisible(1);
			$latency->setName(__('Latence', __FILE__));
		}
		$latency->setType('info');
		$latency->setSubType('numeric');
		$latency->setEventOnly(1);
		$latency->setEqLogic_id($this->getId());
		$latency->setUnite('ms');
		$latency->save();

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
			$wol->setEventOnly(1);
			$wol->setEqLogic_id($this->getId());
			$wol->save();
		}
	}

	public function preUpdate() {
		if ($this->getConfiguration('ip') == '') {
			throw new Exception(__('L\'adresse IP ne peut être vide', __FILE__));
		}
	}

	public function ping() {
		if ($this->getConfiguration('ip') == '') {
			return;
		}
		$ping = new networks_Ping($this->getConfiguration('ip'));
		$latency_time = $ping->ping();
		if ($latency_time !== false) {
			$ping = $this->getCmd(null, 'ping');
			if (is_object($ping)) {
				$ping->event(1);
			}
			$latency = $this->getCmd(null, 'latency');
			if (is_object($latency)) {
				$latency->event($latency_time);
			}
		} else {
			$ping = $this->getCmd(null, 'ping');
			if (is_object($ping)) {
				$ping->event(0);
			}
			$latency = $this->getCmd(null, 'latency');
			if (is_object($latency)) {
				$latency->event(-1);
			}
		}
		$mc = cache::byKey('networksWidgetdashboard' . $this->getId());
		$mc->remove();
		$mc = cache::byKey('networksWidgetdview' . $this->getId());
		$mc->remove();
		$mc = cache::byKey('networksWidgetmobile' . $this->getId());
		$mc->remove();
		$mc = cache::byKey('networksWidgetmview' . $this->getId());
		$mc->remove();
		$this->refreshWidget();
	}

	public function toHtml($_version) {
		if ($this->getIsEnable() != 1) {
			return '';
		}
		if (!$this->hasRight('r')) {
			return '';
		}
		$_version = jeedom::versionAlias($_version);
		if ($this->getDisplay('hideOn' . $_version) == 1) {
			return '';
		}
		$mc = cache::byKey('networksWidget' . $_version . $this->getId());
		if ($mc->getValue() != '') {
			return preg_replace("/" . preg_quote(self::UIDDELIMITER) . "(.*?)" . preg_quote(self::UIDDELIMITER) . "/", self::UIDDELIMITER . mt_rand() . self::UIDDELIMITER, $mc->getValue());
		}
		$replace = array(
			'#name#' => $this->getName(),
			'#id#' => $this->getId(),
			'#background_color#' => '#bdc3c7',
			'#eqLink#' => ($this->hasRight('w')) ? $this->getLinkToConfiguration() : '#',
			'#uid#' => 'networks' . $this->getId() . self::UIDDELIMITER . mt_rand() . self::UIDDELIMITER,
		);

		foreach ($this->getCmd('info') as $cmd) {
			$replace['#' . $cmd->getLogicalId() . '_history#'] = '';
			$replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
			$replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd(null, 2);
			if ($cmd->getSubType() == 'numeric' && $replace['#' . $cmd->getLogicalId() . '#'] === '') {
				$replace['#' . $cmd->getLogicalId() . '#'] = 0;
			}
			$replace['#' . $cmd->getLogicalId() . '_collect#'] = $cmd->getCollectDate();
			if ($cmd->getIsHistorized() == 1) {
				$replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
			}
		}
		$replace['#action#'] = '';
		foreach ($this->getCmd('action') as $cmd) {
			if ($cmd->getLogicalId() == 'refresh') {
				continue;
			}
			$replace['#action#'] .= $cmd->toHtml($_version, '', '#7f8c8d');
		}
		$refresh = $this->getCmd(null, 'refresh');
		if (is_object($refresh)) {
			$replace['#refresh_id#'] = $refresh->getId();
		}

		$parameters = $this->getDisplay('parameters');
		if (is_array($parameters)) {
			foreach ($parameters as $key => $value) {
				$replace['#' . $key . '#'] = $value;
			}
		}
		if ($replace['#action#'] == '') {
			$html = template_replace($replace, getTemplate('core', $_version, 'networks', 'networks'));
		} else {
			$html = template_replace($replace, getTemplate('core', $_version, 'networks2', 'networks'));
		}

		cache::set('networksWidget' . $_version . $this->getId(), $html, 0);
		return $html;
	}

	/*     * **********************Getteur Setteur*************************** */
}

class networksCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function execute($_options = array()) {
		if ($this->getType() == 'info') {
			return;
		}
		$eqLogic = $this->getEqLogic();
		if ($this->getLogicalId() == 'wol') {
			$f = new \Phpwol\Factory();
			$magicPacket = $f->magicPacket();
			$result = $magicPacket->send($eqLogic->getConfiguration('mac'), $eqLogic->getConfiguration('broadcastIP'));
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

	/*     * **********************Getteur Setteur*************************** */
}

?>
