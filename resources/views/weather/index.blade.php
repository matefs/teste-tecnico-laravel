<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .spinner-border {
            width: 1.5rem;
            height: 1.5rem;
            display: none;
        }
    </style>
</head>
<body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ url('/weather/') }}">Clima app</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/weather/history') }}">Histórico</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/weather/compare') }}">Comparar Previsões</a>
                </li>
            </ul>
        </div>
    </nav>

<div class="container mt-5">
    <h1 class="mb-4">Previsão do Tempo</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('weather.search') }}">
        @csrf
        <div class="mb-3">
            <label for="cep" class="form-label">CEP</label>
            <div class="d-flex align-items-center">
                <input type="number" class="form-control me-2" id="cep" name="cep" placeholder="Digite o CEP">
                <div class="spinner-border text-primary" role="status" id="loading-spinner">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">Cidade</label>
            <input type="text" class="form-control" id="city" name="city" placeholder="Digite o nome da cidade">
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    @isset($weatherData)
    <form method="POST" action="{{ route('weather.save') }}">
        @csrf
        <input type="hidden" name="city" value="{{ $city }}">
        <input type="hidden" name="temperature" value="{{ $weatherData['current']['temperature'] }}">
        <input type="hidden" name="description" value="{{ $weatherData['current']['weather_descriptions'][0] }}">
        <input type="hidden" name="wind_speed" value="{{ $weatherData['current']['wind_speed'] }}">
        <button type="submit" class="btn btn-success mt-3">Salvar Previsão</button>
    </form>

        <div class="mt-4">
            <h2>Previsão para {{ $city }}</h2>
            <p><strong>Temperatura:</strong> {{ $weatherData['current']['temperature'] }}°C</p>
            <p><strong>Descrição:</strong> {{ $weatherData['current']['weather_descriptions'][0] }}</p>
            <p><strong>Vento:</strong> {{ $weatherData['current']['wind_speed'] }} km/h</p>
        </div>
    @endisset
</div>

<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">Atenção</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="alertModalBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#cep').on('blur', function () {
            let cep = $(this).val().replace(/\D/g, '');
            if (cep) {
                $('#loading-spinner').show(); 
                $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function (data) {
                    $('#loading-spinner').hide();
                    if (!("erro" in data)) {
                        $('#city').val(data.localidade);
                    } else {
                        showModal('CEP não encontrado.');
                        $('#city').val('');
                    }
                }).fail(function () {
                    $('#loading-spinner').hide(); 
                    showModal('Erro ao consultar o CEP.');
                });
            }
        });

        function showModal(message) {
            $('#alertModalBody').text(message);
            let alertModal = new bootstrap.Modal($('#alertModal'));
            alertModal.show();
        }
    });
</script>
</body>
</html>
