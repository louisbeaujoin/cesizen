<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:150'],
            'subject'  => ['required', 'string', 'max:200'],
            'category' => ['required', 'in:bug,suggestion,question,autre'],
            'message'  => ['required', 'string', 'min:20', 'max:2000'],
        ]);

        if (auth()->check()) {
            $data['user_id'] = auth()->id();
            $data['name']    = $data['name'] ?: auth()->user()->name;
            $data['email']   = $data['email'] ?: auth()->user()->email;
        }

        Ticket::create($data);

        return redirect()->route('tickets.confirmation');
    }

    public function confirmation()
    {
        return view('tickets.confirmation');
    }
}
