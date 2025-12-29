<header class="h-16 bg-white border-b border-gray-300 flex items-center justify-between px-4 md:px-6">
  <div>
    <h1 class="text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
    <p class="text-xs text-gray-500">Selamat datang kembali 👋</p>
  </div>

  <div class="flex items-center gap-4">
    <div class="text-right">
      <p class="text-sm font-medium text-gray-800">
        {{ auth()->user()->name ?? 'User' }}
      </p>
      <p class="text-xs text-gray-500">
        {{ auth()->user()->email ?? '' }}
      </p>
    </div>

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button
        type="submit"
        class="rounded-lg bg-red-600 text-white px-3 py-1.5 text-xs md:text-sm font-medium
               hover:bg-red-700 active:bg-red-800 transition"
      >
        Logout
      </button>
    </form>
  </div>
</header>
