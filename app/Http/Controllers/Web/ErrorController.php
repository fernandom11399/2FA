<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ErrorController extends Controller
{
    /**
     * Show error 429.
     *
     *  @return \Illuminate\View\View
     */
    public function tooManyAttempts()
    {
        $ip = request()->ip();
        Log::info("Too many login attempts from IP: " . $ip . " at " . Carbon::now()->format('Y-m-d H:i:s'));
        return view('errors.error', [
            'error' => "Demasiados intentos de inicio de sesión. Por favor, inténtelo nuevamente en 15 minutos.",
            'status' => "429"
        ]);
    }
    public function notFound()
    {

        return view('errors.error', [
            'error' => "Recurso no encontrado.",
            'status' => "404"
        ]);
    }
}
