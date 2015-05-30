<?php
namespace HP\Bundle\EmailApiBundle\Presenter\DTO;

/**
 * Class IdentityDTO
 * DTO that specifies an Identity that will be shown to the user.
 */
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
