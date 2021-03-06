<?php

namespace nickurt\Plesk;

use nickurt\Plesk\HttpClient\HttpClient;

class Client
{
    /**
     * @var
     */
    protected $httpClient;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param $method
     * @param $args
     * @return Api\Authentication|Api\Clients|Api\Domains
     */
    public function __call($method, $args)
    {
        try {
            return $this->api($method);
        } catch (InvalidArgumentException $e) {
            throw new \BadMethodCallException(sprintf('Undefined method called:"%s"', $method));
        }
    }

    /**
     * @param $name
     * @return Api\Authentication|Api\Clients|Api\Domains
     */
    public function api($name)
    {
        switch ($name) {
            case 'clients':
                $api = new \nickurt\Plesk\Api\Clients($this);
                break;
            case 'domains':
                $api = new \nickurt\Plesk\Api\Domains($this);
                break;
            case 'authentication':
                $api = new \nickurt\Plesk\Api\Authentication($this);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Undefined method called:"%s"', $name));
                break;
        }
        return $api;
    }

    /**
     * @param $username
     * @param $password
     */
    public function setCredentials($username, $password)
    {
        $this->getHttpClient()->setOptions([
            'username' => $username,
            'password' => $password
        ]);
    }

    /**
     * @param $host
     */
    public function setHost($host)
    {
        $this->getHttpClient()->setOptions([
            'host' => $host
        ]);
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if (!isset($this->httpClient)) {
            $this->httpClient = new HttpClient();
            $this->httpClient->setOptions($this->options);
        }

        return $this->httpClient;
    }
}
