<?php
namespace HP\Bundle\EmailApiBundle\ViewModel;

class InboxMessagesAssembler
{
    private $assembler;

    public function __construct(InboxMessageAssembler $inboxMessageAssembler)
    {
        $this->assembler = $inboxMessageAssembler;
    }

    public function write(array $inboxMessages) {
        $resultSerialized = [];
        foreach($inboxMessages as $message) {
            $resultSerialized[] = $this->assembler->write($message)->serialize();
        }

        return $resultSerialized;
    }
}
