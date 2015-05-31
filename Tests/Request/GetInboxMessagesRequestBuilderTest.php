<?php
namespace HP\Bundle\EmailApiBundle\Tests\Request;

use HP\Bundle\EmailApiBundle\Request\GetInboxMessagesRequestBuilder;

class GetInboxMessagesRequestBuilderTest extends \PHPUnit_Framework_TestCase
{
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
        $maxResults = 100;

        $dto = $this->builder
            ->create()
            ->setMaxResults($maxResults)
            ->build();

        $this->assertInstanceOf('HP\Bundle\EmailApiBundle\Request\DTO\GetInboxMessagesRequestDTO', $dto);
        $this->assertEquals($maxResults, $dto->maxResults);
    }
}
