<?php
namespace HP\Bundle\EmailApiBundle\Security;

use HP\Bundle\EmailApiBundle\Security\Authentication\ApiAuthenticationInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class OAuthTokenExpiredListener
 *
 * When a request arrives it verifies if the Token is expired or not and renews it.
 */
class OAuthTokenExpiredListener
{
    /**
     * @var ApiAuthenticationInterface
     */
    private $apiAuthentication;

    public function __construct(ApiAuthenticationInterface $apiAuthentication)
    {
        $this->apiAuthentication = $apiAuthentication;
    }

    /**
     * When a response arrives, it verifies if the token is not expired.
     *
     * @param GetResponseEvent $event event used to create a response
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->apiAuthentication->refreshToken($event->getRequest());
    }
}
