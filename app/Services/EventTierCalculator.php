<?php

namespace App\Services;

use App\Models\Evento;
use App\Models\EventTier;

class EventTierCalculator
{
    private const TIER_THRESHOLDS = [   
        'PLATINUM' => 80,
        'GOLD' => 60,
        'SILVER' => 40,
        'BRONZE' => 20,
        'STANDARD' => 0
    ];

    public function calculateTier(Evento $evento): EventTier
    {
        // Calcular puntuaciones individuales
        $organizerScore = $this->calculateOrganizerScore($evento);
        $participationScore = $this->calculateParticipationScore($evento);
        $subscriptionScore = $this->calculateSubscriptionScore($evento);

        // Calcular puntuación total (máximo 100 puntos)
        $totalScore = $organizerScore + $participationScore + $subscriptionScore;

        // Determinar el tier basado en la puntuación total
        $tier = $this->determineTier($totalScore);

        // Crear o actualizar el registro de tier
        return EventTier::updateOrCreate(
            ['evento_id' => $evento->id],
            [
                'tier' => $tier,
                'organizer_rating_score' => $organizerScore,
                'participation_score' => $participationScore,
                'subscription_score' => $subscriptionScore,
                'total_score' => $totalScore
            ]
        );
    }

    private function calculateOrganizerScore(Evento $evento): int
    {
        // Máximo 40 puntos
        $score = 0;

        // Promedio de calificaciones del organizador (0-10)
        $avgRating = $evento->owner->ownedEventos()
            ->withAvg('reviews', 'rating')
            ->first()
            ->reviews_avg_rating ?? 0;

        // Convertir calificación a escala de 40 puntos
        $score += ($avgRating / 10) * 40;

        return (int) min($score, 40);
    }

    private function calculateParticipationScore(Evento $evento): int
    {
        // Máximo 35 puntos
        $score = 0;

        // Porcentaje de capacidad ocupada
        $capacityPercentage = ($evento->participants()->count() / $evento->capacity) * 100;
        $score += ($capacityPercentage / 100) * 20;

        // Bonus por alta participación
        if ($capacityPercentage >= 90) {
            $score += 15;
        } elseif ($capacityPercentage >= 75) {
            $score += 10;
        } elseif ($capacityPercentage >= 50) {
            $score += 5;
        }

        return (int) min($score, 35);
    }

    private function calculateSubscriptionScore(Evento $evento): int
    {
        // Máximo 25 puntos
        $score = 0;

        // Asignar puntos según el nivel de suscripción del organizador
        $score += match($evento->owner->subscription_tier) {
            'PLATINUM' => 25,
            'GOLD' => 20,
            'SILVER' => 15, 
            'BRONZE' => 10,
            default => 5
        };

        return $score;
    }

    private function determineTier(int $totalScore): string
    {
        $tier = match(true) {
            $totalScore >= self::TIER_THRESHOLDS['PLATINUM'] => 'PLATINUM',
            $totalScore >= self::TIER_THRESHOLDS['GOLD'] => 'GOLD',
            $totalScore >= self::TIER_THRESHOLDS['SILVER'] => 'SILVER',
            $totalScore >= self::TIER_THRESHOLDS['BRONZE'] => 'BRONZE',
            default => 'STANDARD'
        };
        return $tier;
    }
} 