<?php
namespace HP\Bundle\EmailApiBundle\Presenter;

use HP\Bundle\EmailApiBundle\Entity\Identity;
use HP\Bundle\EmailApiBundle\Presenter\DTO\IdentityDTO;

class IdentityAssembler
{
    public function write(Identity $identity)
    {
        $dto = new IdentityDTO();
        $dto->name = $identity->getName();
        $dto->email = $identity->getEmail();

        return $dto;
    }
}
