<?php
namespace HP\Bundle\EmailApiBundle\Gateway;

use HP\Bundle\EmailApiBundle\Entity\Identity;
use HP\Bundle\EmailApiBundle\Entity\InboxMessage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class GoogleApiGateway
 * All of the Google Api operations. (e.g. Retrieving messages, authenticating, etc)
 */
class GoogleApiGateway implements EmailApiGatewayInterface
{
    /**
     * @var $client \Google_Client google client api
     */
    private $client;

    /**
     * @var $tokenStorage TokenStorageInterface token storage of the current authentication
     */
    private $tokenStorage;

    public function __construct(\Google_Client $client, TokenStorageInterface $tokenStorage)
    {
        $this->client = $client;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     *  Authenticates the Google API Client according to the current user authenticated.
     */
    public function authenticate()
    {
        /**
         *  {"access_token":"TOKEN", "refresh_token":"TOKEN", "token_type":"Bearer",
         *  "expires_in":3600, "id_token":"TOKEN", "created":1320790426 }
         *
         * @var $oauthToken \HP\Bundle\EmailApiBundle\Security\TokenOAuthUser
         * @return bool true if the token is not expired, else otherwise
         */
        $oauthToken = $this->tokenStorage->getToken();
        $token = [
            'access_token' => $oauthToken->getAccessToken(),
            'refresh_token' => $oauthToken->getAccessToken(),
            'token_type' => 'Bearer',
            'expires_in' => $oauthToken->getExpiresIn(),
            'id_token' => $oauthToken->getAccessToken(),
            'created' =>  $oauthToken->getCreatedAt(),
        ];
        $this->client->setAccessToken(json_encode($token));

        return !$this->client->isAccessTokenExpired();
    }

    /**
     * @return string user's email authenticated
     */
    public function getPersonAuthenticatedEmail()
    {
        return $this->tokenStorage->getToken()->getUsername();
    }

    /**
     * Get all the messages Ids from the Gmail service and get all the Inbox messages information.
     *
     * @param int $maxResults max results to be returned
     * @return array inbox messages
     */
    public function getInbox($maxResults = EmailApiGatewayInterface::MAX_RESULTS_DEFAULT)
    {
        $gmailService = new \Google_Service_Gmail($this->client);
        $optParams = [];
        $optParams['maxResults'] = $maxResults;
        $optParams['labelIds'] = 'INBOX'; // Only show messages in Inbox
        $this->client->setUseBatch(false);
        $inboxMessages = $gmailService->users_messages->listUsersMessages($this->getPersonAuthenticatedEmail(), $optParams);
        $inboxMessagesWithInformation = $this->getMessagesWithInformation($gmailService, $inboxMessages);

        return $inboxMessagesWithInformation;
    }

    /**
     * Creates a google batch with all the requests
     *
     * @param $gmailService
     * @param $userMessages
     * @return array
     */
    private function getMessagesWithInformation($gmailService, $userMessages)
    {
        $this->client->setUseBatch(true);
        $batch = new \Google_Http_Batch($this->client);
        $messages = $userMessages->getMessages();
        for ($i = 0; $i < count($messages); $i++) {
            $messageId = $messages[$i]->getId();
            $optionalParameters['format'] = 'metadata';
            $getMessagesRequest = $gmailService->users_messages->get(
                $this->getPersonAuthenticatedEmail(),
                $messageId,
                $optionalParameters
            );
            $batch->add($getMessagesRequest);
        }
        $messagesWithInformationBatch = $batch->execute();
        $inboxMessagesWithInformation = $this->parseMessagesWithInformation($messagesWithInformationBatch);

        return $inboxMessagesWithInformation;
    }

    /**
     * Parse the messages that come from the batch.
     *
     * @param  array $messagesWithInformation batch messages result
     * @return array array of inboxMessages
     */
    private function parseMessagesWithInformation(array $messagesWithInformation)
    {
        $inboxMessagesWithInformation = [];
        foreach ($messagesWithInformation as $messageWithInfo) {
            $inboxMessage = $this->getMessage($messageWithInfo);
            $inboxMessagesWithInformation[] = $inboxMessage;
        }

        return $inboxMessagesWithInformation;
    }

    /**
     * Gets the headers and create an InboxMessage object with all of the information needed by the Inbox.
     *
     * @param  \Google_Service_Gmail_Message $message message to parse
     * @return InboxMessage                  inbox message object
     */
    private function getMessage(\Google_Service_Gmail_Message $message)
    {
        $inboxMessage = new InboxMessage();
        $inboxMessage->setId($message['id']);
        $optionalParameters['format'] = 'metadata';
        $headers = $message->getPayload()->getHeaders();
        $inboxMessage->setSnippet($message->getSnippet());
        foreach ($headers as $single) {
            if ($single->getName() == 'Subject') {
                $inboxMessage->setSubject($single->getValue());
            } elseif ($single->getName() == 'Date') {
                $messageDate = $single->getValue();
                $inboxMessage->setTimestamp(strtotime($messageDate));
            } elseif ($single->getName() == 'From') {
                $messageSender = $single->getValue();
                $messageSender = str_replace('"', '', $messageSender);
                $inboxMessage->setSender($this->getIdentity($messageSender));
            }
        }

        return $inboxMessage;
    }

    /**
     * According to a recipientStr creates an Identity object.
     * Example of the $recipientStr:
     *   "John Due <john.due@example.com>"
     *
     * @param $recipientStr
     * @return Identity
     */
    private function getIdentity($recipientStr)
    {
        $pattern = '/\s*"?([^><,"]+)"?\s*(?:<([^><,]+)>)?\s*/';
        preg_match($pattern, $recipientStr, $matches);

        $identity = new Identity();
        $identity->setName($matches[1]);
        $identity->setEmail($matches[2]);

        return $identity;
    }
}
