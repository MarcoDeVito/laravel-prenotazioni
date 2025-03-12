<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OpeningHour;
use App\Models\Holiday;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AvailabilityController extends Controller
{
    /**
     * Ritorna gli slot disponibili tra due date.
     */
    public function getAvailableSlots(Request $request)
    {
        // 🟢 Range di date dinamico: di default da adesso a fine mese successivo
        $startDate = $request->input('start', Carbon::now()->addHour()->startOfHour());
        $endDate   = $request->input('end', Carbon::now()->addMonthNoOverflow()->endOfMonth());

        $availableSlots = [];

        // 🔵 Per ogni giorno nel range
        $datePeriod = CarbonPeriod::create($startDate->copy()->startOfDay(), $endDate);

        foreach ($datePeriod as $date) {
            $dayOfWeek = $date->dayOfWeek;

            // 1️⃣ Verifica se è un giorno festivo
            if (Holiday::whereDate('date', $date->toDateString())->exists()) {
                continue; // Salta il giorno festivo
            }

            // 2️⃣ Prendi le fasce orarie disponibili per quel giorno
            $openingHours = OpeningHour::where('day_of_week', $dayOfWeek)->get();

            foreach ($openingHours as $opening) {
                $slotDurationMinutes = 60;

                // ⏰ Calcolo l'orario di inizio in base al giorno
                if ($date->isToday()) {
                    $currentHour = Carbon::now()->addHour()->startOfHour();

                    // Se l'apertura è prima di adesso, usa adesso; altrimenti usa l'apertura normale
                    $startTime = $currentHour->greaterThan(Carbon::parse($opening->start_time))
                        ? $currentHour
                        : Carbon::parse($opening->start_time);
                } else {
                    // Negli altri giorni normale apertura
                    $startTime = Carbon::parse($opening->start_time);
                }

                $endTime = Carbon::parse($opening->end_time);

                // Se l'orario di inizio è dopo o uguale alla fine, salta
                if ($startTime->greaterThanOrEqualTo($endTime)) {
                    continue;
                }

                // 3️⃣ Genera gli slot disponibili in base all'orario di apertura e chiusura
                $period = CarbonPeriod::create(
                    $date->copy()->setTimeFrom($startTime),
                    "{$slotDurationMinutes} minutes",
                    $date->copy()->setTimeFrom($endTime)->subMinutes($slotDurationMinutes)
                );

                foreach ($period as $slotStart) {
                    $slotEnd = $slotStart->copy()->addMinutes($slotDurationMinutes);

                    // 4️⃣ Controlla se lo slot è già occupato da un appuntamento
                    $appointmentExists = Appointment::where(function ($query) use ($slotStart, $slotEnd) {
                        $query->where(function ($q) use ($slotStart, $slotEnd) {
                            $q->where('start', '<', $slotEnd)
                              ->where('end', '>', $slotStart);
                        });
                    })->exists();

                    // 5️⃣ Se libero, aggiungi lo slot all'array di risposta
                    if (!$appointmentExists) {
                        $availableSlots[] = [
                            'start' => $slotStart->toDateTimeString(),
                            'end'   => $slotEnd->toDateTimeString(),
                        ];
                    }
                }
            }
        }

        return response()->json($availableSlots);
    }
}
