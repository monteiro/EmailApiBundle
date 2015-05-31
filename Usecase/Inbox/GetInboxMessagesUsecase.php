<?php
namespace HP\Bundle\EmailApiBundle\Usecase\Inbox;

use HP\Bundle\EmailApiBundle\Gateway\EmailApiGatewayInterface;
use HP\Bundle\EmailApiBundle\Presenter\InboxMessagesAssembler;
use HP\Bundle\EmailApiBundle\Request\DTO\GetInboxMessagesRequestDTO;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class GetInboxMessagesUsecase
 *
 * This usecase when executed, reads the request and gets all the messages from the inbox.
 */
class GetInboxMessagesUsecase implements GetInboxMessagesUsecaseInterface
{
    /**
     * @var EmailApiGatewayInterface
     */
    private $gmailGateway;

    /**
     * @var InboxMessagesAssembler
     */
    private $inboxMessageAssembler;

    public function __construct(EmailApiGatewayInterface $gmailGateway, InboxMessagesAssembler $inboxMessageAssembler)
    {
        $this->gmailGateway = $gmailGateway;
        $this->inboxMessageAssembler = $inboxMessageAssembler;
    }

    /**
     * Executes the usecase
     *
     * @param GetInboxMessagesRequestDTO $request
     * @return JsonResponse
     */
    public function execute(GetInboxMessagesRequestDTO $request)
    {
        $maxResults = $request->maxResults;

        $this->gmailGateway->authenticate();
        $messages = $this->gmailGateway->getInbox($maxResults);

        return new JsonResponse([
            'email' => $this->gmailGateway->getPersonAuthenticatedEmail(),
            'messages' => $this->inboxMessageAssembler->write($messages),
        ]);
    }
}
