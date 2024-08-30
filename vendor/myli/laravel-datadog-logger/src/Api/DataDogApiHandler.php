<?php

namespace Myli\DatadogLogger\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Logger;

/**
 * Class DataDogApiHandler
 *
 * @package   Myli\DatadogLogger\Api
 * @author    Aurélien SCHILTZ <aurelien@myli.io>
 * @copyright 2016-2019 Myli
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 */
class DataDogApiHandler extends AbstractProcessingHandler
{
    protected const HOST_US = 'http-intake.logs.datadoghq.com';
    protected const HOST_EU = 'http-intake.logs.datadoghq.eu';

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $host;

    /**
     * @param string     $token        API token supplied by DataDog
     * @param bool       $europeRegion Are your logs located in the EU region?
     * @param string|int $level        The minimum logging level to trigger this handler
     * @param bool       $bubble       Whether or not messages that are handled should bubble up the stack.
     *
     * @throws MissingExtensionException If the curl extension is missing
     */
    public function __construct(string $token, bool $europeRegion = false, $level = Logger::DEBUG, bool $bubble = true)
    {
        if (!extension_loaded('curl')) {
            throw new MissingExtensionException('The curl extension is needed to use the DataDogApiHandler');
        }
        $this->token = $token;
        $this->host  = $europeRegion ? self::HOST_EU : self::HOST_US;
        parent::__construct($level, $bubble);
    }

    /**
     * Get the Token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get the region of this DataDogApiHandler
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->host === self::HOST_EU ? 'eu' : 'us';
    }

    /**
     * Write implementation of AbstractProcessingHandler
     *
     * @param array $record
     *
     * @return void
     */
    protected function write(array $record) : void
    {
        $this->send($record["formatted"]);
    }

    /**
     * Send the log
     *
     * @param string $data
     *
     * @return void
     */
    protected function send(string $data) : void
    {
        $client = $this->getHttpClient();
        try {
            $client->request('POST', '', [
                'headers'         => [
                    'Content-Type' => 'application/json',
                ],
                'connect_timeout' => 3.14, // pi
                'body'            => $data
            ]);
        } catch (GuzzleException $e) {
            return;
        }
    }

    /**
     * Get the Client
     *
     * @return ClientInterface
     */
    public function getHttpClient() : ClientInterface
    {
        if (!$this->httpClient) {
            $this->httpClient = $this->initHttpClient();
        }

        return $this->httpClient;
    }

    /**
     * Set an http client
     *
     * @param ClientInterface $client
     *
     * @return DataDogApiHandler
     */
    public function setHttpClient(ClientInterface $client) : DataDogApiHandler
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * Init a Guzzle Client
     *
     * @return Client
     */
    private function initHttpClient()
    {
        $guzzleClient = new Client([
            'base_uri'    => sprintf("https://%s/v1/input/%s", $this->host, $this->token),
            'http_errors' => false,// we don't want the logger to stop the application for a failed log
            'debug'       => false,
        ]);

        return $guzzleClient;
    }
}
