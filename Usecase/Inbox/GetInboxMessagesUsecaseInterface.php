<?php
namespace HP\Bundle\EmailApiBundle\Usecase\Inbox;

use HP\Bundle\EmailApiBundle\Request\DTO\GetInboxMessagesRequestDTO;

/**
 * Interface GetInboxMessagesUsecaseInterface
 *
 * Executes the usecase according to a request.
 *
 */
interface GetInboxMessagesUsecaseInterface
{
    public function execute(GetInboxMessagesRequestDTO $request);
}
