<?php
namespace HP\Bundle\EmailApiBundle\Security\Authentication;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GoogleResourceOwner;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class GoogleApiAuthentication
 *
 * Refreshes a Google Access Token, updating the session.
 *
 */
class GoogleApiAuthentication implements ApiAuthenticationInterface
{
    /**
     * @var string
     */
    private $firewallName;

    /**
     * @var GoogleResourceOwner
     */
    private $googleResourceOwner;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(
        $firewallName,
        GoogleResourceOwner $googleResourceOwner,
        TokenStorageInterface $tokenStorage
    ) {
        $this->firewallName = $firewallName;
        $this->googleResourceOwner = $googleResourceOwner;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Refreshes the access token.
     *
     * @param  Request $request http request
     * @return bool    true if the token was refreshed, false otherwise
     */
    public function refreshToken(Request $request)
    {
        $session = $request->getSession();
        /**
         * The name of the session depends of HWIOAuthBundle
         */
        $oAuthToken = unserialize($session->get('_security_'.$this->firewallName));
        if (!empty($oAuthToken) && $this->isAccessTokenExpired($oAuthToken)) {
            return $this->refreshTokenWithResourceOwner($oAuthToken);
        }

        return false;
    }

    /**
     * Refreshes token asking for the resource owner (in this case Google).
     * The resource owner is responsible for saving it in the session the new access token.
     *
     * @param  OAuthToken $oAuthToken token that will refresh the token.
     * @return bool       true if the access token is set, false otherwise
     */
    private function refreshTokenWithResourceOwner(OAuthToken $oAuthToken)
    {
        $refreshToken = $oAuthToken->getRefreshToken();
        $response = $this->googleResourceOwner->refreshAccessToken($refreshToken);
        $tokenStored = $this->tokenStorage->getToken();
        if (!empty($tokenStored)) {
            $tokenStored->setAccessToken($response['access_token']);

            return true;
        }

        return false;
    }

    /**
     * Verifies if token has expired or not according to the $oAuthToken creation date.
     *
     * @param  OAuthToken $oAuthToken token that will be verified
     * @return bool       true if token has been expired, false otherwise
     */
    private function isAccessTokenExpired(OAuthToken $oAuthToken)
    {
        $nowTimestamp = (new \DateTime())->getTimestamp();
        $expiresInTimestamp = $oAuthToken->getCreatedAt() + $oAuthToken->getExpiresIn();

        return $nowTimestamp > $expiresInTimestamp;
    }
}
