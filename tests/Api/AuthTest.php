<?php

namespace App\Test\Api;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    public $client;

    protected function setUp(): void
    {
        $path = dirname(__DIR__, 2);
        @unlink("{$path}/runtime/database.db");
        exec("{$path}/vendor/bin/yii user/create user password");
        $this->client = new Client(['base_uri' => 'http://app/api/']);
    }

    protected function tearDown(): void
    {
        $this->client = null;
    }

    public function testRequiredLogin()
    {
        $response = $this->client->request(
            'POST',
            'auth/login',
            ['http_errors' => false]
        );
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('{"success":false,"error":"login is required."}', $response->getBody()->getContents());
    }

    public function testLoginWrongCredentials()
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

    public function testLoginSuccess()
    {
        $response = $this->client->request(
            'POST',
            'auth/login',
            [
                'http_errors' => false,
                'form_params' => [
                    'login' => 'user',
                    'password' => 'password',
                ]
            ]
        );
        $this->assertEquals(200, $response->getStatusCode());
        $decodedData = json_decode($response->getBody()->getContents(), true);
        $this->assertIsArray($decodedData);
        $this->assertArrayHasKey('user', $decodedData);
    }
}
