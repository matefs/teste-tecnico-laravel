@extends('layouts.app')

@section('content')

    <div class="container mt-5">
        <h1>Histórico de Previsões</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Cidade</th>
                    <th>Temperatura</th>
                    <th>Descrição</th>
                    <th>Vento</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $history)
                <tr>
                    <td>{{ $history->city }}</td>
                    <td>{{ $history->temperature }}°C</td>
                    <td>{{ $history->description }}</td>
                    <td>{{ $history->wind_speed }} km/h</td>
                    <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
