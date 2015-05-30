<?php
namespace HP\Bundle\EmailApiBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * OAuthUserProvider
 *
 * @author Geoffrey Bachelet <geoffrey.bachelet@gmail.com>
 */
class TokenOAuthUserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        return new TokenOAuthUser($username);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        return $this->loadUserByUsername($response->getEmail());
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s"', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === 'HP\\Bundle\\EmailApiBundle\\Security\\TokenOAuthUser';
    }
}
