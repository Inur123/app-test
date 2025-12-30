<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ExternalLogger;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    // ===== REGISTER =====
    public function registerForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // REGISTER => DATA_CREATE
        ExternalLogger::send('DATA_CREATE', [
            'user_id' => $user->id,
            'data'    => [
                'name'  => $user->name,
                'email' => $user->email,
            ],
            'ip'      => $request->ip(),
        ]);

        return redirect()->intended('/dashboard');
    }

    // ===== LOGIN =====
    public function loginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // LOGIN => AUTH_LOGIN
            ExternalLogger::send('AUTH_LOGIN', [
                'user_id' => $user?->id,
                'email'   => $user?->email,
                'ip'      => $request->ip(),
            ]);

            return redirect()->intended('/dashboard');
        }

        // LOGIN FAILED => AUTH_LOGIN_FAILED
        ExternalLogger::send('AUTH_LOGIN_FAILED', [
            'user_id' => null,
            'email'   => $request->input('email'),
            'ip'      => $request->ip(),
        ]);

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    // ===== LOGOUT =====
    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            // LOGOUT => AUTH_LOGOUT
            ExternalLogger::send('AUTH_LOGOUT', [
                'user_id' => $user->id,
                'email'   => $user->email,
                'ip'      => $request->ip(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // ===== PROFILE (EDIT & UPDATE) =====
    public function editProfile(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        // before data
        $before = $user->getOriginal();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // after data
        $after = $user->fresh()->toArray();

        // UPDATE PROFILE => DATA_UPDATE (wajib before & after)
        ExternalLogger::send('DATA_UPDATE', [
            'user_id' => $user->id,
            'before'  => $before,
            'after'   => $after,
            'ip'      => $request->ip(),
        ]);

        return back()->with('status', 'Profil berhasil diperbarui.');
    }
}
