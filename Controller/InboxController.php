<?php
namespace HP\Bundle\EmailApiBundle\Controller;

use HP\Bundle\EmailApiBundle\Gateway\EmailApiGatewayInterface;
use HP\Bundle\EmailApiBundle\Presenter\InboxMessagesAssembler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class InboxController extends Controller
{
    private $gmailGateway;
    private $inboxMessageAssembler;

    public function __construct(EmailApiGatewayInterface $gmailGateway, InboxMessagesAssembler $inboxMessageAssembler)
    {
        $this->gmailGateway = $gmailGateway;
        $this->inboxMessageAssembler = $inboxMessageAssembler;
    }

    /**
     * Get all the emails from the Inbox.
     *
     * @return JsonResponse with the authenticated and user messages information
     */
    public function getMessagesAction()
    {
        $this->gmailGateway->authenticate();
        $this->gmailGateway->getInbox();
        $messages = $this->gmailGateway->getInbox();

        return new JsonResponse([
            'email' => $this->gmailGateway->getPersonAuthenticatedEmail(),
            'messages' => $this->inboxMessageAssembler->write($messages),
        ]);
    }
}
