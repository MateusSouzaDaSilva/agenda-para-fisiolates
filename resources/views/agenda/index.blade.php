<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Semanal</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #d0d0d0;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #c7a6db;
            font-weight: bold;
        }

        .event {
            background-color: #f5f5f5;
            padding: 5px;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn {
            margin-right: 5px;
        }

        .modal-dialog {
            max-width: 80%;
        }

        .modal-content {
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Agenda Semanal</h1>
        <!-- Mensagens de erro e sucesso -->
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    @foreach ($days as $day)
                    <th>{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($hours as $hour)
                <tr>
                    <td>{{ sprintf('%02d:00', $hour) }}</td>
                    @foreach ($days as $day)
                    <td>
                        @foreach ($events as $event)
                        @if ($event->day == $day && $event->time == sprintf('%02d:00:00', $hour))
                        <div class="event" data-event-id="{{ $event->id }}">
                            {{ $event->eventName->name }}
                        </div>
                        @endif
                        @endforeach
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createEventModal">
            Adicionar Evento
        </button>
        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editEventModal">
            Editar Evento
        </button>

        <!-- Modal de Criação -->
        <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createEventModalLabel">Adicionar Evento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/agenda/salvar" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="day">Dia:</label>
                                <select name="day" id="day" class="form-control" required>
                                    <option value="Segunda-feira">Segunda-feira</option>
                                    <option value="Terça-feira">Terça-feira</option>
                                    <option value="Quarta-feira">Quarta-feira</option>
                                    <option value="Quinta-feira">Quinta-feira</option>
                                    <option value="Sexta-feira">Sexta-feira</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="time">Hora:</label>
                                <select name="time" id="time" class="form-control" required>
                                    @foreach (range(8, 20) as $hour)
                                    @if (!in_array($hour, [11, 12, 13]))
                                    <option value="{{ sprintf('%02d:00:00', $hour) }}">{{ sprintf('%02d:00', $hour) }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="eventName">Nome do Evento:</label>
                                <select name="eventName" id="eventName" class="form-control" required>
                                    <option value="" disabled selected>Selecione o evento</option>
                                    @foreach ($eventsName as $eventName)
                                    <option value="{{ $eventName->id }}">{{ $eventName->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Adicionar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEventModalLabel">Editar Evento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editEventForm" action="/agenda/update" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="selectedEvent">Selecionar Evento:</label>
                                <select name="selectedEvent" id="selectedEvent" class="form-control" required>
                                    <option value="" disabled selected>Selecione um evento</option>
                                    @foreach ($events as $event)
                                    <option value="{{ $event->id }}">
                                        {{ $event->eventName->name }} - {{ $event->day }} - {{ $event->time }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="day">Dia:</label>
                                <select name="day" id="day" class="form-control" required>
                                    <option value="Segunda-feira">Segunda-feira</option>
                                    <option value="Terça-feira">Terça-feira</option>
                                    <option value="Quarta-feira">Quarta-feira</option>
                                    <option value="Quinta-feira">Quinta-feira</option>
                                    <option value="Sexta-feira">Sexta-feira</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="time">Hora:</label>
                                <select name="time" id="time" class="form-control" required>
                                    @foreach (range(8, 20) as $hour)
                                    @if (!in_array($hour, [11, 12, 13]))
                                    <option value="{{ sprintf('%02d:00:00', $hour) }}">{{ sprintf('%02d:00', $hour) }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>
                        <form action="/agenda/excluir/{{ $event->id }}" method="post" onsubmit="return confirmarExclusao()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" value="E"><i class="bi bi-trash3-fill"></i>Excluir</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery e Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#selectedEvent').change(function() {
                var eventId = $(this).val();
                $('#editEventForm').attr('action', '/agenda/' + eventId);
            });
        });


        function confirmarExclusao() {
            return confirm('Tem certeza que deseja excluir esta avaliação?');
        }
    </script>
</body>

</html>