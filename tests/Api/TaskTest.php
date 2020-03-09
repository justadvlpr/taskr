<?php

declare(strict_types=1);

namespace App\Test\Api;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    use TestHelper;

    public ?Client $client;
    public static string $token = '';

    public static function setUpBeforeClass(): void
    {
        self::resetDbAndCreateUser();
        static::$token = self::loginAndGetToken();
    }

    protected function setUp(): void
    {
        $this->client = new Client(['base_uri' => 'http://app/api/']);
    }

    protected function tearDown(): void
    {
        $this->client = null;
    }

    public function testCreateTaskRequired(): void
    {
        $response = $this->client->request(
            'POST',
            'task',
            [
                'http_errors' => false,
                'form_params' => [
                    'task' => '',
                    'date' => '',
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . static::$token,
                ]
            ]
        );
        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testCreateAndUpdateTaskWithSuccess(): void
    {
        $response = $this->client->request(
            'POST',
            'task',
            [
                'http_errors' => false,
                'form_params' => [
                    'task' => 'task 1',
                    'date' => date('Y-m-d'),
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . static::$token,
                ]
            ]
        );
        $this->assertEquals(200, $response->getStatusCode());

        $decodedData = json_decode($response->getBody()->getContents(), true);
        $this->assertIsArray($decodedData);

        $this->assertArrayHasKey('task', $decodedData);

        $taskId = $decodedData['task']['id'];

        $response = $this->client->request(
            'PUT',
            'task/' . $taskId,
            [
                'body' => json_encode(['task' => 'test 2', ]),
                'http_errors' => false,
                'headers' => [
                    'Authorization' => 'Bearer ' . static::$token,
                ]
            ]
        );
        $this->assertEquals(200, $response->getStatusCode());
    }
}
