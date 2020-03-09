<?php

declare(strict_types=1);

namespace App\Test\Api;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    use TestHelper;

    public ?Client $client;

    public static function setUpBeforeClass(): void
    {
        self::resetDbAndCreateUser();
    }

    protected function setUp(): void
    {
        $this->client = new Client(['base_uri' => 'http://app/api/']);
    }

    protected function tearDown(): void
    {
        $this->client = null;
    }

    public function testRequiredLogin(): void
    {
        $response = $this->client->request(
            'POST',
            'auth/login',
            ['http_errors' => false]
        );
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('{"success":false,"error":"login is required."}', $response->getBody()->getContents());
    }

    public function testLoginWrongCredentials(): void
    {
        $response = $this->client->request(
            'POST',
            'auth/login',
            [
                'http_errors' => false,
                'form_params' => [
                    'login' => 'abc',
                    'password' => '123',
                ]
            ]
        );
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('{"success":false,"error":"Invalid user or password."}', $response->getBody()->getContents());
    }

    public function testLoginSuccess(): void
    {
        $token = $this->loginAndGetToken();
        $this->assertIsString($token);
    }
}
