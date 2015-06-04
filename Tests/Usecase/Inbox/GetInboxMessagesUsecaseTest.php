<?php
namespace HP\Bundle\EmailApiBundle\Tests\Usecase\Inbox;

use HP\Bundle\EmailApiBundle\Gateway\GoogleApiGateway;
use HP\Bundle\EmailApiBundle\Presenter\InboxMessageAssembler;
use HP\Bundle\EmailApiBundle\Request\GetInboxMessagesRequestBuilder;
use HP\Bundle\EmailApiBundle\Usecase\Inbox\GetInboxMessagesUsecase;

class GetInboxMessagesUsecaseTest extends \PHPUnit_Framework_TestCase
{
    const MAX_RESULTS = 100;

    /**
     * @var GetInboxMessagesUsecase
     */
    private $usecase;

    /**
     * @var GoogleApiGateway
     */
    private $gmailGateway;

    /**
     * @var InboxMessageAssembler
     */
    private $inboxMessagesAssembler;

    public function setUp()
    {
        $this->gmailGateway = $this->getMockBuilder('HP\Bundle\EmailApiBundle\Gateway\GoogleApiGateway')
            ->disableOriginalConstructor()
            ->getMock();

        $this->inboxMessagesAssembler =
            $this->getMockBuilder('HP\Bundle\EmailApiBundle\Presenter\InboxMessagesAssembler')
            ->disableOriginalConstructor()
            ->getMock();

        $this->usecase = new GetInboxMessagesUsecase($this->gmailGateway, $this->inboxMessagesAssembler);
    }

    /**
     * Tests the usecase execute() method.
     */
    public function testExecute()
    {
        $results = [1,2,3,4];
        $request = $this->getRequest();
        $this->mockGmailGateway($results);
        $this->mockInboxMessagesAssembler();

        $response = $this->usecase->execute($request);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
    }

    private function getRequest()
    {
        $requestBuilder = new GetInboxMessagesRequestBuilder();
        $request = $requestBuilder->create()->setMaxResults(GetInboxMessagesUsecaseTest::MAX_RESULTS)->build();

        return $request;
    }

    private function mockInboxMessagesAssembler()
    {
        $this->inboxMessagesAssembler->expects($this->once())->method('write');
    }

    private function mockGmailGateway($results)
    {
        $this->gmailGateway->expects($this->once())->method('authenticate');
        $this->gmailGateway->expects($this->once())
            ->method('getInbox')
            ->with(GetInboxMessagesUsecaseTest::MAX_RESULTS)
            ->will($this->returnValue($results));
    }
}
