<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Evento extends Model
{
    /** @use HasFactory<\Database\Factories\EventoFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'start',
        'end',
        'location_name',
        'location_address',
        'location_url',
        'image',
        'color',
    ];

    /**
     * Get the user that owns the event.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the users participating in this event.
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_eventos', 'evento_id', 'user_id');
    }
}
