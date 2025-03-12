<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventoController extends Controller
{
    public function handleSubscribe(Request $request)
    {
        try {
            $evento = Evento::findOrFail($request->evento_id);

            // Check if user is already subscribed
            if ($evento->participants()->where('user_id', auth()->id())->exists()) {
                return response()->json([
                    'message' => 'Ya estás suscrito a este evento'
                ], 422);
            }

            $evento->participants()->attach(auth()->id());

            return response()->json([
                'message' => '¡Te has suscrito al evento!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al suscribirse al evento'
            ], 500);
        }
    }
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
    public function show(Request $request)
    {
        $evento = Evento::findOrFail($request->id)
            ->load([
                'owner:id,name',
                'participants:id,name'
            ]);
        return Inertia::render('Events/Show', [
            'event' => $evento,
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
