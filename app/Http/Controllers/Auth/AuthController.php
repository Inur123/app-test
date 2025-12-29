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

        // ⬇️ kirim log REGISTER (auth.*)
        ExternalLogger::send('auth.register', [
            'user_id' => $user->id,
            'email'   => $user->email,
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

            // ⬇️ kirim log LOGIN (auth.*)
            ExternalLogger::send('auth.login', [
                'user_id' => $user?->id,
                'email'   => $user?->email,
                'ip'      => $request->ip(),
            ]);

            return redirect()->intended('/dashboard');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    // ===== LOGOUT =====
    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            // ⬇️ kirim log LOGOUT (auth.*)
            ExternalLogger::send('auth.logout', [
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

    // simpan data sebelum update (before)
    $before = $user->getOriginal(); // atau $user->toArray() juga boleh

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

    // data sesudah update (after)
    $after = $user->fresh()->toArray();

    // ⬇️ kirim log UPDATE PROFILE sebagai "update"
    // log_type diakhiri _updated supaya kena rule UPDATE (wajib before/after)
    ExternalLogger::send('user_profile_updated', [
        'user_id' => $user->id,
        'before'  => $before,
        'after'   => $after,
        'ip'      => $request->ip(), // opsional, nggak wajib di validasi tapi boleh dikirim
    ]);

    return back()->with('status', 'Profil berhasil diperbarui.');
}

}
