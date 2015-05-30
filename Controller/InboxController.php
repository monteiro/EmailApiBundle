<?php
namespace HP\Bundle\EmailApiBundle\Controller;

use HP\Bundle\EmailApiBundle\Gateway\EmailApiGatewayInterface;
use HP\Bundle\EmailApiBundle\ViewModel\InboxMessagesAssembler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class InboxController extends Controller
{
    private $gmailGateway;
    private $inboxMessageAssembler;

    public function __construct(EmailApiGatewayInterface $gmailGateway, InboxMessagesAssembler $inboxMessageAssembler)
    {
        $this->gmailGateway = $gmailGateway;
        $this->inboxMessageAssembler = $inboxMessageAssembler;
    }

    public function getMessagesAction()
    {
        $this->gmailGateway->authenticate();
        $this->gmailGateway->getInbox();
        $messages = $this->gmailGateway->getInbox(1);

        return new JsonResponse([
            'email' => $this->gmailGateway->getPersonAuthenticatedEmail(),
            'messages' => $this->inboxMessageAssembler->write($messages)
        ]);
    }
}
