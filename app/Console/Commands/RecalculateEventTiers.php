<?php

namespace App\Console\Commands;

use App\Models\Evento;
use App\Services\EventTierCalculator;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class RecalculateEventTiers extends Command
{
    protected $signature = 'events:recalculate-tiers';
    protected $description = 'Recalcula los tiers de todos los eventos';

    public function handle(EventTierCalculator $calculator)
    {
        $eventos = Evento::all();
        $bar = $this->output->createProgressBar(count($eventos));

        foreach ($eventos as $evento) {
            $calculator->calculateTier($evento);
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nTiers recalculados exitosamente!");
    }

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('events:recalculate-tiers')->daily();
    }
} 