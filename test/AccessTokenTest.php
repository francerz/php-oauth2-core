<?php

namespace Francerz\OAuth2\Tests;

use Francerz\OAuth2\AccessToken;
use PHPUnit\Framework\TestCase;

class AccessTokenTest extends TestCase
{
    public function testInstantiation()
    {
        $at = new AccessToken('abcdefgh', 'Bearer', 3600, 'ijklmnop', '', 10000);
        $at = $at->withParameter('scope', 'scp1 scp2');

        $this->assertEquals('abcdefgh', $at->getAccessToken());
        $this->assertEquals('Bearer', $at->getTokenType());
        $this->assertEquals(3600, $at->getExpiresIn());
        $this->assertEquals('ijklmnop', $at->getRefreshToken());
        $this->assertEquals(10000, $at->getCreateTime()->format('U'));
        $this->assertEquals('scp1 scp2', $at->getParameter('scope'));

        return $at;
    }

    /**
     * @depends testInstantiation
     */
    public function testJsonEncoding(AccessToken $at)
    {
        $expected = array(
            'access_token' => 'abcdefgh',
            'token_type' => 'Bearer',
            'expires_in' => 3600,
            'refresh_token' => 'ijklmnop',
            'scope' => 'scp1 scp2',
            'issued_at' => '1970-01-01T02:46:40.000+00:00'
        );

        $this->assertEquals(json_encode($expected), json_encode($at));
    }
}
