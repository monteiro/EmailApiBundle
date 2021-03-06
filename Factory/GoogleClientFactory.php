<?php
namespace HP\Bundle\EmailApiBundle\Factory;

/**
 * Class GoogleClientFactory
 * Creates the Client service of the Google API.
 */
class GoogleClientFactory
{
    /**
     * @var array
     */
    private $configuration;

    public function __construct($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Creates a google client using the current configuration.
     *
     * @return \Google_Client a new google client instance
     */
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
