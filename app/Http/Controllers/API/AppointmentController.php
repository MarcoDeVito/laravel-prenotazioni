<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AppointmentController extends Controller
{
    // ✅ LISTA APPUNTAMENTI
    public function index(Request $request)
    {
        
        // Se l'utente è admin ➜ mostra tutto
        if (Auth::user()->role === 'admin') {
            $appointments = Appointment::with('user', 'category')->get();
        }  if (Auth::user()->role === 'cliente') {
            // Se è cliente ➜ mostra solo i suoi
            $appointments = Appointment::where('user_id', Auth::id())->get();
        }


        return response()->json($appointments);
    }

    // ✅ CREA NUOVO APPUNTAMENTO
    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'color' => 'nullable|string'
        ]);

        $appointment = Appointment::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'] ?? null,
            'title' => 'Appuntamento - ' . $user->name ?? 'Nuovo appuntamento',
            'description' => $validated['description'] ?? null,
            'start' => $validated['start'],
            'end' => $validated['end'],
            'color' => $validated['color'] ?? '#3788d8',
            'status' => 'prenotato'
        ]);

        return response()->json($appointment, 201);
    }

    // ✅ VISUALIZZA APPUNTAMENTO SINGOLO
    public function show(Appointment $appointment)
    {
        $this->authorizeAccess($appointment);

        return response()->json($appointment);
    }

    // ✅ MODIFICA APPUNTAMENTO
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorizeAccess($appointment);

        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'color' => 'nullable|string',
            'status' => 'nullable|in:libero,prenotato,completato,cancellato'
        ]);

        $appointment->update($validated);

        return response()->json($appointment);
    }

    // ✅ ELIMINA APPUNTAMENTO
    public function destroy(Appointment $appointment)
    {
        $this->authorizeAccess($appointment);

        $appointment->delete();

        return response()->json(['message' => 'Appuntamento eliminato']);
    }

    // ✅ AUTORIZZAZIONE
    private function authorizeAccess(Appointment $appointment)
    {
        // Admin può vedere tutto
        if (Auth::check() && Auth::user()->role === 'admin') {
            return true;
        }

        // Cliente ➜ può agire solo sui propri appuntamenti
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Accesso non autorizzato');
        }

        return true;
    }
}
