<?php
namespace HP\Bundle\EmailApiBundle\Presenter\DTO;

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
