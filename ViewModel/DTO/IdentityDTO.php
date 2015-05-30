<?php
namespace HP\Bundle\EmailApiBundle\ViewModel\DTO;

class IdentityDTO
{
    public $name;
    public $email;

    public function serialize()
    {
        return [
            'name' => $this->name,
            'email' => $this->email
        ];
    }
}
