<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use function PHPUnit\Framework\throwException;

class YandexWeather
{
    private String $apiKey;
    private Client $client;

    public function __construct()
    {
        $this->apiKey =  '7c114f68-d5be-4677-8ba5-7573bcb64a77';
        $this->client = new Client(['verify' => false]);
    }

    public function getHourlyWeatherData(float $latitude, float $longitude): array
    {
        try {
            $response = $this->client->get("https://api.weather.yandex.ru/v2/forecast?lat={$latitude}&lon={$longitude}&hours=true", [
                'headers' => [
                    'X-Yandex-API-Key' => $this->apiKey,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throwException($e);
        }

        return [];
    }

    public function getDailyWeatherData(float $latitude, float $longitude, int $days = 1): array
    {
        try {
            $response = $this->client->get("https://api.weather.yandex.ru/v2/forecast?lat={$latitude}&lon={$longitude}&limit={$days}", [
                'headers' => [
                    'X-Yandex-API-Key' => $this->apiKey,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throwException($e);
        }

        return [];
    }

}