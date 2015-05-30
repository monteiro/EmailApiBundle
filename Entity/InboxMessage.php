<?php
namespace HP\Bundle\EmailApiBundle\Entity;

class InboxMessage
{
    private $id;

    private $snippet;

    /**
     * @var \HP\Bundle\EmailApiBundle\Entity\Identity
     */
    private $sender;

    private $subject;

    private $date;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getSnippet()
    {
        return $this->snippet;
    }

    public function setSnippet($snippet)
    {
        $this->snippet = $snippet;

        return $this;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }
}
