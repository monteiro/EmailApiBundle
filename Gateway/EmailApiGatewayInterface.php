<?php
namespace HP\Bundle\EmailApiBundle\Gateway;

interface EmailApiGatewayInterface
{
    const MAX_RESULTS_DEFAULT = 50;

    /**
     * Authenticates the API client.
     */
    public function authenticate();

    /**
     * Gets the user email from the TokenStorage service.
     *
     * @return string user email
     */
    public function getPersonAuthenticatedEmail();

    /**
     * Gets all the messages from the User Authenticated Inbox.
     *
     * @param  int   $maxResults max messages to get
     * @return array inbox messages
     */
    public function getInbox($maxResults = EmailApiGatewayInterface::MAX_RESULTS_DEFAULT);
}
