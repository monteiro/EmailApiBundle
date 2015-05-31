<?php
namespace HP\Bundle\EmailApiBundle\Tests\Request;

use HP\Bundle\EmailApiBundle\Request\GetInboxMessagesRequestBuilder;

class GetInboxMessagesRequestBuilderTest extends \PHPUnit_Framework_TestCase
{
    const MAX_RESULTS = 100;

    /**
     * @var GetInboxMessagesRequestBuilder
     */
    private $builder;

    public function setUp()
    {
        $this->builder = new GetInboxMessagesRequestBuilder();
    }

    /**
     * Tests the builder creating all the parameters available to set and verify them
     */
    public function testBuilder()
    {
        $dto = $this->builder
            ->create()
            ->setMaxResults(GetInboxMessagesRequestBuilderTest::MAX_RESULTS)
            ->build();

        $this->assertInstanceOf('HP\Bundle\EmailApiBundle\Request\DTO\GetInboxMessagesRequestDTO', $dto);
        $this->assertEquals(GetInboxMessagesRequestBuilderTest::MAX_RESULTS, $dto->maxResults);
    }
}
