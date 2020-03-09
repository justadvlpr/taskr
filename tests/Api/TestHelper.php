<?php

declare(strict_types=1);

namespace App\Test\Api;

use GuzzleHttp\Client;

trait TestHelper
{
    protected static function resetDbAndCreateUser(): void
    {
        $path = dirname(__DIR__, 2);
        @unlink("{$path}/runtime/database.db");
        exec("{$path}/vendor/bin/yii user/create user password");
    }

    protected static function loginAndGetToken()
    {
        $client = new Client(['base_uri' => 'http://app/api/']);
        $response = $client->request(
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

        return $decodedData['user']['token'];
    }
}
