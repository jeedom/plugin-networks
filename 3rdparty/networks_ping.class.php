<?php
class networks_Ping {
	private $host;
	private $ttl;
	private $timeout;
	private $port = 80;

	public function __construct($host, $ttl = 255, $timeout = 3) {
		if (!isset($host)) {
			throw new \Exception("Error: Host name not supplied.");
		}
		$this->host = $host;
		$this->ttl = $ttl;
		$this->timeout = $timeout;
	}

	public function setTtl($ttl) {
		$this->ttl = $ttl;
	}

	public function getTtl() {
		return $this->ttl;
	}

	public function setHost($host) {
		$this->host = $host;
	}

	public function getHost() {
		return $this->host;
	}

	public function setPort($port) {
		$this->port = $port;
	}

	public function getPort() {
		return $this->port;
	}

	public function ping($method = 'ip') {
		switch ($method) {
			case 'ip':
				return $this->pingExec('ip');
			case 'arp':
				return $this->pingExec('arp');
			case 'port':
				return $this->pingPort();
		}
		return false;
	}

	private function pingExec($_mode = 'ip') {
		$latency = false;
		$ttl = escapeshellcmd($this->ttl);
		$host = escapeshellcmd($this->host);
		$timeout = escapeshellcmd($this->timeout);
		if ($_mode == 'arp') {
			$exec_string = "sudo arping -c 1 -C 1 -w 10 -W {$timeout} {$host} 2> /dev/null";
		} else {
			$exec_string = "sudo ping -n -c 1 -t {$ttl} -W {$timeout} {$host} 2> /dev/null";
		}
		exec($exec_string, $output, $return);
		$output = array_values(array_filter($output));
		if (!empty($output[1])) {
			if (count($output) >= 5) {
				$response = preg_match("/time(?:=|<)(?<time>[\.0-9]+)(?:|\s)(?<unit>[mu]?s(ec)?)/", $output[count($output) - 4], $matches);
				if ($response > 0 && isset($matches['time'])) {
					$latency = $matches['time'];
					if (isset($matches['unit'])) {
						if ($matches['unit'] == 's' || $matches['unit'] == 'sec') {
							$latency *= 1000;
						} elseif ($matches['unit'] == 'us' || $matches['unit'] == 'usec') {
							$latency /= 1000;
						}
					}
				}
			}
		}
		return $latency;
	}

	private function pingPort() {
		$start = microtime(true);
		$fp = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
		if (!$fp) {
			$latency = false;
		} else {
			$latency = microtime(true) - $start;
			$latency = round($latency * 1000);
		}
		return $latency;
	}
}
