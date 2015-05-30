<?php
namespace HP\Bundle\EmailApiBundle\ViewModel;

use HP\Bundle\EmailApiBundle\Entity\Identity;
use HP\Bundle\EmailApiBundle\ViewModel\DTO\IdentityDTO;

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
