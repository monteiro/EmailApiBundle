<?php
namespace HP\Bundle\EmailApiBundle\Entity;

/**
 * Class Identity
 * Entity that specifies an Identity in a recipient (e.g. John due <john.due@example.com>)
 */
class Identity
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
