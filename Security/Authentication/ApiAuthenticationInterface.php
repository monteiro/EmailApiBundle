<?php
namespace HP\Bundle\EmailApiBundle\Security\Authentication;

use Symfony\Component\HttpFoundation\Request;

interface ApiAuthenticationInterface
{
    public function refreshToken(Request $request);
}
