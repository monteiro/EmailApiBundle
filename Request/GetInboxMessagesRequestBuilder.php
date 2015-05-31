<?php
namespace HP\Bundle\EmailApiBundle\Request;

use HP\Bundle\EmailApiBundle\Request\DTO\GetInboxMessagesRequestDTO;

/**
 * Class GetInboxMessagesRequestBuilder
 *
 * Used to create a request (a dto) that will be sent to the usecase.
 *
 */
class GetInboxMessagesRequestBuilder implements GetInboxMessagesRequestBuilderInterface
{
    /**
     * @var GetInboxMessagesRequestDTO
     */
    private $dto;

    public function create()
    {
        $this->dto = new GetInboxMessagesRequestDTO();

        return $this;
    }

    public function setMaxResults($maxResults)
    {
        $this->dto->maxResults = $maxResults;

        return $this;
    }

    public function build()
    {
        return $this->dto;
    }
}
