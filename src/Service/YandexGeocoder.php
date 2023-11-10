<?php

namespace App\Service;

use Doctrine\DBAL\Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class YandexGeocoder
{
    private String $apiKey;
    private Client $client;

    public function __construct()
    {
        $this->apiKey = '4d9a3606-dd51-48fb-b971-6be166713ad4';
        $this->client = new Client(['verify' => false]);
    }

    public function getCoordinates(string $city): array
    {
        try {
            $response = $this->client->get("https://geocode-maps.yandex.ru/1.x/?apikey={$this->apiKey}&geocode={$city}&format=json");
            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'])) {
                $coordinates = explode(' ', $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos']);

                $latitude = $coordinates[1];
                $longitude = $coordinates[0];

                return [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ];
            }
        } catch (GuzzleException $e) {
            throw new Exception($e);
        }
        return [];
    }
}