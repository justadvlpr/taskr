<?php

declare(strict_types=1);

namespace App\Test\Api;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public static Client $client;
    public static string $token = '';

    public static function setUpBeforeClass(): void
    {
        $path = dirname(__DIR__, 2);
        @unlink("{$path}/runtime/database.db");
        exec("{$path}/vendor/bin/yii user/create user password");

        static::$client = new Client(['base_uri' => 'http://app/api/']);

        $response = static::$client->request(
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
        self::assertEquals(200, $response->getStatusCode());
        $decodedData = json_decode($response->getBody()->getContents(), true);
        self::assertIsArray($decodedData);
        self::assertArrayHasKey('user', $decodedData);
        static::$token = $decodedData['user']['token'];
    }

    public function testCreateTaskRequired()
    {
        $response = static::$client->request('POST', 'task',
            [
                'http_errors' => false,
                'form_params' => [
                    'task' => '',
                    'date' => '',
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . static::$token,
                    'Accept' => 'application/json',
                ]
            ]
        );
        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testCreateTaskSuccess()
    {
        $response = static::$client->request('POST', 'task',
            [
                'http_errors' => false,
                'form_params' => [
                    'task' => 'task 1',
                    'date' => date('Y-m-d'),
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . static::$token,
                    'Accept' => 'application/json',
                ]
            ]
        );
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUpdateTask()
    {
        $response = static::$client->request(
            'PUT',
            'task/1',
            [
                'body' => json_encode(['task' => 'test 2',]),
                'http_errors' => false,
                'headers' => [
                    'Authorization' => 'Bearer ' . static::$token,
                    'Accept' => 'application/json',
                ]
            ]
        );
        $this->assertEquals(200, $response->getStatusCode());
    }
}
