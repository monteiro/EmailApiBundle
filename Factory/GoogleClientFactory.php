<?php
namespace HP\Bundle\EmailApiBundle\Factory;

class GoogleClientFactory
{
    private $configuration;

    public function __construct($configuration)
    {
        $this->configuration = $configuration;
    }

    public function create()
    {
        $client = new \Google_Client();
        $client->setApplicationName("EmailApiBundle");
        $client->setClientId($this->configuration['client_id']);
        $client->setClientSecret($this->configuration['client_secret']);
        $client->setRedirectUri($this->configuration['redirect_uri']);
        $client->addScope('https://www.googleapis.com/auth/gmail.readonly');

        return $client;
    }
}