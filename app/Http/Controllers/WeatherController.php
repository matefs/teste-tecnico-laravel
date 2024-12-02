<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index()
    {
        return view('weather.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'cep' => 'required',
            'city' => 'required',
        ]);

        $cep = $request->input('cep');
        $viaCepResponse = Http::get("https://viacep.com.br/ws/{$cep}/json/");
        $cityFromCep = $viaCepResponse->json('localidade');

        $city = $request->input('city') ?: $cityFromCep;
        $apiKey = config('services.weather_api.key');
        $apiUrl = config('services.weather_api.url');

        $weatherResponse = Http::get("{$apiUrl}/current", [
            'access_key' => $apiKey,
            'query' => $city,
        ]);

        if ($weatherResponse->failed()) {
            return back()->withErrors(['error' => 'Failed to fetch weather data.']);
        }

        $weatherData = $weatherResponse->json();

        return view('weather.index', [
            'weatherData' => $weatherData,
            'city' => $city,
            'viaCepCity' => $cityFromCep,
        ]);
    }
}
