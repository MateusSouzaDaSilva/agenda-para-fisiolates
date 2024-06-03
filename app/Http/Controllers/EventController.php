<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventName;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('eventName')->get();
        $eventsName = EventName::all();
        $days = ['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira'];
        $hours = range(8, 20);
        return view('agenda.index', compact('events', 'days', 'hours', 'eventsName'));
    }

    public function store(Request $request)
{
    // Verifica se já existe um evento com o mesmo event_name_id em qualquer dia e horário
    $existingEvent = Event::where('event_name_id', $request->eventName)->exists();

    // Se já existir, retorne um redirecionamento com um erro
    if ($existingEvent) {
        return redirect('/agenda')->with('error', 'Já existe um evento com o mesmo nome em outro dia e/ou horário.');
    }

    // Verifica se já existem 7 eventos nesta célula de tempo
    $existingEventsInCell = Event::where('day', $request->day)
                                    ->where('time', $request->time)
                                    ->count();

    // Se já houver 7 eventos, retorne um redirecionamento com um erro
    if ($existingEventsInCell >= 7) {
        return redirect('/agenda')->with('error', 'Não é permitido mais de 7 eventos por célula.');
    }

    // Se passar pelas verificações, crie o novo evento
    $event = new Event;
    $event->event_name_id = $request->eventName;
    $event->day = $request->day; // Captura o dia selecionado no formulário
    $event->time = $request->time; // Captura o horário selecionado no formulário
    $event->save();

    // Redirecione de volta para a página de agenda com uma mensagem de sucesso
    return redirect('/agenda')->with('success', 'Evento adicionado com sucesso!');
}

    
    

    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return redirect()->back()->with('error', 'Evento não encontrado!');
        }

        $event->day = $request->input('day');
        $event->time = $request->input('time');
        $event->save();

        return redirect()->back()->with('success', 'Agendamento atualizado!');
    }

    public function destroy($id) 
    {
        Event::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Agendamento excluído!');
    }
}
