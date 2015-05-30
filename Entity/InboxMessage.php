<?php
namespace HP\Bundle\EmailApiBundle\Entity;

/**
 * Class InboxMessage
 * Entity that specifies an InboxMessage.
 */
class InboxMessage
{
    /**
     * @var int
     */
    private $id;

    /**
     * Snippet of the message body
     *
     * @var string
     **/
    private $snippet;

    /**
     * Name and Email of the sender
     *
     * @var \HP\Bundle\EmailApiBundle\Entity\Identity
     */
    private $sender;

    /**
     * @var string
     */
    private $subject;

    /**
     * In Milliseconds of the message date
     *
     * @var int
     */
    private $timestamp;

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

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}
