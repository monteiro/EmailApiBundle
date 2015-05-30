<?php
namespace HP\Bundle\EmailApiBundle\Presenter;

class InboxMessagesAssembler
{
    /**
     * @var InboxMessageAssembler
     */
    private $assembler;

    public function __construct(InboxMessageAssembler $inboxMessageAssembler)
    {
        $this->assembler = $inboxMessageAssembler;
    }

    /**
     * Goes through the list of inbox messages and creates a new list with the serialized objects.
     *
     * @param  array $inboxMessages list of inbox messages
     * @return array with all the DTOs already serialized
     */
    public function write(array $inboxMessages)
    {
        $resultSerialized = [];
        foreach ($inboxMessages as $message) {
            $resultSerialized[] = $this->assembler->write($message)->serialize();
        }

        return $resultSerialized;
    }
}
