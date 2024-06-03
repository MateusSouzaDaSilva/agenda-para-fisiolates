<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Evento</title>
</head>
<body>
    <h1>Adicionar Evento</h1>
    <form action="/agenda" method="POST">
        @csrf
        <label for="day">Dia:</label>
        <select name="day" id="day" required>
            <option value="Segunda-feira">Segunda-feira</option>
            <option value="Terça-feira">Terça-feira</option>
            <option value="Quarta-feira">Quarta-feira</option>
            <option value="Quinta-feira">Quinta-feira</option>
            <option value="Sexta-feira">Sexta-feira</option>
        </select><br>

        <label for="time">Hora:</label>
        <select name="time" id="time" required>
            @foreach (range(7, 20) as $hour)
                @if (!in_array($hour, [11, 12, 13]))
                    <option value="{{ sprintf('%02d:00:00', $hour) }}">{{ sprintf('%02d:00', $hour) }}</option>
                @endif
            @endforeach
        </select><br>

        <label for="description">Descrição:</label>
        <input type="text" name="description" id="description"><br>

        <button type="submit">Adicionar</button>
    </form>
</body>
</html>
