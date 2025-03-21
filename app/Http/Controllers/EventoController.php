<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventoController extends Controller
{
    private const TIER_RANKS = [
        'STANDARD' => 0,
        'BRONZE' => 1,
        'SILVER' => 2,
        'GOLD' => 3,
        'PLATINUM' => 4
    ];

    public function handleSubscribe(Request $request)
    {
        try {
            $evento = Evento::findOrFail($request->evento_id);

            // Check if user is already subscribed
            if ($evento->participants()->where('user_id', auth()->id())->exists()) {
                return response()->json(['message' => 'Ya estás suscrito a este evento'], 422);
            }

            $evento->participants()->attach(auth()->id());

            return response()->json(['message' => '¡Te has suscrito al evento!']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error al suscribirse al evento'], 500);
        }
    }

    public function handleUnsubscribe(Request $request)
    {
        try {
            $evento = Evento::findOrFail($request->evento_id);

            // Check if user is subscribed
            if (!$evento->participants()->where('user_id', auth()->id())->exists()) {
                return response()->json(['message' => 'No estás suscrito a este evento'], 422);
            }

            $evento->participants()->detach(auth()->id());

            return response()->json(['message' => 'Te has dado de baja del evento']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error al dar de baja del evento'], 500);
        }
    }

    public function index(Request $request)
    {
        $query = Evento::query()->with('tier');
        $query = $this->applyFilters($query, $request);
        $events = $query->paginate(15);
        return Inertia::render('Events/Explore', [
            'events' => $events,
            'filters' => $this->getFiltersArray($request)
        ]);
    }

    private function applyFilters($query, Request $request)
    {
        // Filter by age group
        if ($request->has('age_group') && $request->age_group) {
            $query->where('age_group', $request->age_group);
        }

        // Filter by price range
        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by time range
        if ($request->has('start_date') && $request->start_date) {
            $query->where('start', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('start', '<=', $request->end_date);
        }

        // Filter by minimum tier
        if ($request->has('min_tier') && $request->min_tier) {
            $minTierRank = self::TIER_RANKS[$request->min_tier];
            $query->whereHas('tier', function($query) use ($minTierRank) {
                $query->whereIn('tier', array_filter(
                    array_keys(self::TIER_RANKS),
                    fn($tier) => self::TIER_RANKS[$tier] >= $minTierRank
                ));
            });
        }

        // Apply sorting
        $query->orderBy($request->input('sort_by', 'created_at'), $request->input('sort_direction', 'desc'));
        // Show only active events
        $query->where('status', 'ACTIVE');
        return $query;
    }

    private function getFiltersArray(Request $request): array
    {
        return [
            'age_group' => $request->age_group ?: null,
            'min_price' => is_numeric($request->min_price) ? (float)$request->min_price : null,
            'max_price' => is_numeric($request->max_price) ? (float)$request->max_price : null,
            'start_date' => $request->start_date ?: null,
            'end_date' => $request->end_date ?: null,
            'sort_by' => $request->input('sort_by', 'created_at'),
            'sort_direction' => in_array($request->input('sort_direction'), ['asc', 'desc'])
                ? $request->input('sort_direction')
                : 'desc',
            'min_tier' => $request->min_tier ?: null,
        ];
    }

    public function owned(Request $request)
    {
        $query = Evento::where('owner_id', auth()->id())->with('tier');
        $query = $this->applyFilters($query, $request);
        $events = $query->get();

        return Inertia::render('Events/Owned', ['events' => $events, 'filters' => $this->getFiltersArray($request)]);
    }

    public function subscribed(Request $request)
    {
        $query = Evento::whereHas('participants', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('tier');
        $query = $this->applyFilters($query, $request);
        $events = $query->paginate(15);

        return Inertia::render('Events/Subscribed', ['events' => $events, 'filters' => $this->getFiltersArray($request)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Events/Create');
    }

    /**
     * Store a newly created resource in storage and assign a Standard tier.
     */
    public function store(Request $request)
    {
        \Log::info('Datos recibidos:', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start' => 'required|date',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'location_name' => 'required|string|max:255',
            'location_address' => 'required|string',
            'location_url' => 'nullable|url',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'age_group' => 'required|string',
            'color' => 'required|string',
        ]);

        // Manejar la carga de la imagen
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('event-images', 'public');
        }

        $evento = Evento::create([
            'name' => $request->name,
            'description' => $request->description,
            'start' => $request->start,
            'price' => intval($request->input('price', 0)),
            'capacity' => intval($request->input('capacity', 1)),
            'location_name' => $request->location_name,
            'location_address' => $request->location_address,
            'location_url' => $request->location_url,
            'image' => $imagePaths['medium'] ? '/storage/' . $imagePaths['medium'] : null,
            'image_thumbnail' => $imagePaths['thumbnail'] ? '/storage/' . $imagePaths['thumbnail'] : null,
            'image_large' => $imagePaths['large'] ? '/storage/' . $imagePaths['large'] : null,
            'image_webp' => $imagePaths['medium_webp'] ? '/storage/' . $imagePaths['medium_webp'] : null,
            'age_group' => $request->age_group,
            'color' => $request->color,
            'owner_id' => auth()->id(),
        ]);

        $evento->tier()->create([
            'tier' => 'STANDARD',
        ]);

        return redirect()->route('events.show', $evento->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $evento = Evento::findOrFail($request->id)->load(['owner:id,name', 'participants:id,name']);
        $participantsCount = $evento->participants->count();
        $tier = $evento->tier;
        return Inertia::render(
            'Events/Show',
            [
                'event' => $evento,
                'participantsCount' => $participantsCount,
                'isSubscribed' => $evento->participants()->where('user_id', auth()->id())->exists(),
                'tier' => $tier
            ]
        );
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

    public function handleCancel(Request $request)
    {
        try {
            $evento = Evento::findOrFail($request->evento_id);
            
            // Verificar si el usuario es el dueño del evento
            if ($evento->owner_id === auth()->id()) {
                // Si es el dueño, cancelar el evento completamente
                $evento->update([
                    'status' => 'CANCELLED',
                    'cancellation_reason' => $request->reason,
                    'cancelled_by' => auth()->id(),
                    'cancelled_at' => now()
                ]);

                // Aquí podrías agregar lógica para notificar a los participantes
                return response()->json(['message' => 'Evento cancelado exitosamente']);
            } else {
                // Si es un participante, solo cancelar su participación
                if (!$evento->participants()->where('user_id', auth()->id())->exists()) {
                    return response()->json(['message' => 'No estás inscrito en este evento'], 422);
                }

                $evento->participants()->detach(auth()->id());
                
                // Registrar la razón de cancelación en una tabla pivote o log si es necesario
                
                return response()->json(['message' => 'Has cancelado tu participación en el evento']);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Error al cancelar el evento'], 500);
        }
    }
}
