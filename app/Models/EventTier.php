<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTier extends Model
{
    protected $fillable = [
        'evento_id',
        'tier',
        'organizer_rating_score',
        'participation_score',
        'subscription_score',
        'total_score'
    ];

    protected $casts = [
        'tier' => 'string',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
} 