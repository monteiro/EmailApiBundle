<?php
namespace HP\Bundle\EmailApiBundle\ViewModel;

use HP\Bundle\EmailApiBundle\Entity\InboxMessage;
use HP\Bundle\EmailApiBundle\ViewModel\DTO\InboxMessageDTO;

class InboxMessageAssembler
{
    private $identityAssembler;

    public function __construct(IdentityAssembler $identityAssembler)
    {
        $this->identityAssembler = $identityAssembler;
    }

    public function write(InboxMessage $inboxMessage)
    {
        $dto = new InboxMessageDTO();
        $dto->id = $inboxMessage->getId();
        $dto->sender = $this->identityAssembler->write($inboxMessage->getSender())->serialize();
        $dto->date = $inboxMessage->getDate();
        $dto->subject = $inboxMessage->getSubject();
        $dto->snippet = $inboxMessage->getSnippet();

        return $dto;
    }
}
