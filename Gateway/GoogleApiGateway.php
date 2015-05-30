<?php
namespace HP\Bundle\EmailApiBundle\Gateway;

use HP\Bundle\EmailApiBundle\Entity\Identity;
use HP\Bundle\EmailApiBundle\Entity\InboxMessage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GoogleApiGateway implements EmailApiGatewayInterface
{
    private $client;
    private $tokenStorage;

    public function __construct(\Google_Client $client, TokenStorageInterface $tokenStorage)
    {
        $this->client = $client;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * This must be later refactored to a new client that does only the authentication
     */
    public function authenticate()
    {
        /**
         *  {"access_token":"TOKEN", "refresh_token":"TOKEN", "token_type":"Bearer",
         *  "expires_in":3600, "id_token":"TOKEN", "created":1320790426 }
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
    }

    public function getPersonAuthenticatedEmail()
    {
        return $this->tokenStorage->getToken()->getUsername();
    }

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

    private function getMessagesWithInformation($gmailService, $userMessages)
    {
        $inboxMessagesWithInformation = [];
        $this->client->setUseBatch(true);
        $batch =new \Google_Http_Batch($this->client);
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
        $messagesWithInfo = $batch->execute();
        foreach ($messagesWithInfo as $messageWithInfo) {
            $inboxMessage = $this->getMessage($messageWithInfo);
            $inboxMessagesWithInformation[] = $inboxMessage;
        }

        return $inboxMessagesWithInformation;
    }

    private function getMessage($message)
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
                $inboxMessage->setDate(strtotime($messageDate));
            } elseif ($single->getName() == 'From') {
                $messageSender = $single->getValue();
                $messageSender = str_replace('"', '', $messageSender);
                $inboxMessage->setSender($this->getIdentity($messageSender));
            }
        }

        return $inboxMessage;
    }

    private function getIdentity($recipientStr)
    {
        /**
         * "John Due <john.due@example.com>"
         */
        $pattern = '/\s*"?([^><,"]+)"?\s*(?:<([^><,]+)>)?\s*/';
        preg_match($pattern, $recipientStr, $matches);

        $identity = new Identity();
        $identity->setName($matches[1]);
        $identity->setEmail($matches[2]);

        return $identity;
    }
}
