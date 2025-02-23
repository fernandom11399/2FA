<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginWhitPasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\TwoFactorCodeMail;
use App\Mail\VerifyAccountMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    /**
     * Show the register form.
     *
     *  @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    /**
     * Show the login form.
     *
     *  @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }


    /**
     *Sends an account activation email to the provided user.
     *
     * This method generates a temporary URL for account activation and sends an email with a link
     * for the user to activate their account. The URL is valid for 1 hour.
     *
     * @param \App\Models\User $user
     * @return void 
     */
    private function activationEmail(User $user)
    {
        $signedUrl = URL::temporarySignedRoute(
            'activate.account',
            now()->addHours(1),
            ['email' => $user->email]
        );
        Mail::to($user->email)->send(new VerifyAccountMail($signedUrl, $user->name));
    }

    /**
     * Register a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse 
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => hash('sha256', $request->password),
        ]);
        $this->activationEmail($user);

        return redirect()->route('verify.email')->with('success', 'Registro exitoso. Por favor, inicia sesión.');
    }
    /**
     * Show the register form.
     *
     *  @return \Illuminate\View\View
     */
    public function showVerifyEmail()
    {
        return view('auth.verify-email');
    }
    /**
     * Handle the user login with a verification code.
     *
     * This method first checks if the user's credentials are correct, then generates a
     * verification code, sends it to the user via email, and redirects the user to the
     * verification page with a signed URL.
     *
     * @param  \App\Http\Requests\LoginWhitPasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function LoginWithPasswordRequest(LoginWhitPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['password' => 'Credenciales incorrectas.']);
        }
        if ($user->email_verified_at === null) {
            return back()->withErrors(['password' => 'Verifique su cuenta.']);
        }
        if ($user->password !== hash('sha256', $request->password)) {
            return back()->withErrors(['password' => 'Credenciales incorrectas.']);
        }
        $code = strtoupper(Str::random(6));
        $user->update(['two_factor_code' => Hash::make($code)]);
        $signedUrl = URL::temporarySignedRoute(
            'verification.code',
            now()->addMinutes(10),
            ['email' => $user->email]
        );
        Mail::to($user->email)->send(new TwoFactorCodeMail($code, $user->name));
        return redirect()->route('verification.form')->with('signedUrl', $signedUrl);
    }

    /**
     * Show the login form.
     *  @param \Illuminate\Http\Request  $request
     *  @return \Illuminate\View\View
     */
    public function showVerificationCode(Request $request)
    {
        $signedUrl = $request->session()->get('signedUrl');
        return view('auth.code', ["signedUrl" => $signedUrl]);
    }

    /**
     * Verify the provided verification code and log the user in.
     *
     * This method validates the verification code entered by the user, checks the
     * validity of the signed URL, and logs in the user if the code is correct.
     * If the code or URL is invalid, an error is returned.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function verificationCode(Request $request)
    {
        $signedUrl = $request->input('signed_url');
        if (!$signedUrl) {
            return view('emails.error', ['message' => 'Datos perdidos, vuelva a intenarlo.']);
        }
        $urlRequest = Request::create($signedUrl, 'GET');
        if (!$urlRequest->hasValidSignature()) {
            return view('emails.error', ['message' => 'Datos perdidos, vuelva a intenarlo.']);
        }
        $request->validate([
            'verification_code' => 'required|alpha_num|size:6'
        ], [
            'verification_code.required' => 'El código de verificación es obligatorio.',
            'verification_code.alpha_num' => 'El código de verificación debe ser un alpanúmerico.',
            'verification_code.size' => 'El código de verificación debe tener exactamente 6 dígitos.',
        ]);
        $email = $urlRequest->query('email');
        $user = User::where('email', $email)->whereNotNull('email_verified_at')->first();
        if (!$user) {
            return redirect()->route('error.404');
        }
        if (!Hash::check($request->verification_code, $user->two_factor_code)) {
            Log::info("Invalid verification code attempt for user: " . $user->email . " at " . Carbon::now()->format('Y-m-d H:i:s'));
            return back()->withErrors(['verification_code' => 'Código inválido.'])
                ->withInput()
                ->with('signed_url', $request->input('signed_url'));
        }
        Auth::login($user);
        Log::info("The user " . $user->name . " logged in at " . Carbon::now()->format('Y-m-d H:i:s'));
        return redirect()->route('dashboard');
    }

    /**
     * Log out the authenticated user and invalidate the session.
     *
     * This method logs out the user, invalidates the session, and regenerates the CSRF token.
     * After that, it redirects the user to the login form with a success message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        Log::info("The user " . $user->name . " logged out at " . Carbon::now()->format('Y-m-d H:i:s'));
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }
}
