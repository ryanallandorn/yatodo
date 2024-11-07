<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function destroy($id)
    {
        // Logic to destroy the session by ID
        $sessions = collect(session('sessions'));

        // Filter out the session with the given ID
        $filtered = $sessions->filter(fn ($session) => $session['id'] !== $id);

        session(['sessions' => $filtered]);

        return response()->json(['message' => 'Session removed successfully.']);
    }
}
