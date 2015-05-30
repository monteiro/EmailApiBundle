<?php
namespace HP\Bundle\EmailApiBundle\Presenter;

use HP\Bundle\EmailApiBundle\Entity\InboxMessage;
use HP\Bundle\EmailApiBundle\Presenter\DTO\InboxMessageDTO;

class InboxMessageAssembler
{
    private $identityAssembler;

    public function __construct(IdentityAssembler $identityAssembler)
    {
        $this->identityAssembler = $identityAssembler;
    }

    /**
     * Creates a DTO from the Entity.
     *
     * @param  InboxMessage    $inboxMessage Inbox message entity
     * @return InboxMessageDTO Inbox Message DTO from the InboxMessage
     */
    public function write(InboxMessage $inboxMessage)
    {
        $dto = new InboxMessageDTO();
        $dto->id = $inboxMessage->getId();
        $dto->sender = $this->identityAssembler->write($inboxMessage->getSender())->serialize();
        $dto->date = $inboxMessage->getTimestamp();
        $dto->subject = $inboxMessage->getSubject();
        $dto->snippet = $inboxMessage->getSnippet();

        return $dto;
    }
}
