<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventoController extends Controller
{
    public function owned(Request $request)
    {
        $events = Evento::where('owner_id', auth()->id())
            ->orderBy('created_at', 'desc')->limit(20)->get();

        return Inertia::render('Events/Owned', [
            'events' => $events,
        ]);
    }

    public function subscribed(Request $request)
    {
        $events = Evento::whereHas('participants', function ($query) {
            $query->where('user_id', auth()->id());
        })->orderBy('created_at', 'desc')->limit(20)->get();

        return Inertia::render('Events/Subscribed', [
            'events' => $events,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Evento::limit(10)->orderBy('created_at', 'desc')->get();
        return Inertia::render('Events/Explore', [
            'events' => $events,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Evento $evento)
    {
        return Inertia::render('Events/Show', [
            'event' => $evento->load('owner'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evento $evento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evento $evento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evento $evento)
    {
        //
    }


}
