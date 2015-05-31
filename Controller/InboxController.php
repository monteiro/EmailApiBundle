<?php
namespace HP\Bundle\EmailApiBundle\Controller;

use HP\Bundle\EmailApiBundle\Gateway\EmailApiGatewayInterface;
use HP\Bundle\EmailApiBundle\Presenter\InboxMessagesAssembler;
use HP\Bundle\EmailApiBundle\Request\GetInboxMessagesRequestBuilderInterface;
use HP\Bundle\EmailApiBundle\Usecase\Inbox\GetInboxMessagesUsecaseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class InboxController
 * Controller that calls the usecases.
 */
class InboxController extends Controller
{
    /**
     * @var GetInboxMessagesUsecaseInterface
     */
    private $getInboxMessagesUsecase;

    /**
     * @var GetInboxMessagesRequestBuilderInterface
     */
    private $getInboxMessagesRequestBuilder;

    public function __construct(
        GetInboxMessagesUsecaseInterface $getInboxMessagesUsecase,
        GetInboxMessagesRequestBuilderInterface $getInboxMessagesRequestBuilder
    ) {
        $this->getInboxMessagesUsecase = $getInboxMessagesUsecase;
        $this->getInboxMessagesRequestBuilder = $getInboxMessagesRequestBuilder;
    }

    /**
     * Get all the emails from the Inbox.
     *
     * @return JsonResponse with the authenticated and user messages information
     */
    public function getMessagesAction(Request $frameworkRequest)
    {
        $params = json_decode($frameworkRequest->getContent());
        $request = $this->getInboxMessagesRequestBuilder
            ->create()
            ->setMaxResults(isset($params['max_results']) ?: $params['maxResults'])
            ->build();

        return $this->getInboxMessagesUsecase->execute($request);
    }
}
