<?php
namespace HP\Bundle\EmailApiBundle\ViewModel\DTO;

class InboxMessageDTO
{
    public $id;
    public $snippet;
    public $sender;
    public $subject;
    public $date;

    public function serialize()
    {
        return [
            'id' => $this->id,
            'snippet' => $this->snippet,
            'sender' => $this->sender,
            'subject' => $this->subject,
            'date' => $this->date
        ];
    }
}
