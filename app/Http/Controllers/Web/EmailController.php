<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    /**
     * Activates a user account if the link's signature is valid.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function activate(Request $request)
    {
        $email = $request->query('email');
        if (!$request->hasValidSignature()) {
            return view('emails.error', ['message' => 'URL no valida.']);
        }
        $user = User::where('email', $email)->first();
        if (!$user) {
            return view('emails.error', ['message' => 'Usuario no encontrado.']);
        }
        $user->email_verified_at = now();
        $user->save();
        return view('emails.success', ['message' => 'Cuenta activada exitosamente.', 'name' => $user->name]);
    }
}
