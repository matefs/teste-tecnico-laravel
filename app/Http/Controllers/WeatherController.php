<?php

namespace App\Http\Controllers;

use App\Models\WeatherHistory;
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
            return back()->withErrors(['error' => 'Falha ao buscar os dados de previsão do tempo.']);
        }

        $weatherData = $weatherResponse->json();

        return view('weather.index', [
            'weatherData' => $weatherData,
            'city' => $city,
            'viaCepCity' => $cityFromCep,
        ]);
    }

    public function save(Request $request)
    {
        WeatherHistory::create([
            'city' => $request->input('city'),
            'temperature' => $request->input('temperature'),
            'description' => $request->input('description'),
            'wind_speed' => $request->input('wind_speed'),
        ]);

        return redirect()->route('weather.index')->with('success', 'Previsão salva com sucesso!');
    }

    public function history()
    {
        $histories = WeatherHistory::all();

        return view('weather.history', compact('histories'));
    }


    public function compare(Request $request)
{
    $city1 = $request->input('city1');
    $city2 = $request->input('city2');

    $weather1 = null;
    $weather2 = null;

    if ($city1) {
        $weather1Response = Http::get(config('services.weather_api.url') . '/current', [
            'access_key' => config('services.weather_api.key'),
            'query' => $city1,
        ]);
        if ($weather1Response->successful()) {
            $weather1 = $weather1Response->json()['current'] ?? null; // Apenas 'current'
        }
    }

    if ($city2) {
        $weather2Response = Http::get(config('services.weather_api.url') . '/current', [
            'access_key' => config('services.weather_api.key'),
            'query' => $city2,
        ]);
        if ($weather2Response->successful()) {
            $weather2 = $weather2Response->json()['current'] ?? null; // Apenas 'current'
        }
    }

    return view('weather.compare', [
        'weather1' => $weather1,
        'weather2' => $weather2,
        'city1' => $city1,
        'city2' => $city2,
    ]);
}

    
}
