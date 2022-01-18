<?php

namespace Francerz\OAuth2\Tests;

use Francerz\Http\Request;
use Francerz\Http\Response;
use Francerz\Http\Uri;
use Francerz\Http\Utils\UriHelper;
use Francerz\OAuth2\AuthorizeErrorEnum;
use Francerz\OAuth2\Error;
use Francerz\OAuth2\ErrorCodesEnum;
use Francerz\OAuth2\OAuth2Error;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{
    public function testBasicError()
    {
        $error = new OAuth2Error(
            AuthorizeErrorEnum::INVALID_REQUEST,
            'The given request was malformed.',
            'http://example.com/docs/oauth2/invalid_request'
        );

        $this->assertEquals(AuthorizeErrorEnum::INVALID_REQUEST, $error->getError());
        $this->assertEquals('The given request was malformed.', $error->getErrorDescription());
        $this->assertEquals('http://example.com/docs/oauth2/invalid_request', $error->getErrorUri());
    }

    public function testParsingFromUri()
    {
        $uri = new Uri('http://example.com/oauth2/callback');
        $uri = UriHelper::withQueryParams($uri, array(
            'state' => '6yhZkSejLW3r',
            'error' => 'invalid_request',
            'error_description' => 'The given request was malformed.',
            'error_uri' => 'http://example.com/docs/oauth2/invalid_request'
        ));

        $error = OAuth2Error::fromUri($uri);

        $this->assertEquals(AuthorizeErrorEnum::INVALID_REQUEST, $error->getError());
        $this->assertEquals('The given request was malformed.', $error->getErrorDescription());
        $this->assertEquals('http://example.com/docs/oauth2/invalid_request', $error->getErrorUri());
    }

    public function testParsingFromRequest()
    {
        $expected = new OAuth2Error(
            AuthorizeErrorEnum::ACCESS_DENIED,
            'Access Denied.',
            'http://example.com/docs/oauth2/access_denied'
        );
        $request = new Request(new Uri('http://example.com/oauth2/callback'));
        $request = $request->withHeader('Content-Type', 'application/json; charset=utf-8');
        $request->getBody()->write(json_encode($expected));

        $actual = OAuth2Error::fromRequest($request);

        $this->assertEquals($expected, $actual);
    }

    public function testParsingFromResponse()
    {
        $expected = new OAuth2Error(
            AuthorizeErrorEnum::ACCESS_DENIED,
            'Access Denied.',
            'http://example.com/docs/oauth2/access_denied'
        );
        $response = new Response();
        $response = $response->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write(json_encode($expected));

        $actual = OAuth2Error::fromResponse($response);

        $this->assertEquals($expected, $actual);
    }
}
