<?php

namespace App\Controller;

namespace App\Controller;

use App\Service\DateService;
use App\Service\YandexGeocoder;
use App\Service\YandexWeather;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WeatherController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    public function forecast(Request $request, YandexGeocoder $geocoder, YandexWeather $weather, DateService $dateService): Response
    {
        $city = $request->request->get('city');
        $forecastType = $request->request->get('forecast_type');
        $days = $request->request->get('days');

        $coordinates = $geocoder->getCoordinates($city);

        if (empty($coordinates)) {
            return $this->render('error.html.twig', [
                'message' => 'Город не найден',
            ]);
        }

        $latitude = $coordinates['latitude'];
        $longitude = $coordinates['longitude'];


        if ($forecastType === 'hourly') {
            $weatherData = $weather->getHourlyWeatherData($latitude, $longitude);
            $today = $dateService->getTodayDate();
            return $this->render('forecast.html.twig', [
                'city' => $city,
                'weatherData' => $weatherData,
                'today' => $today
            ]);
        } elseif ($forecastType === 'daily' && $days >= 2 && $days <= 7) {
            $weatherData = $weather->getDailyWeatherData($latitude, $longitude, $days);
            $dates = $dateService->generateDateRange($days);
            return $this->render('daily.html.twig', [
                'city' => $city,
                'days' => $days,
                'weatherData' => $weatherData,
                'dates' => $dates
            ]);
        } else {
            return $this->render('error.html.twig', [
                'message' => 'Некорректный выбор прогноза или количество дней',
            ]);
        }

    }
}