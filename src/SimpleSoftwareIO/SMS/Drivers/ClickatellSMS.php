<?php namespace SimpleSoftwareIO\SMS\Drivers;

/**
 * Simple-SMS
 * A simple SMS message sending for Laravel.
 *
 * @link http://www.simplesoftware.io
 * @author SimpleSoftware support@simplesoftware.io
 *
 */

use GuzzleHttp\Client;
use SimpleSoftwareIO\SMS\OutgoingMessage;

class ClickatellSMS extends AbstractSMS implements DriverInterface {
	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * Configuration settings for Clickatell
	 * @var Client
	 */
	protected $config;

	/**
	 * The Clickatell Base URL
	 *
	 * @var string
	 */
	protected $apiBase = 'https://api.clickatell.com';

	/**
	 * The Clickatell session ID
	 *
	 * @var string
	 */
	protected $session_id;

	/**
	 * The Clickatell username
	 *
	 * @var string
	 */
	protected $username;

	/**
	 * The Clickatell password
	 *
	 * @var string
	 */
	protected $password;

	/**
	 * The Clickatell API ID
	 *
	 * @var string
	 */
	protected $apiId;

	/**
	 * @param Client $client
	 */
	function __construct(Client $client) {
		$this->client = $client;
	}

	/**
	 * Authenticates and opens SMS session
	 *
	 * @throws \ErrorException
	 * @return session
	 */
	protected function authenticate() {
		// Authentication URL
		$url = $this->apiBase . "/http/auth?user=" . $this->body['username']
		. "&password=" . $this->body['password']
		. "&api_id=" . $this->body['api_id'];

		// Do auth
		$response = file($url);
		$sess = explode(":", $response[0]);

		if ($sess[0] == "OK") {
			$this->session_id = trim($sess[1]);
		} else {
			throw new \RuntimeException('Authentication failure.');
		}
	}

	/**
	 * Creates many IncomingMessage objects and sets all of the properties.
	 *
	 * @param $rawMessage
	 * @return mixed
	 */
	protected function processReceive($rawMessage) {
		throw new \RuntimeException('Clickatell does not support Inbound API Calls.');
	}

	/**
	 * Sends a SMS message
	 *
	 * @parma SimpleSoftwareIO\SMS\Message @messasge The message class.
	 * @return void
	 */
	public function send(OutgoingMessage $message) {
		$this->authenticate();
		$composeMessage = urlencode($message->composeMessage());

		foreach ($message->getTo() as $to) {
			$url = $this->apiBase . '/http/sendmsg';
			$url .= '?session_id=' . $this->session_id;
			$url .= '&to=' . $to . '&text=' . $composeMessage;

			$ret = file($url);
			$response = explode(":", $ret[0]);
			if ($response[0] == "ID") {
				// TODO: action log
			} else {
				throw new \RuntimeException('send message failed.');
			}
		}
	}

	/**
	 * Checks the server for messages and returns their results.
	 *
	 * @param array $options
	 * @return array
	 */
	public function checkMessages(Array $options = array()) {
		throw new \RuntimeException('Clickatell does not support Inbound API Calls.');
	}

	/**
	 * Gets a single message by it's ID.
	 *
	 * @param $messageId
	 * @return IncomingMessage
	 */
	public function getMessage($messageId) {
		throw new \RuntimeException('Clickatell does not support Inbound API Calls.');
	}

	/**
	 * Receives an incoming message via REST call.
	 *
	 * @param $raw
	 * @return \SimpleSoftwareIO\SMS\IncomingMessage
	 */
	public function receive($raw) {
		// TODO: Implement receive() method.
	}

}
