<?php
namespace HP\Bundle\EmailApiBundle\Tests\Security\Authentication;

use HP\Bundle\EmailApiBundle\Security\Authentication\GoogleApiAuthentication;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GoogleResourceOwner;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class GoogleApiAuthenticationTest
 *
 * Tests the Authentication of the Google API.
 *
 * @package HP\Bundle\EmailApiBundle\Tests\Security\Authentication
 */
class GoogleApiAuthenticationTest extends \PHPUnit_Framework_TestCase
{
    const EXPIRED_IN_SECONDS = -100;
    const NOT_EXPIRED_IN_SECONDS = 100;

    /**
     * @var GoogleApiAuthentication
     */
    private $googleApiAuthentication;

    /**
     * @var GoogleResourceOwner
     */
    private $googleResourceOwner;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var string
     */
    private $firewallName;

    public function setUp()
    {
        $this->firewallName = 'firewallNameTest';
        $this->googleResourceOwner =
            $this->getMockBuilder('HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GoogleResourceOwner')
                ->disableOriginalConstructor()
                ->getMock();
        $this->tokenStorage =
            $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface')
                ->getMock();

        $this->googleApiAuthentication = new GoogleApiAuthentication(
            $this->firewallName,
            $this->googleResourceOwner,
            $this->tokenStorage
        );
    }

    /**
     * Tests if the access token is refreshed.
     */
    public function testRefreshTokenNotExpired()
    {
        $oauthToken = $this->getOAuthToken(GoogleApiAuthenticationTest::NOT_EXPIRED_IN_SECONDS);
        $request = $this->getRequestWithSession(serialize($oauthToken));

        $tokenHasBeenUpdated = $this->googleApiAuthentication->refreshToken($request);

        $this->assertFalse($tokenHasBeenUpdated);
    }

    /**
     * Test when the access token is expired
     */
    public function testRefreshTokenExpired()
    {
        $oauthToken = $this->getOAuthToken(GoogleApiAuthenticationTest::EXPIRED_IN_SECONDS);
        $request = $this->getRequestWithSession(serialize($oauthToken));
        $this->mockTokenStorage($oauthToken);
        $this->mockGoogleResourceOwner();

        $tokenHasBeenUpdated = $this->googleApiAuthentication->refreshToken($request);

        $this->assertTrue($tokenHasBeenUpdated);
    }

    /**
     * Test when the access token is expired, but there is no token available in the
     * token storage to be updated.
     */
    public function testRefreshTokenExpiredWithoutTokenInStorage()
    {
        $oauthToken = $this->getOAuthToken(GoogleApiAuthenticationTest::EXPIRED_IN_SECONDS);
        $request = $this->getRequestWithSession(serialize($oauthToken));
        $this->mockTokenStorage(null);
        $this->mockGoogleResourceOwner();

        $tokenHasBeenUpdated = $this->googleApiAuthentication->refreshToken($request);

        $this->assertFalse($tokenHasBeenUpdated);
    }

    /**
     * When the user is not authenticated, the token does not exist in session.
     * Test when trying to serialize an object that does not exist in session.
     */
    public function testSessionWithoutOAuthToken()
    {
        $request = $this->getRequestEmptySession();

        $tokenHasBeenUpdated = $this->googleApiAuthentication->refreshToken($request);

        $this->assertEquals(false, $tokenHasBeenUpdated);
    }

    private function getOAuthToken($expiresInSeconds)
    {
        $today = new \DateTime();
        $oauthToken = new OAuthToken('testAccessToken');
        $oauthToken->setCreatedAt($today->getTimestamp());
        $oauthToken->setExpiresIn($expiresInSeconds);

        return $oauthToken;
    }

    private function mockTokenStorage($oauthToken)
    {
        $this->tokenStorage->expects($this->once())->method('getToken')->will($this->returnValue($oauthToken));
    }

    private function mockGoogleResourceOwner()
    {
        $response = ['access_token' => 'testAccessToken'];
        $this->googleResourceOwner->expects($this->once())->method('refreshAccessToken')->will($this->returnValue($response));
    }

    private function getSessionMock($oauthTokenSerialized)
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')->getMock();
        $session->expects($this->once())
            ->method('get')
            ->with('_security_'.$this->firewallName)
            ->will($this->returnValue($oauthTokenSerialized));

        return $session;
    }

    private function getRequestWithSession($oauthSerialized)
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->once())->method('getSession')->will($this->returnValue($this->getSessionMock($oauthSerialized)));

        return $request;
    }

    private function getRequestEmptySession()
    {
        return $this->getRequestWithSession(null);
    }
}
