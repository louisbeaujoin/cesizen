<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $tickets = Ticket::query()
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);

        return view('admin.tickets.index', compact('tickets', 'status'));
    }

    public function show(Ticket $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => ['required', 'in:ouvert,en_cours,resolu,ferme'],
        ]);

        $ticket->update(['status' => $request->status]);

        return back()->with('success', 'Statut du ticket mis à jour.');
    }
}
