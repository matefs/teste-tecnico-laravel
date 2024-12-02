@extends('layouts.app')

@section('content')
<div class="container mt-5">

    <h1 class="text-center">Comparar Previsões do Tempo</h1>
    <form method="GET" action="{{ route('weather.compare') }}" id="compare-form">
        <div class="row mb-4 justify-content-center">
            <div class="col-md-4">
                <input type="text" name="city1" class="form-control" placeholder="Cidade 1" value="{{ old('city1') }}">
            </div>
            <div class="col-md-4">
                <input type="text" name="city2" class="form-control" placeholder="Cidade 2" value="{{ old('city2') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Comparar</button>
            </div>
        </div>
    </form>

    @if ($weather1 || $weather2)
    <div class="comparison-section mt-5">
        <div class="row">
            <div class="col-md-6 text-center">
                <h3 class="mb-3">{{ $city1 ?? 'Cidade não informada' }}</h3>
                @if ($weather1)
                    <p class="display-6">{{ $weather1['temperature'] ?? 'N/A' }}°C</p>
                    <p>{{ $weather1['weather_descriptions'][0] ?? 'N/A' }}</p>
                    <p>Vento: {{ $weather1['wind_speed'] ?? 'N/A' }} km/h</p>
                @else
                    <p>Previsão não disponível</p>
                @endif
            </div>
            <div class="col-md-6 text-center">
                <h3 class="mb-3">{{ $city2 ?? 'Cidade não informada' }}</h3>
                @if ($weather2)
                    <p class="display-6">{{ $weather2['temperature'] ?? 'N/A' }}°C</p>
                    <p>{{ $weather2['weather_descriptions'][0] ?? 'N/A' }}</p>
                    <p>Vento: {{ $weather2['wind_speed'] ?? 'N/A' }} km/h</p>
                @else
                    <p>Previsão não disponível</p>
                @endif
            </div>
        </div>

        @if ($weather1 && $weather2)
        <div class="mt-5">
            <h4 class="text-center">Comparação Visual</h4>
            <div class="progress mb-3">
                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $weather1['temperature'] ?? 0 }}%;" aria-valuenow="{{ $weather1['temperature'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                    {{ $city1 }}: {{ $weather1['temperature'] ?? 'N/A' }}°C
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $weather2['temperature'] ?? 0 }}%;" aria-valuenow="{{ $weather2['temperature'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                    {{ $city2 }}: {{ $weather2['temperature'] ?? 'N/A' }}°C
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

</div>

<script>
    $(document).ready(function () {
        $('#compare-form').on('submit', function (e) {
            e.preventDefault();
            // Exibir um spinner ou mensagem de carregamento
            $('body').append('<div class="loading-overlay"><div class="spinner-border text-primary"></div></div>');
            this.submit();
        });
    });
</script>
@endsection
