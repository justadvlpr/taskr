<?php

namespace App\Test\Api;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    public $client;

    protected function setUp(): void
    {
        $this->client = new Client(['base_uri' => 'http://app/']);
    }

    protected function tearDown(): void
    {
        $this->client = null;
    }

    public function testRequiredLogin()
    {
        $response = $this->client->request(
            'POST',
            '/api/auth/login',
            ['http_errors' => false]
        );

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('{"success":false,"error":"login is required."}', $response->getBody()->getContents());
    }
}
