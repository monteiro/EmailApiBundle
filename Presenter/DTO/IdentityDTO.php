<?php
namespace HP\Bundle\EmailApiBundle\Presenter\DTO;

class IdentityDTO
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    public function serialize()
    {
        return [
            'name' => $this->name,
            'email' => $this->email
        ];
    }
}
