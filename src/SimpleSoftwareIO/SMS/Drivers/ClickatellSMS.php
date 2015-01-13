<?php namespace SimpleSoftwareIO\SMS\Drivers;

/**
 * Simple-SMS
 * A simple SMS message sending for Laravel.
 *
 * @link http://www.simplesoftware.io
 * @author SimpleSoftware support@simplesoftware.io
 *
 */

use SimpleSoftwareIO\SMS\OutgoingMessage;

class ClickatellSMS extends AbstractSMS implements DriverInterface {
    /**
     * @var Client
     */
    protected $config;
    
    /**
     * @var string
     */
    protected $apiBase;

    function __construct()
    {
        $this->config = Config::get('clickatell::clickatell');
        $this->apiBase = $this->config['base_url'];
    }

    /**
     * Creates many IncomingMessage objects and sets all of the properties.
     *
     * @param $rawMessage
     * @return mixed
     */
    protected function processReceive($rawMessage)
    {
        throw new \RuntimeException('Optikseis does not support Inbound API Calls.');
    }

    /**
     * Sends a SMS message
     *
     * @parma SimpleSoftwareIO\SMS\Message @messasge The message class.
     * @return void
     */
    public function send(OutgoingMessage $message)
    {
        $composeMessage = $message->composeMessage();

        foreach($message->getTo() as $to)
        {
            $data = [
                'hp'        => $to,
                'message'   => $composeMessage
            ];

            $this->buildBody($data);

            $this->getRequest();
        }
    }

    /**
     * Checks the server for messages and returns their results.
     *
     * @param array $options
     * @return array
     */
    public function checkMessages(Array $options = array())
    {
        throw new \RuntimeException('Optikseis does not support Inbound API Calls.');
    }

    /**
     * Gets a single message by it's ID.
     *
     * @param $messageId
     * @return IncomingMessage
     */
    public function getMessage($messageId)
    {
        throw new \RuntimeException('Optikseis does not support Inbound API Calls.');
    }

    /**
     * Receives an incoming message via REST call.
     *
     * @param $raw
     * @return \SimpleSoftwareIO\SMS\IncomingMessage
     */
    public function receive($raw)
    {
        // TODO: Implement receive() method.  Waiting for Optikseis to Enable REST.
    }

}