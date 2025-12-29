<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

  {{-- Tailwind via Vite --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
  <div class="w-full max-w-md bg-white rounded-2xl shadow p-6">
    <h1 class="text-2xl font-semibold text-gray-800">Login</h1>
    <p class="text-sm text-gray-500 mt-1">Masuk untuk melanjutkan</p>

    @if ($errors->any())
      <div class="mt-4 rounded-lg bg-red-50 border border-red-200 p-3 text-sm text-red-700">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="/login" class="mt-6 space-y-4">
      @csrf

      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input
          type="email"
          name="email"
          value="{{ old('email') }}"
          required
          class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="contoh@email.com"
        >
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input
          type="password"
          name="password"
          required
          class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="••••••••"
        >
      </div>

      <button
        type="submit"
        class="w-full rounded-lg bg-indigo-600 text-white py-2.5 font-medium hover:bg-indigo-700 active:bg-indigo-800 transition"
      >
        Masuk
      </button>
    </form>

    <p class="mt-6 text-sm text-gray-600">
      Belum punya akun?
      <a href="/register" class="text-indigo-600 hover:underline font-medium">Register</a>
    </p>
  </div>
</body>
</html>
